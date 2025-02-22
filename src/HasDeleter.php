<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasDeleter
{
    // Default column name, can be overridden in the model
    protected string $deletedByColumn = 'deleted_by';

    // Default relationship name, can be overridden in the model
    protected string $deleterRelation = 'deleter';

    // Arrays to include/exclude tracking of certain events
    protected array $trackInclude = ['deleting'];
    protected array $trackExclude = [];

    public static function bootHasDeleter(): void
    {
        $instance = new static;

        // Handle deleting event (soft deletes)
        if ($instance->shouldTrackEvent('deleting')) {
            static::deleting(function (Model $model) use ($instance) {
                if (method_exists($model, 'runSoftDelete') && Auth::check()) {
                    $userId = Auth::user()->id;

                    if (! $model->isDirty($instance->getDeletedByColumn())) {
                        $model->{$instance->getDeletedByColumn()} = $userId;
                        $model->save();
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

    protected function getDeletedByColumn(): string
    {
        return $this->deletedByColumn;
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, $this->getDeletedByColumn());
    }

    public function __call($method, $parameters)
    {
        if ($method === $this->deleterRelation) {
            return $this->deleter();
        }

        return parent::__call($method, $parameters);
    }
}
