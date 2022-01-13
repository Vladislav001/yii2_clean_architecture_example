<?php

namespace app\controllers;
use Yii;

class ApiDirectionController extends MainController
{
	public function actionCreate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->directionInstance->create($this->accessToken, $data);
		}

		return array();
	}

	public function actionUpdate()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->directionInstance->update($this->accessToken, $data);
		}

		return array();
	}

	public function actionDelete()
	{
		if (Yii::$app->request->isPost)
		{
			$data = Yii::$app->request->post();
			return $this->directionInstance->delete($this->accessToken, $data['id']);
		}

		return array();
	}

	public function actionGetList()
	{
		if (Yii::$app->request->isGet)
		{
			$params = Yii::$app->request->get();
			return $this->directionInstance->getList($this->accessToken, $params['project_id']);
		}

		return array();
	}
}