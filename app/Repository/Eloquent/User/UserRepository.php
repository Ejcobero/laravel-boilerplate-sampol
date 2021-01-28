<?php

namespace App\Repository\Eloquent\User;

use App\Models\User;
use App\Repository\Contracts\User\UserRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Find a user by email.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->whereEmail($email)->first();
    }

    /**
     * Find a user by username.
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->model->whereUsername($username)->first();
    }

    /**
     * Logout a user from all devices by ID.
     *
     * @param int $userId
     * @return int number of tokens deleted
     */
    public function logoutUserFromAllDevices(int $userId): int
    {
        $user = $this->findById($userId);

        return $user->tokens()->delete();
    }

    /**
     * Logout a user from a given token or device.
     *
     * @param int $userId
     * @param int $tokenId
     * @return bool
     */
    public function logoutUserFromDevice(int $userId, int $tokenId): bool
    {
        $user = $this->findById($userId);

        return $user->tokens()->where('id', $tokenId)->delete();
    }

    /**
     * Add a base64 encoded file to a user profile image.
     *
     * @param int $userId
     * @param string $base64data
     * @return bool
     */
    public function uploadProfileImageFromBase64ById(int $userId, string $base64data): bool
    {
        $user = $this->findById($userId);

        $file = null;

        if ($base64data) {
            $user->clearMediaCollection('profile_image');

            $file = $user->addMediaFromBase64($base64data)->toMediaCollection('profile_image');
        }

        return $file != null;
    }

    /**
     * Add user profile image from URL.
     *
     * @param int $userId
     * @param string $url
     * @return bool
     */
    public function uploadProfileImageFromUrlById(int $userId, string $url): bool
    {
        $user = $this->findById($userId);

        $file = null;

        if ($url) {
            $user->clearMediaCollection('profile_image');

            $file = $user->addMediaFromUrl($url)->toMediaCollection('profile_image');
        }

        return $file != null;
    }

    /**
     * Delete profile image of a user.
     *
     * @param int $userId
     * @param int $profileImageId
     * @return void
     */
    public function deleteProfileImageById(int $userId)
    {
        $user = $this->findById($userId);

        if ($user != null) {
            $user->clearMediaCollection('profile_image');
        }
    }

    /**
     * Suspend a user, preventing them from authenticating.
     *
     * @param int $userId
     * @return null|User
     */
    public function suspendById(int $userId): ?User
    {
        $user = $this->findById($userId);

        return $user->suspend();
    }

    /**
     * Unsuspend a user, allowing them to authenticate.
     *
     * @param int $userId
     * @return User
     */
    public function unsuspendById(int $userId): ?User
    {
        $user = $this->findById($userId);

        return $user->unsuspend();
    }
}
