<?php

namespace app\controllers;
use Yii;

class ApiTokenController extends MainController
{
	// todo
//	public function behaviors()
//	{
//		parent::beforeAction(null);
//
//		return [
//			'access' => [
//				'class' => AccessControl::className(),
//				'rules' => [
//					[
//						'actions' => ['update'],
//						'allow' => true,
//						'matchCallback' => function ($rule, $action)
//						{
//							// todo
//						}
//					],
//				],
//				'denyCallback' => function ()
//				{
//					throw new \Exception('access_is_denied');
//				},
//			]
//		];
//	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			return $this->userInstance->updateTokens($this->refreshToken);
		}

		return array();
	}
}