<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasCreatorAndUpdater
{
    // Default column names, can be overridden in the model
    protected string $createdByColumn = 'created_by';
    protected string $updatedByColumn = 'updated_by';

    // Default relationship names, can be overridden in the model
    protected string $creatorRelation = 'creator';
    protected string $updaterRelation = 'updater';

    // Arrays to include/exclude tracking of certain events
    protected array $trackInclude = ['creating', 'updating'];
    protected array $trackExclude = [];

    public static function bootHasCreatorAndUpdater(): void
    {
        $instance = new static;

        // Handle creating event
        if ($instance->shouldTrackEvent('creating')) {
            static::creating(function (Model $model) use ($instance) {
                if (Auth::check()) {
                    $userId = Auth::user()->id;

                    if (! $model->isDirty($instance->getCreatedByColumn())) {
                        $model->{$instance->getCreatedByColumn()} = $userId;
                    }

                    if (! $model->isDirty($instance->getUpdatedByColumn())) {
                        $model->{$instance->getUpdatedByColumn()} = $userId;
                    }
                }
            });
        }

        // Handle updating event
        if ($instance->shouldTrackEvent('updating')) {
            static::updating(function (Model $model) use ($instance) {
                if (Auth::check()) {
                    $userId = Auth::user()->id;

                    if (! $model->isDirty($instance->getUpdatedByColumn())) {
                        $model->{$instance->getUpdatedByColumn()} = $userId;
                    }
                }
            });
        }
    }

    protected function shouldTrackEvent(string $event): bool
    {
        // Check if the event is in the "exclude" list
        if (in_array($event, $this->trackExclude, true)) {
            return false;
        }

        // Otherwise, check if the event is in the "include" list
        if (in_array($event, $this->trackInclude, true)) {
            return true;
        }

        // Default to excluding the event if neither array contains it
        return false;
    }

    protected function getCreatedByColumn(): string
    {
        return $this->createdByColumn;
    }

    protected function getUpdatedByColumn(): string
    {
        return $this->updatedByColumn;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, $this->getCreatedByColumn());
    }

    public function updater()
    {
        return $this->belongsTo(User::class, $this->getUpdatedByColumn());
    }

    public function __call($method, $parameters)
    {
        if ($method === $this->creatorRelation) {
            return $this->creator();
        }

        if ($method === $this->updaterRelation) {
            return $this->updater();
        }

        return parent::__call($method, $parameters);
    }
}
