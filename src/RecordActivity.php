<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

trait RecordActivity
{
    public static function bootRecordActivity(): void
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (! $model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }

            if (! $model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });

        static::updating(function ($model) {
            if (! $model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });

        static::deleting(function ($model) {
            if (! $model->isDirty('deleted_by')) {
                $model->deleted_by = auth()->user()->id;
            }
        });
    }
}
