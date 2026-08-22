# Changelog

All notable changes to `record-activity-laravel` will be documented in this file.

## v3.1.0 - 2026-08-22

### Added
- `withCreatorAndUpdater()` and `withDeleter()` now accept an optional `bool $withForeignKey = true` and `string $usersTable = 'users'` argument. Pass `withForeignKey: false` to add the `created_by`/`updated_by`/`deleted_by` columns without a foreign key constraint to `users` — useful when the consuming app's `users.id` type has drifted from `bigint unsigned` (e.g. legacy `int` primary keys) and the constraint would otherwise fail with a MySQL errno 150 ("Foreign key constraint is incorrectly formed") during migration.
- `dropCreatorAndUpdater()` and `dropDeleter()` now accept a matching `bool $withForeignKey = true` so drop calls stay symmetric with however the columns were added.

### Fixed
- `withCreatorAndUpdater()`/`withDeleter()` previously used `foreignId(...)->constrained('users')`, which silently assumes `users.id` is `bigint unsigned`. Column creation is now done via `unsignedBigInteger(...)` with the foreign key added as a separate, opt-out step, so the columns and the constraint can be reasoned about independently.

Existing calls to all four macros are unaffected — the new parameters default to the previous behavior.

## v3.0.0 - 2026-07-21

### Fixed
- `HasDeleter`'s `bootHasDeleter()` used `method_exists($model, 'runSoftDelete')` to detect soft-delete support; replaced with a `class_uses_recursive()` check against `SoftDeletes::class`, which is unambiguous regardless of which model consumes the trait.
- Added missing type declarations across `TracksEvents`, `HasCreatorAndUpdater`, and `HasDeleter` (closure parameter types, `BelongsTo` generics, `$userModel` typing) — these traits were previously excluded from static analysis entirely (reported as "used zero times"), so these gaps had never been caught.

### Removed
- Dropped Laravel 10 support (minimum is now Laravel 11)

## v1.0.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/record-activity-laravel/commits/v1.0.0
