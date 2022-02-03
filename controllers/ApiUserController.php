<?php

namespace app\controllers;
use Yii;

class ApiUserController extends MainController
{
	public function behaviors()
	{
		return parent::behaviors();
	}

	public function actionGetProfile()
	{
		if (Yii::$app->request->isGet)
		{
			return $this->userInstance->getProfile($this->accessToken);
		}

		return array();
	}

	public function actionLogin()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->userInstance->login($data);
		}

		return array();
	}

	public function actionLogout()
	{
		if (Yii::$app->request->isPost)
		{
			return $this->userInstance->logout($this->accessToken);
		}

		return array();
	}

	public function actionLogoutAll()
	{
		if (Yii::$app->request->isPost)
		{
			return $this->userInstance->logoutAll($this->accessToken);
		}

		return array();
	}

	public function actionRegistration()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->userInstance->registration($data);
		}

		return array();
	}

	public function actionRestorePassword()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->userInstance->restorePassword($data['email']);
		}

		return array();
	}

	public function actionChangePasswordByToken()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->userInstance->changePasswordByToken($data['new_password'], $data['token']);
		}

		return array();
	}
}