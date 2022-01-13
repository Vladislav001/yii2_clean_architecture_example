<?php

namespace Adapters\Controllers;

use Entities\User;
use UseCases\Managers\UserManager;

/**
 * Class UserController
 * @package Adapters\Controllers
 */
class UserController
{
	/**
	 * @var UserManager
	 */
	private $userManager;

	/**
	 * UserController constructor.
	 * @param UserManager $userManager
	 */
	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	/**
	 * Получить профиль
	 *
	 * @param string $accessToken
	 * @return array
	 */
	public function getProfile(string $accessToken) : array
	{
		$obj = $this->userManager->getProfile($accessToken);

		return array(
			"id" => $obj->getId(),
			"email" => $obj->getEmail(),
			"name" => $obj->getName(),
		);
	}

	/**
	 * Авторизация
	 *
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function login(array $data) : array
	{
		$data = new User(null, $data['email'], null, $data['password']);
		return $this->userManager->login($data);
	}

	/**
	 * Выход с устройства
	 *
	 * @param string $accessToken
	 * @return array
	 */
	public function logout(string $accessToken) : array
	{
		return $this->userManager->logout($accessToken);
	}

	/**
	 * Выход со всех устройств
	 *
	 * @param string $accessToken
	 * @return array
	 */
	public function logoutAll(string $accessToken) : array
	{
		return $this->userManager->logoutAll($accessToken);
	}

	/**
	 * Регистрация
	 *
	 * @param array $data
	 * @return array
	 * @throws \Exception
	 */
	public function registration(array $data) : array
	{
		$data = new User(null, $data['email'], $data['name'], $data['password']);
		return $this->userManager->registration($data);
	}

	/**
	 * Обновить токены
	 *
	 * @param string $refreshToken
	 * @return array
	 */
	public function updateTokens(string $refreshToken) : array
	{
		return $this->userManager->updateTokens($refreshToken);
	}

	/**
	 * Запрос на восстановление пароля (отправка письма на почту)
	 *
	 * @param string $email
	 * @return bool
	 * @throws \Exception
	 */
	public function restorePassword(string $email): bool
	{
		return $this->userManager->restorePassword($email);
	}

	/**
	 * Сменить пароль по временному токену
	 *
	 * @param string $newPassword
	 * @param string $token
	 * @return bool
	 */
	public function changePasswordByToken(string $newPassword, string $token) : bool
	{
		return $this->userManager->changePasswordByToken($newPassword, $token);
	}
}