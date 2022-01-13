<?php

namespace Adapters\Repositories;

use UseCases\Interfaces\UserRepositoryInterface;
use Entities\User;
use FrameworksDrivers\JwtService;

use app\models\User as UserModel;
use app\models\UserJwt as UserJwt;
use Yii;

class UserRepository extends MainRepository implements UserRepositoryInterface
{
	protected $model = UserModel::class;
	protected $modelJwt = UserJwt::class;

	const ROLES = array(
		"admin" => array(
			"key" => "admin",
			"description" => "Администратор"
		),
		"user" => array(
			"key" => "user",
			"description" => "Пользователь"
		),
	);

	const PERMISSIONS = array(
		"get_project" => array(
			"key" => "get_project",
			"description" => "Получение проекта"
		),
		"change_rules_project" => array(
			"key" => "change_rules_project",
			"description" => "Изменение прав проекта"
		),
	);

	public function getRolesInfo()
	{
		return self::ROLES;
	}

	public function getPermissionsInfo()
	{
		return self::PERMISSIONS;
	}

	public function getProfile(int $id) : User
	{
		$UserModelData = $this->model::findOne(['id' => $id]);
		return new User($UserModelData->id, $UserModelData->email, $UserModelData->name);
	}

	public function login(User $data) : array
	{
		$result = array();

		$jwtService = new JwtService();
		$UserModelData = $this->model::findOne(['email' => $data->getEmail()]);

		if (!$UserModelData)
		{
			throw new \Exception('email_or_password_is_not_valid');
		}

		$validatePassword = Yii::$app->getSecurity()->validatePassword($data->getPassword(), $UserModelData->password);

		if (!$validatePassword)
		{
			throw new \Exception('email_or_password_is_not_valid');
		}

		$UserJwtModel = new $this->modelJwt();

		// *по факту контроль времени + id юзера внутри токена + в БД
		$UserJwtModel->user_id = $UserModelData->id;
		$UserJwtModel->access_token = $jwtService->CreateNewAccessToken($UserModelData->id);
		$UserJwtModel->access_token_expire_at = time() + ACCESS_TOKEN_LIFE_TIME;
		$UserJwtModel->refresh_token = $jwtService->CreateNewRefreshToken($UserModelData->id);
		$UserJwtModel->refresh_token_expire_at = time() + REFRESH_TOKEN_LIFE_TIME;

		if($UserJwtModel->save()){
			$result['access_token'] = $UserJwtModel->access_token;
			$result['refresh_token'] = $UserJwtModel->refresh_token;
		} else
		{
			throw new \Exception(current($UserJwtModel->errors)[0]);
		}

		return $result;
	}

	public function logout(string $accessToken) : array
	{
		$UserJwtModel = $this->modelJwt::findOne(['access_token' => $accessToken]);

		if($UserJwtModel->delete()){
			$result['logout'] = 1;
		} else
		{
			throw new \Exception(current($UserJwtModel->errors)[0]);
		}

		return $result;
	}

	public function logoutAll(int $userId) : array
	{
		$UserJwtModel = new $this->modelJwt();

		if($UserJwtModel->deleteAll(['user_id' => $userId])){
			$result['logout'] = 1;
		} else
		{
			throw new \Exception(current($UserJwtModel->errors)[0]);
		}

		return $result;
	}

	public function registration(User $data) : array
	{
		$result = array();

		$UserModel = new $this->model;
		$UserJwtModel = new $this->modelJwt();
		$jwtService = new JwtService();

		$UserModel->email = $data->getEmail();
		$UserModel->name = $data->getName();
		$UserModel->password = Yii::$app->security->generatePasswordHash($data->getPassword());

		$transaction = Yii::$app->db->beginTransaction();
		try {
			if (!$UserModel->save())
			{
				throw new \Exception(current($UserModel->errors)[0]);
			}

			$userRoles = $this->getRolesInfo();
			$auth = Yii::$app->authManager;
			$role = $auth->getRole($userRoles['user']['key']);
			$auth->assign($role, $UserModel->id);

			// *по факту контроль времени + id юзера внутри токена + в БД
			$UserJwtModel->user_id = $UserModel->id;
			$UserJwtModel->access_token = $jwtService->CreateNewAccessToken($UserModel->id);
			$UserJwtModel->access_token_expire_at = time() + ACCESS_TOKEN_LIFE_TIME;
			$UserJwtModel->refresh_token = $jwtService->CreateNewRefreshToken($UserModel->id);
			$UserJwtModel->refresh_token_expire_at = time() + REFRESH_TOKEN_LIFE_TIME;

			if (!$UserJwtModel->save())
			{
				throw new \Exception(current($UserJwtModel->errors)[0]);
			}

			$result['access_token'] = $UserJwtModel->access_token;
			$result['refresh_token'] = $UserJwtModel->refresh_token;
			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}

		return $result;
	}

