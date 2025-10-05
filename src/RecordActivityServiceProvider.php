<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Schema\Blueprint;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RecordActivityServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('record-activity-laravel');
    }

    public function packageRegistered(): void
    {
        // Add created_by and updated_by columns
        Blueprint::macro('withHasCreatorAndUpdater', function () {
            /** @var Blueprint $this */
            $this->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $this->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // Add deleted_by column
        Blueprint::macro('withHasDeleter', function () {
            /** @var Blueprint $this */
            $this->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // Drop created_by and updated_by columns
        Blueprint::macro('dropHasCreatorAndUpdater', function () {
            /** @var Blueprint $this */
            $this->dropForeign(['created_by']);
            $this->dropForeign(['updated_by']);
            $this->dropColumn(['created_by', 'updated_by']);
        });

        // Drop deleted_by column
        Blueprint::macro('dropHasDeleter', function () {
            /** @var Blueprint $this */
            $this->dropForeign(['deleted_by']);
            $this->dropColumn(['deleted_by']);
        });
    }
}
