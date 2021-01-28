<?php

namespace App\Models;

use App\Models\Traits\WithDateFormatterAccessorsTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class BaseUser extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasRoles, HasApiTokens, HasFactory,
        Notifiable, WithDateFormatterAccessorsTrait,
        InteractsWithMedia, SoftDeletes;

    protected $guard = 'api';

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'username',
        'is_suspended',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_suspended' => 'boolean'
    ];

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
}
