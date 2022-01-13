<?php

namespace UseCases\Interfaces;

use Entities\User;

interface UserRepositoryInterface
{
	public function find(array $params = array()) : array;

	public function getProfile(int $id) : User;

	public function login(User $data) : array;

	public function logout(string $accessToken) : array;

	public function logoutAll(int $id) : array;

	public function registration(User $data) : array;

	public function updateTokens(string $refreshToken) : array;

	public function deleteOldTokens(string $accessToken) : bool;

	public function updatePasswordResetToken(int $id) : string;

	public function changePasswordByToken(string $newPassword, string $token) : bool;
}