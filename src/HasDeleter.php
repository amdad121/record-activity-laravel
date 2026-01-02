<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasDeleter
{
    use TracksEvents;

    protected string $deletedByColumn = 'deleted_by';

    protected string $deleterRelation = 'deleter';

    public static function bootHasDeleter(): void
    {
        $instance = new static;

        $instance->trackInclude = ['deleting'];
        $instance->trackExclude = [];

        if ($instance->shouldTrackEvent('deleting')) {
            static::deleting(function (Model $model) use ($instance): void {
                if (method_exists($model, 'runSoftDelete') && Auth::check()) {
                    $userId = Auth::id();

                    if (! $model->isDirty($instance->getDeletedByColumn())) {
                        $model->{$instance->getDeletedByColumn()} = $userId;
                        $model->timestamps = false;
                        $model->saveQuietly();
                    }
                }
            });
        }
    }

    protected function getDeletedByColumn(): string
    {
        return $this->deletedByColumn;
    }

    public function deleter()
    {
        return $this->belongsTo($this->getUserModelClass(), $this->getDeletedByColumn());
    }
}
