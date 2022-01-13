<?php

namespace app\controllers;
use Yii;

class ApiProjectController extends MainController
{
	public function actionCreate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			$data['logo'] = $_FILES['logo'];

			return $this->projectInstance->create($this->accessToken, $data);
		}

		return array();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			$data['logo'] = $_FILES['logo'];
			return $this->projectInstance->update($this->accessToken, $data);
		}

		return array();
	}

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