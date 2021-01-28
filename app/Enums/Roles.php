<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Roles extends Enum
{
    const SuperAdmin = 'super-admin';
    const Admin = 'admin';
    const PremiumUser = 'premium-user';
    const RegularUser = 'regular-user';
    const Guest = 'guest';

    const All = [
        self::SuperAdmin,
        self::Admin,
        self::RegularUser,
        self::PremiumUser,
        self::Guest,
    ];
}
