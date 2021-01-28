<?php

namespace App\Models;

use App\Models\Traits\WithDateFormatterAccessorsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use WithDateFormatterAccessorsTrait, SoftDeletes, HasFactory;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'date_created',
        'date_updated',
        'date_deleted',
        'created_since',
        'updated_since',
        'deleted_since'
    ];

    /**
     * Dates to be treated as Carbon instances
     *
     * @var array
     */
    public $dates = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];
}
