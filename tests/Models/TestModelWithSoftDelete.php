<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity\Tests\Models;

use AmdadulHaq\RecordActivity\HasCreatorAndUpdater;
use AmdadulHaq\RecordActivity\HasDeleter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestModelWithSoftDelete extends Model
{
    use HasCreatorAndUpdater;
    use HasDeleter;
    use SoftDeletes;

    protected $table = 'test_models_with_soft_delete';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $userModel = TestUser::class;
}
