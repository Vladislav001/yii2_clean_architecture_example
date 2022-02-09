<?php

namespace app\controllers;
use yii\filters\AccessControl;
use Yii;

class ApiTaskController extends MainController
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
						'actions' => ['get-summary-by-directions'],
						'allow' => true,
						'matchCallback' => function ($rule, $action)
						{
							$postData = Yii::$app->getRequest()->getRawBody();
							$postData = json_decode($postData, true);

							$currentUserId = $this->userInstance->getProfile($this->accessToken)['id'];
							$userPermissions = $this->userInstance->getPermissionsInfo();

							if(Yii::$app->authManager->checkAccess(
								$currentUserId,
								$userPermissions['get_project']['key'],
								['project_id' => $postData['filter']['project_id']])
							)
							{
								return true;
							}

							return false;
						}
					],
					[
						// todo контроль
						'actions' => ['create', 'update', 'get-list', 'move-to-archive', 'get-by-id'],
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

	public function actionGetSummaryByDirections()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->getRequest()->getRawBody();
			$data = json_decode($data, true);
			return $this->taskInstance->getSummaryByDirections($data['filter'], $data['sort'], $data['pagination']);
		}

		return array();
	}

	public function actionGetList()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->getRequest()->getRawBody();
			$data = json_decode($data, true);
			return $this->taskInstance->getList($this->accessToken, $data['filter'], $data['sort'], $data['pagination']);
		}

		return array();
	}

	public function actionGetById()
	{
		if (Yii::$app->request->isGet)
		{
			$params = Yii::$app->request->get();

			return $this->taskInstance->getById($this->accessToken, $params['id']);
		}

		return array();
	}

	public function actionCreate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskInstance->create($this->accessToken, $data);
		}

		return array();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskInstance->update($this->accessToken, $data);
		}

		return array();
	}

	public function actionMoveToArchive()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->taskInstance->moveToArchive($this->accessToken, $data['id']);
		}

		return array();
	}
}