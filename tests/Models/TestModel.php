<?php

declare(strict_types=1);

namespace AmdadulHaq\RecordActivity\Tests\Models;

use AmdadulHaq\RecordActivity\HasCreatorAndUpdater;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasCreatorAndUpdater;

    protected $table = 'test_models';

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->userModel = TestUser::class;

        parent::__construct($attributes);
    }
}
