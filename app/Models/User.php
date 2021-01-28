<?php

namespace App\Models;

use App\Models\Socialite\OauthProvider;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class User extends BaseUser
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'role',
        'full_name',
        'date_created',
        'date_updated',
        'date_deleted',
        'created_since',
        'updated_since',
        'deleted_since'
    ];

    /**
     * A user has many oauth providers.
     *
     * @return HasMany
     */
    public function providers(): HasMany
    {
        return $this->hasMany(OauthProvider::class);
    }

    /**
     * Encrypt the password.
     *
     * @param string $value
     * @return $this
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);

        return $this;
    }

    /**
     * Get the first role the user has.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "$this->first_name $this->middle_name $this->last_name";
    }

    /**
     * Prevent user from authenticating into the application.
     *
     * @return null|User
     * @throws MassAssignmentException
     * @throws InvalidArgumentException
     */
    public function suspend(): ?User
    {
        if ($this->update([ 'is_suspended' => true ])) return $this->fresh();

        return null;
    }

    /**
     * Allow user to authenticate into the application.
     *
     * @return null|User
     * @throws MassAssignmentException
     * @throws InvalidArgumentException
     */
    public function unsuspend(): ?User
    {
        if ($this->update([ 'is_suspended' => false ])) return $this->fresh();

        return null;
    }
}
