<?php

namespace App\Http\Requests\Media;

use App\Http\Requests\BaseRequest;
use App\Util\RoleGuard;

class ProfileImageRequest extends BaseRequest
{
    /**
     * @var \App\Util\RoleGuard
     */
    protected $roleGuard;

    /**
     * UserRequest constructor.
     *
     * @param \App\Util\RoleGuard $roleGuard
     * @return void
     */
    public function __construct(
        RoleGuard $roleGuard
    ) {
        $this->roleGuard = $roleGuard;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->roleGuard->verifiedUserOnly();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'base_64_data' => 'sometimes|required|base64image',
            'url' => 'sometimes|required|url'
        ];
    }
}
