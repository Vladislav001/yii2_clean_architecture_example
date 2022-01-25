<?php

namespace app\controllers;
use yii\filters\AccessControl;
use Yii;

class ApiTaskResponsibleController extends MainController
{
	public function behaviors()
	{
		parent::beforeAction(null);

		return [
			// надо подумать куда лучше определить этот код.
			'corsFilter' => [
				'class' => \yii\filters\Cors::className(),
				'cors'  => [
					'Origin'                           => ['*'],
					'Access-Control-Request-Method'    => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
					'Access-Control-Max-Age'           => 3600,
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['create'],
						'allow' => true,
						'matchCallback' => function ($rule, $action)
						{
							$postData = Yii::$app->request->post();
							$currentUserId = $this->userInstance->getProfile($this->accessToken)['id'];
							$userPermissions = $this->userInstance->getPermissionsInfo();

							if(Yii::$app->authManager->checkAccess(
								$currentUserId,
								$userPermissions['get_project']['key'],
								['project_id' => $postData['project_id']])
							)
							{
								return true;
							}

							return false;
						}
					],
					[
						'actions' => ['get-list'],
						'allow' => true,
						'matchCallback' => function ($rule, $action)
						{
							$getData = Yii::$app->request->get();
							$currentUserId = $this->userInstance->getProfile($this->accessToken)['id'];
							$userPermissions = $this->userInstance->getPermissionsInfo();

							if(Yii::$app->authManager->checkAccess(
								$currentUserId,
								$userPermissions['get_project']['key'],
								['project_id' => $getData['project_id']])
							)
							{
								return true;
							}

							return false;
						}
					],
					[
						// todo контроль
						'actions' => ['update', 'delete'],
						'allow' => true,
					],
				],
				'denyCallback' => function ()
				{
					throw new \Exception('access_is_denied');
				},
			]
		];
	}

	public function actionCreate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskResponsibleInstance->create($this->accessToken, $data);
		}

		return array();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskResponsibleInstance->update($this->accessToken, $data);
		}

		return array();
	}

	public function actionDelete()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskResponsibleInstance->delete($this->accessToken, $data['id']);
		}

		return array();
	}

	public function actionGetList()
	{
		if (Yii::$app->request->isGet)
		{
			$params = Yii::$app->request->get();
			return $this->taskResponsibleInstance->getList($this->accessToken, $params['project_id']);
		}

		return array();
	}
}