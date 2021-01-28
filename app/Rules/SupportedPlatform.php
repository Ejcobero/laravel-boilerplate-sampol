<?php

namespace App\Rules;

use App\Enums\SupportedPlatforms;
use Illuminate\Contracts\Validation\Rule;

class SupportedPlatform implements Rule
{
    private array $platforms = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->platforms = SupportedPlatforms::PLATFORMS;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, $this->platforms);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The provided platform :attribute is not supported.';
    }
}
