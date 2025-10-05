<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use App\Models\User;
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

        // Configure which events to track
        $instance->trackInclude = ['creating', 'updating'];
        $instance->trackExclude = [];

        // Handle creating event
        if ($instance->shouldTrackEvent('creating')) {
            static::creating(function (Model $model) use ($instance) {
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

        // Handle updating event
        if ($instance->shouldTrackEvent('updating')) {
            static::updating(function (Model $model) use ($instance) {
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
        return $this->belongsTo(User::class, $this->getCreatedByColumn());
    }

    public function updater()
    {
        return $this->belongsTo(User::class, $this->getUpdatedByColumn());
    }
}
