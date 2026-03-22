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
        static::creating(function (Model $model): void {
            if (Auth::check()) {
                $userId = Auth::id();

                if (! $model->isDirty($model->getCreatedByColumn())) {
                    $model->{$model->getCreatedByColumn()} = $userId;
                }

                if (! $model->isDirty($model->getUpdatedByColumn())) {
                    $model->{$model->getUpdatedByColumn()} = $userId;
                }
            }
        });

        static::updating(function (Model $model): void {
            if (Auth::check()) {
                $userId = Auth::id();

                if (! $model->isDirty($model->getUpdatedByColumn())) {
                    $model->{$model->getUpdatedByColumn()} = $userId;
                }
            }
        });
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
