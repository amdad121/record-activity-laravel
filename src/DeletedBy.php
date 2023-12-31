<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

trait DeletedBy
{
    public static function bootDeletedBy(): void
    {
        // when model is deleting
        static::deleting(function ($model) {
            if (! $model->isDirty('deleted_by')) {
                $model->deleted_by = auth()->user()->id;
            }
        });
    }
}
