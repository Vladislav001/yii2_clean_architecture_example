<?php

namespace UseCases\Managers;

use Entities\User;
use UseCases\Interfaces\UserRepositoryInterface;
use Yii;

class UserManager
{
	private $userRepository;

	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function find(array $params) : array
	{
		return $this->userRepository->find($params);
	}

	public function getProfile(string $accessToken) : User
	{
		$userId = $this->userRepository->findUserIdByAccessToken($accessToken);
		return $this->userRepository->getProfile($userId);
	}

	public function login(User $data) : array
	{
		if (!filter_var($data->getEmail(), FILTER_VALIDATE_EMAIL))
		{
			throw new \Exception('email_is_not_valid');
		}

		$result = $this->userRepository->login($data);
		$this->userRepository->deleteOldTokens($result['access_token']);

		return $result;
	}

	public function logout(string $accessToken) : array
	{
		return $this->userRepository->logout($accessToken);
	}

	public function logoutAll(string $accessToken) : array
	{
		$userId = $this->userRepository->findUserIdByAccessToken($accessToken);
		return $this->userRepository->logoutAll($userId);
	}

	public function registration(User $data) : array
	{
		if (!filter_var($data->getEmail(), FILTER_VALIDATE_EMAIL))
		{
			throw new \Exception('email_is_not_valid');
		}

		$result = $this->userRepository->registration($data);

		return $result;
	}

	public function updateTokens(string $refreshToken): array
	{
		$result = $this->userRepository->updateTokens($refreshToken);
		return $result;
	}

	public function restorePassword(string $email): bool
	{
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			throw new \Exception('email_is_not_valid');
		}

		// если есть юзер - выслать на почту токен, иначе не выслать, но типо всегда [] выдавать, дабы не палить наличие почт
		$user = $this->find(array('where' => array(
			'email' => $email
		)))[0];

		if ($user)
		{
			$token = $this->userRepository->updatePasswordResetToken($user['id']);

			// todo именно с этого дев домена не доходят письма
			Yii::$app->mailer->compose('restore_password', [
				'url' => FRONTEND_URL .'?token=' . $token,
			])->setFrom(DOMAIN_EMAIL)
				->setTo($email)
				->setSubject('Restore Password')
				->send();

			return true;
		} else
		{
			return false;
		}
	}

	public function changePasswordByToken(string $newPassword, string $token): bool
	{
		return $this->userRepository->changePasswordByToken($newPassword, $token);
	}
}