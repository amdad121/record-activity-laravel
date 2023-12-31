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
        $package
            ->name('record-activity-laravel');
    }

    public function packageRegistered(): void
    {
        // Extend the Blueprint class to add custom methods
        Blueprint::macro('withCreatedByAndUpdatedBy', function () {
            $this->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $this->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
        });

        Blueprint::macro('withDeletedBy', function () {
            $this->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // Extend the Blueprint class to add custom drop methods
        Blueprint::macro('dropCreatedByAndUpdatedBy', function () {
            $this->dropForeign(['created_by', 'updated_by']);
            $this->dropColumn(['created_by', 'updated_by']);
        });

        Blueprint::macro('dropDeletedBy', function () {
            $this->dropForeign(['deleted_by']);
            $this->dropColumn(['deleted_by']);
        });
    }
}
