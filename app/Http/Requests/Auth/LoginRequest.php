<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use App\Models\User;
use App\Repository\Contracts\User\UserRepositoryInterface;
use Illuminate\Validation\Rule;

class LoginRequest extends BaseRequest
{
    /**
     * @var \App\Repository\Eloquent\User\UserRepository
     */
    private $userRepository;

    /**
     * LoginRequest constructor.
     *
     * @param \App\Repository\Eloquent\User\UserRepository $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->guest();
    }

    /**
     * @return User|null
     */
    public function persist($userId = null): ?User
    {
        $user = null;

        if ($this->has('email'))
            $user = $this->userRepository->findByEmail($this->email);
        else if ($this->has('username'))
            $user = $this->userRepository->findByUsername($this->username);

        if ($user == null) abort(422, 'We cannot find any matching records on the provided username or email.');

        return $user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'sometimes',
                'required',
                'email',
                'exists:users,email'
            ],
            'username' => [
                'sometimes',
                'required',
                'exists:users,username'
            ],
            'password' => 'required',
            'device_name' => 'required'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'device_name' => 'device name',
        ];
    }
}
