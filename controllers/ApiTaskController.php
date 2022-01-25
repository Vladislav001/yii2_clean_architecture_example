<?php

namespace app\controllers;
use yii\filters\AccessControl;
use Yii;

class ApiTaskController extends MainController
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
						'actions' => ['get-summary-by-directions'],
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
						'actions' => ['create', 'update', 'get-list', 'move-to-archive', 'get-by-id'],
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

	public function actionGetSummaryByDirections()
	{
		if (Yii::$app->request->isGet)
		{
			$data = Yii::$app->request->get();

			return $this->taskInstance->getSummaryByDirections($this->accessToken, $data['project_id']);
		}

		return array();
	}

	public function actionGetList()
	{
		if (Yii::$app->request->isGet)
		{
			$params = Yii::$app->request->get();

			// *второй аргумент воспринимает не как работу c yii2, а как просто логику, которую разберем уже в слои репозитория,т.е
			// фактически все что влияет на работу с БД находится в слое репозитория
			return $this->taskInstance->getList($this->accessToken, array(
				'where' => array(
					'direction_id' => $params['direction_id']
				),
				'orderBy' => array(
					$params['sort_field'] => $params['sort_type']
				)
			));
		}

		return array();
	}

	/**
	 * Получить задачу по ID
	 * @return array
	 */
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