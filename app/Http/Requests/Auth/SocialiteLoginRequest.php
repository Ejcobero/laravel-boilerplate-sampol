<?php

namespace App\Http\Requests\Auth;

use App\Rules\OauthProviderRule;
use Illuminate\Foundation\Http\FormRequest;

class SocialiteLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'provider' => [
                'required',
                new OauthProviderRule
            ]
        ];
    }
}
