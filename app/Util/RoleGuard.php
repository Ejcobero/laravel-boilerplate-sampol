<?php

namespace App\Util;

use App\Enums\Roles;
use App\Models\User;

class RoleGuard
{
    /**
     * Get the User model instance.
     *
     * @return User|null
     */
    private function user(): User
    {
        return auth()->user();
    }

    /**
     * Checks if the user is authenticated and has verified email address.
     *
     * @return bool
     */
    private function hasVerifiedEmail(): bool
    {
        return auth()->check() && $this->user()->hasVerifiedEmail();
    }

    /**
     * Checks if the current authenticated user has role of `super-admin`.
     *
     * @return bool
     */
    public function superAdminOnly(): bool
    {
        return $this->hasVerifiedEmail() && $this->user()->hasRole(Roles::SuperAdmin);
    }

    /**
     * Checks if the current authenticated user has role of `admin`.
     *
     * @return bool
     */
    public function adminOnly(): bool
    {
        return $this->hasVerifiedEmail() && $this->user()->hasAnyRole([
            Roles::SuperAdmin,
            Roles::Admin
        ]);
    }

    /**
     * Checks if the current authenticated user has role of `verified-user`.
     *
     * @return bool
     */
    public function verifiedUserOnly(): bool
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Checks if the current authenticated user has role of `premium-user`.
     *
     * @return bool
     */
    public function premiumUserOnly(): bool
    {
        return $this->hasVerifiedEmail() && $this->user()->hasAnyRole([
            Roles::SuperAdmin,
            Roles::Admin,
            Roles::PremiumUser
        ]);
    }

    /**
     * Checks if the current authenticated user is an `unverified-user`.
     *
     * @return bool
     */
    public function isUnverifiedUser(): bool
    {
        return auth()->check() && ! $this->user()->hasVerifiedEmail();
    }

    /**
     * Checks if it's the guest who is requesting the resource.
     *
     * @return bool
     */
    public function guestOnly(): bool
    {
        return auth()->guest();
    }
}
