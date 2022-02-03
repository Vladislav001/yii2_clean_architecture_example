<?php

namespace app\controllers;
use yii\filters\AccessControl;
use Yii;

class ApiProjectController extends MainController
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
							if ($this->userInstance->getProfile($this->accessToken)['id'])
							{
								return true;
							}
							return false;
						}
					],
					[
						'actions' => ['update'],
						'allow' => true,
						'matchCallback' => function ($rule, $action)
						{
							$postData = Yii::$app->request->post();
							$currentUserId = $this->userInstance->getProfile($this->accessToken)['id'];
							$userPermissions = $this->userInstance->getPermissionsInfo();

							if(Yii::$app->authManager->checkAccess(
								$currentUserId,
								$userPermissions['get_project']['key'],
								['project_id' => $postData['id']])
							)
							{
								return true;
							}

							return false;
						}
					],
					[
						'actions' => ['create-permission', 'delete-permission'],
						'allow' => true,
						'matchCallback' => function ($rule, $action)
						{
							$postData = Yii::$app->request->post();
							$currentUserId = $this->userInstance->getProfile($this->accessToken)['id'];
							$userPermissions = $this->userInstance->getPermissionsInfo();

							if(Yii::$app->authManager->checkAccess(
								$currentUserId,
								$userPermissions['change_rules_project']['key'],
								['project_id' => $postData['project_id']])
							)
							{
								return true;
							}

							return false;
						}
					],
					[
						// todo контроль
						'actions' => ['get-list'],
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
			$data['logo'] = $_FILES['logo']; // todo мб в отдельной табле БД будут

			return $this->projectInstance->create($this->accessToken, $data);
		}

		return array();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			$data['logo'] = $_FILES['logo']; // todo мб в отдельной табле БД будут
			return $this->projectInstance->update($this->accessToken, $data);
		}

		return array();
	}

	// внутри проверка и логика, т.к для админа все проекты будут
	public function actionGetList()
	{
		if (Yii::$app->request->isGet)
		{
			return $this->projectInstance->getList($this->accessToken);
		}

		return array();
	}

	public function actionCreatePermission()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->projectInstance->createPermission($this->accessToken, $data['project_id'], $data['user_id'], $data['permission']);
		}

		return array();
	}

	public function actionDeletePermission()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->projectInstance->deletePermission($this->accessToken, $data['project_id'], $data['user_id'], $data['permission']);
		}

		return array();
	}
}