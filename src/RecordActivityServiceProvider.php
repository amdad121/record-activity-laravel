<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class RecordActivityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Add created_by and updated_by columns
        Blueprint::macro('withCreatorAndUpdater', function (): void {
            /** @var Blueprint $this */
            $this->foreignId('created_by')->nullable()->constrained('users')->cascadeOnDelete();
            $this->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // Add deleted_by column
        Blueprint::macro('withDeleter', function (): void {
            /** @var Blueprint $this */
            $this->foreignId('deleted_by')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // Drop created_by and updated_by columns
        Blueprint::macro('dropCreatorAndUpdater', function (): void {
            /** @var Blueprint $this */
            $this->dropForeign(['created_by']);
            $this->dropForeign(['updated_by']);
            $this->dropColumn(['created_by', 'updated_by']);
        });

        // Drop deleted_by column
        Blueprint::macro('dropDeleter', function (): void {
            /** @var Blueprint $this */
            $this->dropForeign(['deleted_by']);
            $this->dropColumn(['deleted_by']);
        });
    }

    public function boot(): void
    {
        //
    }
}
