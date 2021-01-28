<?php

namespace App\Http\Controllers\API\User;

use App\Enums\HttpCode;
use App\Enums\RouteGuards;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Repository\Contracts\User\UserRepositoryInterface;
use App\Util\RoleGuard;

class UserController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var \App\Repository\Eloquent\User\UserRepository
     */
    protected $userRepository;

    /**
     * The RoleGuard instance.
     *
     * @var \App\Util\RoleGuard
     */
    protected $roleGuard;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repository\Eloquent\User\UserRepository  $userRepository
     * @param \App\Util\RoleGuard $roleGuard
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleGuard $roleGuard
    ) {
        $this->userRepository = $userRepository;
        $this->roleGuard = $roleGuard;

        $this->middleware(RouteGuards::AdminOrSuperAdmin)->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->userRepository->all(), HttpCode::OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        return response()->json($request->persist(), HttpCode::CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function show(int $userId)
    {
        $user = $this->userRepository->findById($userId);

        if ($user == null) abort(404, 'There is no existing resource on the requested ID.');

        return response()->json($user, HttpCode::OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, int $userId)
    {
        return response()->json($request->persist($userId), HttpCode::NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $userId)
    {
        return response()->json($this->userRepository->deleteById($userId), HttpCode::NO_CONTENT);
    }

    /**
     * Remove the specified resource from storage permanently.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(int $userId)
    {
        return response()->json($this->userRepository->permanentlyDeleteById($userId), HttpCode::NO_CONTENT);
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function restore(int $userId)
    {
        return response()->json($this->userRepository->restoreById($userId), HttpCode::NO_CONTENT);
    }
}