	public function updateTokens(string $refreshToken) : array
	{
		$UserJwtModel = new $this->modelJwt();
		$jwtService = new JwtService();
		$userJwt = $UserJwtModel::findOne(['refresh_token' => $refreshToken]);
		$decodedToken = $jwtService->DecodeToken($refreshToken);

		// *вряд ли получится аналогичный токен с зашитым id юзера, но навсякий случай проверка
		if (!$userJwt || $userJwt->user_id != $decodedToken['data']['user_id'])
		{
			throw new \Exception('token_is_not_valid');
		}

		if ($userJwt->access_token_expire_at < time())
		{
			throw new \Exception('token_expired');
		}

		$userJwt->access_token = $jwtService->CreateNewAccessToken($userJwt->user_id);
		$userJwt->access_token_expire_at = time() + ACCESS_TOKEN_LIFE_TIME;
		$userJwt->refresh_token = $jwtService->CreateNewRefreshToken($userJwt->user_id);
		$userJwt->refresh_token_expire_at = time() + REFRESH_TOKEN_LIFE_TIME;

		if ($userJwt->update())
		{
			$result['access_token'] = $userJwt->access_token;
			$result['refresh_token'] = $userJwt->refresh_token;
		} else
		{
			throw new \Exception(current($userJwt->errors)[0]);
		}

		return $result;
	}

	public function deleteOldTokens(string $accessToken) : bool
	{
		$UserJwtModel = new $this->modelJwt();
		$userJwt = $UserJwtModel::findOne(['access_token' => $accessToken]);

		// удалить старые токены
		$countDelete = $UserJwtModel->deleteAll('user_id = :user_id AND refresh_token_expire_at < :time', [':user_id' => $userJwt->user_id, ':time' =>  time()]);

		return true;
	}

	public function updatePasswordResetToken(int $id) : string
	{
		$jwtService = new JwtService();
		$user = $this->model::findOne(['id' => $id]);
		$token = $jwtService->CreateNewPasswordResetToken($id);
		$user->password_reset_token = $token;

		if ($user->update())
		{
			return $token;
		} else
		{
			throw new \Exception(current($user->errors)[0]);
		}
	}

	public function changePasswordByToken(string $newPassword, string $token) : bool
	{
		$jwtService = new JwtService();

		$decodedToken = $jwtService->DecodeToken($token);
		$userID = $decodedToken['data']['user_id'];

		// *вряд ли получится аналогичный токен с зашитым id юзера, но навсякий случай проверка
		if (!$userID)
		{
			throw new \Exception('token_is_not_valid');
		}

		$user = $this->model::findOne(['id' => $userID]);
		$user->password = Yii::$app->security->generatePasswordHash($newPassword);

		if ($user->update())
		{
			return true;
		} else
		{
			throw new \Exception(current($user->errors)[0]);
		}
	}

	public function findUserIdByAccessToken($accessToken) : int
	{
		$jwtService = new JwtService();
		$userJwt = $this->modelJwt::findOne(['access_token' => $accessToken]);
		$decodedToken = $jwtService->DecodeToken($accessToken);

		// *вряд ли получится аналогичный токен с зашитым id юзера, но навсякий случай проверка
		if (!$userJwt || $userJwt->user_id != $decodedToken['data']['user_id'])
		{
			throw new \Exception('token_is_not_valid');
		}

		if ($userJwt->access_token_expire_at < time())
		{
			throw new \Exception('token_expired');
		} else
		{
			return $userJwt->user_id;
		}
	}
}