<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity;

use Illuminate\Support\Facades\Auth;

trait RecordActivity
{
    public static function bootRecordActivity()
    {
        static::creating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->save();
            }
        });

        static::updating(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->updated_by = $user->id;
                $model->save();
            }
        });

        static::deleting(function ($model) {
            $user = Auth::user();
            if ($user) {
                $model->deleted_by = $user->id;
                $model->save();
            }
        });
    }
}
