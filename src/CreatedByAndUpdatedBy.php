<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

trait CreatedByAndUpdatedBy
{
    public static function bootCreatedByAndUpdatedBy(): void
    {
        // when model is created
        static::creating(function ($model) {
            if (! $model->isDirty('created_by') && auth()->check()) {
                $model->created_by = auth()->user()->id;
            }

            if (! $model->isDirty('updated_by') && auth()->check()) {
                $model->updated_by = auth()->user()->id;
            }
        });

        // when model is updating
        static::updating(function ($model) {
            if (! $model->isDirty('updated_by') && auth()->check()) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }
}
