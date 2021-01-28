<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static DESKTOP()
 * @method static static MOBILE()
 * @method static static PLATFORMS()
 */
final class SupportedPlatforms extends Enum
{
    const DESKTOP = 'desktop';

    const MOBILE = 'mobile';

    const PLATFORMS = [self::DESKTOP, self::MOBILE];
}
