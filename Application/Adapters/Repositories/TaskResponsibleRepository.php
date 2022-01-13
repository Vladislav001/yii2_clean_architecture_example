<?php

namespace Adapters\Repositories;

use UseCases\Interfaces\TaskResponsibleRepositoryInterface;
use Entities\TaskResponsible;
use app\models\TaskResponsible as TaskResponsibleModel;

class TaskResponsibleRepository extends MainRepository implements TaskResponsibleRepositoryInterface
{
	protected $model = TaskResponsibleModel::class;

	public function create(TaskResponsible $data) : array
	{
		$model = new $this->model();
		$model->name = $data->getName();
		$model->project_id = $data->getProjectId();;

		if ($model->validate())
		{
			$model->save();
			$result['id'] = $model->id;
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}

		return $result;
	}

	public function update(TaskResponsible $data) : bool
	{
		$model = $this->model::findOne(['id' => $data->getId()]);
		$model->name = $data->getName();

		if ($model->validate())
		{
			$model->update();
			return true;
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}
	}

	public function delete(int $id) : bool
	{
		$model = $this->model::findOne(['id' => $id]);

		if($model->delete()){
			return true;
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}
	}

	public function getList(int $projectId) : array
	{
		return $this->model::find()->where(['project_id' => $projectId])->asArray()->all();
	}
}