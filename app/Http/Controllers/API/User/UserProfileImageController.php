<?php

namespace App\Http\Controllers\API\User;

use App\Enums\HttpCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ProfileImageRequest;
use App\Repository\Contracts\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserProfileImageController extends Controller
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
     * @param ProfileImageRequest $request
     * @param int $userId
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws BadRequestException
     */
    public function uploadFromBase64(ProfileImageRequest $request, int $userId)
    {
        return response()->json(
            $this->userRepository->uploadProfileImageFromBase64ById($userId, $request->get('base_64_data')),
            HttpCode::OK
        );
    }

    /**
     * @param ProfileImageRequest $request
     * @param int $userId
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws BadRequestException
     */
    public function uploadFromUrl(ProfileImageRequest $request, int $userId)
    {
        return response()->json(
            $this->userRepository->uploadProfileImageFromUrlById($userId, $request->get('url')),
            HttpCode::OK
        );
    }

    /**
     * @param int $userId
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function removeProfileImage(int $userId)
    {
        return response()->json(
            $this->userRepository->deleteProfileImageById($userId),
            HttpCode::NO_CONTENT
        );
    }
}
