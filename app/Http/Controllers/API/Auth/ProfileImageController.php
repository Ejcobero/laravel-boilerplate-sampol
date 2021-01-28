<?php

namespace App\Http\Controllers\API\Auth;

use App\Enums\HttpCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ProfileImageRequest;
use App\Repository\Contracts\User\UserRepositoryInterface;

class ProfileImageController extends Controller
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
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws BadRequestException
     */
    public function uploadFromBase64(ProfileImageRequest $request)
    {
        return response()->json(
            $this->userRepository->uploadProfileImageFromBase64ById(auth()->id(), $request->get('base_64_data')),
            HttpCode::OK
        );
    }

    /**
     * @param ProfileImageRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws BadRequestException
     */
    public function uploadFromUrl(ProfileImageRequest $request)
    {
        return response()->json(
            $this->userRepository->uploadProfileImageFromUrlById(auth()->id(), $request->get('url')),
            HttpCode::OK
        );
    }

    /**
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function removeProfileImage()
    {
        return response()->json(
            $this->userRepository->deleteProfileImageById(auth()->id()),
            HttpCode::NO_CONTENT
        );
    }
}
