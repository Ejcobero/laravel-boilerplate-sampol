<?php

namespace App\Repository\Contracts\User;

use App\Models\User;
use App\Repository\Contracts\EloquentRepositoryInterface;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Find a user by email.
     *
     * @param string $email
     * @return \App\Models\User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find a user by username.
     *
     * @param string $username
     * @return \App\Models\User|null
     */
    public function findByUsername(string $username): ?User;

    /**
     * Logout a user from all devices by ID.
     *
     * @param int $userId
     * @return int number of tokens deleted
     */
    public function logoutUserFromAllDevices(int $userId): int;

    /**
     * Logout a user from a given token or device.
     *
     * @param int $userId
     * @param int $tokenId
     * @return bool
     */
    public function logoutUserFromDevice(int $userId, int $tokenId): bool;

    /**
     * Add a base64 encoded file to a user profile image.
     *
     * @param int $userId
     * @param string $base64data
     * @return bool
     */
    public function uploadProfileImageFromBase64ById(int $userId, string $base64data): bool;

    /**
     * Add user profile image from URL.
     *
     * @param int $userId
     * @param string $url
     * @return bool
     */
    public function uploadProfileImageFromUrlById(int $userId, string $url): bool;

    /**
     * Delete profile image of a user.
     *
     * @param int $userId
     * @param int $profileImageId
     * @return void
     */
    public function deleteProfileImageById(int $userId);

    /**
     * Suspend a user, preventing them from authenticating.
     *
     * @param int $userId
     * @return User
     */
    public function suspendById(int $userId): ?User;

    /**
     * Unsuspend a user, allowing them to authenticate.
     *
     * @param int $userId
     * @return User
     */
    public function unsuspendById(int $userId): ?User;
}
