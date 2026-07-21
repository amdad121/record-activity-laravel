# Changelog

All notable changes to `record-activity-laravel` will be documented in this file.

## v3.0.0 - 2026-07-21

### Fixed
- `HasDeleter`'s `bootHasDeleter()` used `method_exists($model, 'runSoftDelete')` to detect soft-delete support; replaced with a `class_uses_recursive()` check against `SoftDeletes::class`, which is unambiguous regardless of which model consumes the trait.
- Added missing type declarations across `TracksEvents`, `HasCreatorAndUpdater`, and `HasDeleter` (closure parameter types, `BelongsTo` generics, `$userModel` typing) — these traits were previously excluded from static analysis entirely (reported as "used zero times"), so these gaps had never been caught.

### Removed
- Dropped Laravel 10 support (minimum is now Laravel 11)

## v1.0.0 - 2024-10-08

**Full Changelog**: https://github.com/amdad121/record-activity-laravel/commits/v1.0.0
