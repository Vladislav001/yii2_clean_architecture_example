<?php

namespace app\controllers;
use Yii;

class ApiTokenController extends MainController
{
	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			return $this->userInstance->updateTokens($this->refreshToken);
		}

		return array();
	}
}