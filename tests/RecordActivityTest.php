<?php

declare(strict_types=1);

use AmdadulHaq\RecordActivity\Tests\Models\TestModel;
use AmdadulHaq\RecordActivity\Tests\Models\TestModelWithSoftDelete;
use AmdadulHaq\RecordActivity\Tests\Models\TestUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

beforeEach(function (): void {
    Schema::create('users', function ($table): void {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('test_models', function ($table): void {
        $table->id();
        $table->string('name');
        $table->withCreatorAndUpdater();
        $table->timestamps();
    });

    Schema::create('test_models_with_soft_delete', function ($table): void {
        $table->id();
        $table->string('name');
        $table->withCreatorAndUpdater();
        $table->withDeleter();
        $table->timestamps();
        $table->softDeletes();
    });
});

it('creates test user successfully', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    expect($user)->toBeInstanceOf(TestUser::class)
        ->and($user->name)->toBe('Test User');
});

it('sets created_by and updated_by on model creation when authenticated', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);

    $model = TestModel::create(['name' => 'Test Model']);

    expect($model->created_by)->toBe($user->id)
        ->and($model->updated_by)->toBe($user->id);

    Auth::logout();
});

it('does not set created_by and updated_by when not authenticated', function (): void {
    $model = TestModel::create(['name' => 'Test Model']);

    expect($model->created_by)->toBeNull()
        ->and($model->updated_by)->toBeNull();
});

it('sets updated_by on model update when authenticated', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $otherUser = TestUser::create([
        'name' => 'Other User',
        'email' => 'other@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);
    $model = TestModel::create(['name' => 'Original Name']);
    Auth::logout();

    Auth::login($otherUser);
    $model->update(['name' => 'Updated Name']);
    Auth::logout();

    $model->refresh();

    expect($model->updated_by)->toBe($otherUser->id)
        ->and($model->created_by)->toBe($user->id);
});

it('does not modify created_by and updated_by if explicitly set', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);

    $model = TestModel::create([
        'name' => 'Test Model',
        'created_by' => 999,
        'updated_by' => 888,
    ]);

    expect($model->created_by)->toBe(999)
        ->and($model->updated_by)->toBe(888);

    Auth::logout();
});

it('sets deleted_by on soft delete when authenticated', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);

    $model = TestModelWithSoftDelete::create(['name' => 'Test Model']);
    $model->delete();

    expect($model->deleted_by)->toBe($user->id);

    Auth::logout();
});

it('does not set deleted_by when not authenticated', function (): void {
    $model = TestModelWithSoftDelete::create(['name' => 'Test Model']);
    $model->delete();

    expect($model->deleted_by)->toBeNull();
});

it('has creator relationship', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);
    $model = TestModel::create(['name' => 'Test Model']);
    Auth::logout();

    $creator = $model->creator;

    expect($creator)->not->toBeNull()
        ->and($creator->id)->toBe($user->id)
        ->and($creator->name)->toBe('Test User');
});

it('has updater relationship', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);
    $model = TestModel::create(['name' => 'Test Model']);
    Auth::logout();

    $updater = $model->updater;

    expect($updater)->not->toBeNull()
        ->and($updater->id)->toBe($user->id)
        ->and($updater->name)->toBe('Test User');
});

it('has deleter relationship', function (): void {
    $user = TestUser::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Auth::login($user);
    $model = TestModelWithSoftDelete::create(['name' => 'Test Model']);
    $model->delete();
    Auth::logout();

    $deleter = $model->deleter;

    expect($deleter)->not->toBeNull()
        ->and($deleter->id)->toBe($user->id)
        ->and($deleter->name)->toBe('Test User');
});
