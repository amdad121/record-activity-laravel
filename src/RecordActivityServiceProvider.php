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
        Blueprint::macro('withCreatorAndUpdater', function (bool $withForeignKey = true, string $usersTable = 'users'): void {
            /** @var Blueprint $this */
            $this->unsignedBigInteger('created_by')->nullable();
            $this->unsignedBigInteger('updated_by')->nullable();

            if ($withForeignKey) {
                $this->foreign('created_by')->references('id')->on($usersTable)->cascadeOnDelete();
                $this->foreign('updated_by')->references('id')->on($usersTable)->cascadeOnDelete();
            }
        });

        // Add deleted_by column
        Blueprint::macro('withDeleter', function (bool $withForeignKey = true, string $usersTable = 'users'): void {
            /** @var Blueprint $this */
            $this->unsignedBigInteger('deleted_by')->nullable();

            if ($withForeignKey) {
                $this->foreign('deleted_by')->references('id')->on($usersTable)->cascadeOnDelete();
            }
        });

        // Drop created_by and updated_by columns
        Blueprint::macro('dropCreatorAndUpdater', function (bool $withForeignKey = true): void {
            /** @var Blueprint $this */
            if ($withForeignKey) {
                $this->dropForeign(['created_by']);
                $this->dropForeign(['updated_by']);
            }
            $this->dropColumn(['created_by', 'updated_by']);
        });

        // Drop deleted_by column
        Blueprint::macro('dropDeleter', function (bool $withForeignKey = true): void {
            /** @var Blueprint $this */
            if ($withForeignKey) {
                $this->dropForeign(['deleted_by']);
            }
            $this->dropColumn(['deleted_by']);
        });
    }

    public function boot(): void
    {
        //
    }
}
