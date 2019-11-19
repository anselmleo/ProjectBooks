<?php


namespace App\Repositories\Contracts;

interface IUserRepository
{
    public function register(array $params, $role);

    public function verifyEmail($token);

    public function authenticate(array $credentials);

    public function updateLastLogin($user_id, $ip);

    public function profile(int $user_id, array $params);

    public function updatePassword(int $user_id, array $params);

    public function getUsers($perPage = 15, $orderBy = 'created_at', $sort = 'desc');
}
