<?php

namespace Adapters\Repositories;

use UseCases\Interfaces\TaskTypeRepositoryInterface;
use Entities\TaskType;
use app\models\TaskType as TaskTypeModel;

class TaskTypeRepository extends MainRepository implements TaskTypeRepositoryInterface
{
	protected $model = TaskTypeModel::class;

	public function create(TaskType $data) : array
	{
		$model = new $this->model();
		$model->name = $data->getName();
		$model->project_id = $data->getProjectId();;
		$model->sort = $data->getSort();

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

	public function update(TaskType $data) : bool
	{
		$model = $this->model::findOne(['id' => $data->getId()]);
		$model->name = $data->getName();
		$model->sort = $data->getSort() ?? $model->sort;

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

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		$query = $this->model::find();

		if ($filter['project_id'])
		{
			$query->where(["project_id" => $filter['project_id']]);
		}

		if ($sort['field'] && $sort['type'])
		{
			$query->orderBy([$sort['field'] => mb_strtoupper($sort['type']) == 'DESC' ? SORT_DESC : SORT_ASC]);
		}

		$result = $query->asArray()->all();

		return $result;
	}
}