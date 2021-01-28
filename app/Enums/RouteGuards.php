<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class RouteGuards extends Enum
{
    const Authenticated = 'auth:sanctum';

    const All = 'role:'.
        Roles::SuperAdmin. '|' .
        Roles::Admin. '|' .
        Roles::RegularUser. '|' .
        Roles::PremiumUser. '|' .
        Roles::Guest;

    const SuperAdminOnly = 'role:'.Roles::SuperAdmin;

    const AdminOrSuperAdmin = 'role:'.Roles::SuperAdmin.'|'.Roles::Admin;

    const EnsureEmailIsVerified = 'verified';
}
