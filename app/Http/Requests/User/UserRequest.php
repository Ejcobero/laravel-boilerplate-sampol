<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;
use App\Models\User;
use App\Repository\Contracts\User\UserRepositoryInterface;
use App\Util\RoleGuard;
use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
{
    /**
     * @var \App\Repository\Eloquent\User\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var \App\Util\RoleGuard
     */
    protected $roleGuard;

    /**
     * UserRequest constructor.
     *
     * @param \App\Repository\Eloquent\User\UserRepositoryInterface $userRepository
     * @param \App\Util\RoleGuard $roleGuard
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleGuard $roleGuard
    ) {
        $this->userRepository = $userRepository;
        $this->roleGuard = $roleGuard;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->roleGuard->adminOnly();
    }

    /**
     * @param int|null $modelId
     * @return User|bool|null
     */
    public function persist($userId = null)
    {
        switch ($this->method()) {
            case 'POST':
                return $this->userRepository->create($this->all());

            case 'PUT':
                return $this->userRepository->update($userId, $this->all());

            default:
                return null;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'email|required|unique:users,email',
                    'username' => 'required|unique:users,username',
                    'password' => 'required|min:6',
                    'role' => 'required',
                ];

            case 'PUT':
                return [
                    // validate if userId param exists in `users` table
                    'id' => Rule::exists('users')->where(function ($query) {
                        $query->where('id', $this->userId);
                    }),
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'email|required|unique:users,email,'.$this->userId,
                    'username' => 'required|unique:users,username,'.$this->userId,
                    'password' => 'sometimes|required|min:6',
                    'role' => 'required',
                ];

            default:
                return [];
        }
    }
}
