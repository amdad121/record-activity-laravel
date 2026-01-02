<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasCreatorAndUpdater
{
    use TracksEvents;

    protected string $createdByColumn = 'created_by';

    protected string $updatedByColumn = 'updated_by';

    protected string $creatorRelation = 'creator';

    protected string $updaterRelation = 'updater';

    public static function bootHasCreatorAndUpdater(): void
    {
        $instance = new static;

        $instance->trackInclude = ['creating', 'updating'];
        $instance->trackExclude = [];

        if ($instance->shouldTrackEvent('creating')) {
            static::creating(function (Model $model) use ($instance): void {
                if (Auth::check()) {
                    $userId = Auth::id();

                    if (! $model->isDirty($instance->getCreatedByColumn())) {
                        $model->{$instance->getCreatedByColumn()} = $userId;
                    }

                    if (! $model->isDirty($instance->getUpdatedByColumn())) {
                        $model->{$instance->getUpdatedByColumn()} = $userId;
                    }
                }
            });
        }

        if ($instance->shouldTrackEvent('updating')) {
            static::updating(function (Model $model) use ($instance): void {
                if (Auth::check()) {
                    $userId = Auth::id();

                    if (! $model->isDirty($instance->getUpdatedByColumn())) {
                        $model->{$instance->getUpdatedByColumn()} = $userId;
                    }
                }
            });
        }
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
        return $this->belongsTo($this->getUserModelClass(), $this->getCreatedByColumn());
    }

    public function updater()
    {
        return $this->belongsTo($this->getUserModelClass(), $this->getUpdatedByColumn());
    }
}
