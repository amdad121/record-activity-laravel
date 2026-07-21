<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait HasDeleter
{
    use TracksEvents;

    protected string $deletedByColumn = 'deleted_by';

    protected string $deleterRelation = 'deleter';

    public static function bootHasDeleter(): void
    {
        static::deleting(function (self $model): void {
            if (in_array(SoftDeletes::class, class_uses_recursive($model), true) && Auth::check()) {
                $userId = Auth::id();

                if (! $model->isDirty($model->getDeletedByColumn())) {
                    $model->{$model->getDeletedByColumn()} = $userId;
                    $model->timestamps = false;
                    $model->saveQuietly();
                }
            }
        });
    }

    protected function getDeletedByColumn(): string
    {
        return $this->deletedByColumn;
    }

    /**
     * @return BelongsTo<Model, $this>
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo($this->getUserModelClass(), $this->getDeletedByColumn());
    }
}
