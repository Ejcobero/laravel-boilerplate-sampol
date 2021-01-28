<?php

use App\Enums\RouteGuards;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\RegistrationController;
use App\Http\Controllers\API\Auth\VerificationController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\UserController as JammController;
use App\Http\Controllers\API\User\UserProfileImageController;
use App\Http\Controllers\API\Auth\ProfileImageController;
use App\Http\Controllers\API\Auth\SocialiteAuthController;
use App\Http\Controllers\API\User\UserSuspensionController;
use App\Http\Controllers\BookController;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->get('jammix', [JammController::class, 'index']);

$router->get('books', [BookController::class, 'index']);
$router->get('books/{bookId}', [BookController::class, 'show']);
$router->post('books', [BookController::class, 'store']);
$router->delete('books/{bookId}', [BookController::class, 'destroy']);
$router->patch('books/{bookId}/restore', [BookController::class, 'restore']);
$router->delete('books/{bookId}/force-delete', [BookController::class, 'forceDestroy']);

// PUT/PATCH
$router->put('books/{bookId}', [BookController::class, 'update']);
// $router->put('books/{bookId}/mga-tao', [BookMgaTaoController::class, 'update']);

$router->patch('books/{bookId}/nabasa-na', [BookController::class, 'readBook']);
$router->patch('books/{bookid}/un-nabasa-na', [BookController::class, 'unreadBook']);

/**
 * Authentication routes.
 */
$router->prefix('auth')->group(function (Router $router) {
    /**
     * Accessible only by unauthenticated users.
     */
    $router->post('login', [AuthController::class, 'login']);
    $router->post('register', [RegistrationController::class, 'register']);
    $router->get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    /**
     * Accessible only by authenticated users.
     */
    $router->middleware([RouteGuards::Authenticated])->group(function (Router $router) {
        $router->get('user', [AuthController::class, 'getCurrentAuthenticatedUser']);
        $router->get('email/resend', [VerificationController::class, 'resend']);
        $router->delete('logout', [AuthController::class, 'logout']);
        $router->delete('logout-all-devices', [AuthController::class, 'logoutAllDevices']);

        $router->post('profile-images/base64', [ProfileImageController::class, 'uploadFromBase64'])->name('auth.profile-images.base64');
        $router->post('profile-images/url', [ProfileImageController::class, 'uploadFromUrl'])->name('auth.profile-images.url');
        $router->delete('profile-images', [ProfileImageController::class, 'removeProfileImage'])->name('auth.profile-images.destroy');
    });

    /**
     * Accessible only by `super-admin` or `admin` roles.
     */
    $router->middleware([
        RouteGuards::Authenticated,
        RouteGuards::AdminOrSuperAdmin,
    ])->group(function (Router $router) {
        $router->delete('user/{userId}/logout-all-devices', [AuthController::class, 'logoutUserFromAllDevices']);
        $router->delete('user/{userId}/logout', [AuthController::class, 'logoutUser']);
    });
});

/**
 * Business Logic, Authenticated routes.
 */
$router->middleware(['auth', 'role:super-admin|admin', 'role_or_permission:UserModule:Create,super-admin'])->group(function (Router $router) {
    /**
     * User Module.
     */
    $router->group([], function (Router $router) {
        $router->delete('users/{userId}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        $router->get('users/{userId}/restore', [UserController::class, 'restore'])->name('users.restore');
        $router->patch('users/{userId}/suspend', [UserSuspensionController::class, 'suspend'])->name('users.suspend');
        $router->patch('users/{userId}/unsuspend', [UserSuspensionController::class, 'unsuspend'])->name('users.unsuspend');

        $router->apiResource('users', UserController::class)->parameters(['users' => 'userId']);

        $router->post('users/{userId}/profile-images/base64', [UserProfileImageController::class, 'uploadFromBase64'])->name('users.profile-images.base64');
        $router->post('users/{userId}/profile-images/url', [UserProfileImageController::class, 'uploadFromUrl'])->name('users.profile-images.url');
        $router->delete('users/{userId}/profile-images', [UserProfileImageController::class, 'removeProfileImage'])->name('users.profile-images.destroy');
    });
});

/**
 * Fallback route, if resource not existing return 404 with the message.
 */
$router->fallback(function () {
    return response()->json([
        'message' => 'The resource you requested doesn\'t exist.'
    ], 404);
});
