<?php

namespace App\Http\Controllers\API\User;

use App\Enums\HttpCode;
use App\Http\Controllers\Controller;
use App\Repository\Contracts\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserSuspensionController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var \App\Repository\Eloquent\User\UserRepository
     */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repository\Eloquent\User\UserRepository  $userRepository
     * @param \App\Util\RoleGuard $roleGuard
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $userId
     * @return JsonResponse
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws BindingResolutionException
     */
    public function suspend(int $userId)
    {
        return response()->json(
            $this->userRepository->suspendById($userId), HttpCode::ACCEPTED
        );
    }

    /**
     * @param int $userId
     * @return JsonResponse
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws BindingResolutionException
     */
    public function unsuspend(int $userId)
    {
        return response()->json(
            $this->userRepository->unsuspendById($userId), HttpCode::ACCEPTED
        );
    }
}
