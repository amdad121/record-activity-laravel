<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

it('can use withCreatorAndUpdater blueprint macro', function (): void {
    Schema::dropIfExists('test_table');

    Schema::create('test_table', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->withCreatorAndUpdater();
        $table->timestamps();
    });

    $columns = Schema::getColumnListing('test_table');

    expect($columns)->toContain('id')
        ->toContain('name')
        ->toContain('created_by')
        ->toContain('updated_by')
        ->toContain('created_at')
        ->toContain('updated_at');

    Schema::dropIfExists('test_table');
});

it('can use withDeleter blueprint macro', function (): void {
    Schema::dropIfExists('test_table');

    Schema::create('test_table', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->withDeleter();
        $table->timestamps();
    });

    $columns = Schema::getColumnListing('test_table');

    expect($columns)->toContain('id')
        ->toContain('name')
        ->toContain('deleted_by')
        ->toContain('created_at')
        ->toContain('updated_at');

    Schema::dropIfExists('test_table');
});

it('can use dropCreatorAndUpdater blueprint macro', function (): void {
    Schema::dropIfExists('test_table');

    Schema::create('test_table', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->withCreatorAndUpdater();
        $table->timestamps();
    });

    Schema::table('test_table', function (Blueprint $table): void {
        $table->dropCreatorAndUpdater();
    });

    $columns = Schema::getColumnListing('test_table');

    expect($columns)->toContain('id')
        ->toContain('name')
        ->not->toContain('created_by')
        ->not->toContain('updated_by');

    Schema::dropIfExists('test_table');
});

it('can use dropDeleter blueprint macro', function (): void {
    Schema::dropIfExists('test_table');

    Schema::create('test_table', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->withDeleter();
        $table->timestamps();
    });

    Schema::table('test_table', function (Blueprint $table): void {
        $table->dropDeleter();
    });

    $columns = Schema::getColumnListing('test_table');

    expect($columns)->toContain('id')
        ->toContain('name')
        ->not->toContain('deleted_by');

    Schema::dropIfExists('test_table');
});

it('creates foreign key constraints withCreatorAndUpdater', function (): void {
    Schema::dropIfExists('users');
    Schema::dropIfExists('test_table');

    Schema::create('users', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('test_table', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->withCreatorAndUpdater();
        $table->timestamps();
    });

    $foreignKeys = collect(Schema::getForeignKeys('test_table'));

    expect($foreignKeys)->toHaveCount(2);

    Schema::dropIfExists('test_table');
    Schema::dropIfExists('users');
});

it('creates foreign key constraints withDeleter', function (): void {
    Schema::dropIfExists('users');
    Schema::dropIfExists('test_table');

    Schema::create('users', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('test_table', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->withDeleter();
        $table->timestamps();
    });

    $foreignKeys = collect(Schema::getForeignKeys('test_table'));

    expect($foreignKeys)->toHaveCount(1);

    Schema::dropIfExists('test_table');
    Schema::dropIfExists('users');
});
