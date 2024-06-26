<?php

namespace app\controllers;
use Yii;

class ApiTokenController extends MainController
{
	public function behaviors()
	{
		return parent::behaviors();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			return $this->userInstance->updateTokens($this->refreshToken);
		}

		return array();
	}
}