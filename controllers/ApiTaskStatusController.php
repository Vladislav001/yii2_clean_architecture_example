<?php

namespace app\controllers;
use yii\filters\AccessControl;
use Yii;

class ApiTaskStatusController extends MainController
{
	public function behaviors()
	{
		parent::beforeAction(null);
		$behaviors = parent::behaviors();

		return array_merge($behaviors, [
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
		]);
	}

	public function actionCreate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskStatusInstance->create($this->accessToken, $data);
		}

		return array();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskStatusInstance->update($this->accessToken, $data);
		}

		return array();
	}

	public function actionDelete()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskStatusInstance->delete($this->accessToken, $data['id']);
		}

		return array();
	}

	public function actionGetList()
	{
		if (Yii::$app->request->isGet)
		{
			$params = Yii::$app->request->get();
			return $this->taskStatusInstance->getList($this->accessToken, $params['project_id']);
		}

		return array();
	}
}