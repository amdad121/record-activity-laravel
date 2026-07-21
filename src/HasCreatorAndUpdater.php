<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        static::creating(function (self $model): void {
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

        static::updating(function (self $model): void {
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

    /**
     * @return BelongsTo<Model, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo($this->getUserModelClass(), $this->getCreatedByColumn());
    }

    /**
     * @return BelongsTo<Model, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo($this->getUserModelClass(), $this->getUpdatedByColumn());
    }
}
