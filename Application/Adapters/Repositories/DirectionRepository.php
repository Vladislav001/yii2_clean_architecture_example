<?php

namespace Adapters\Repositories;

use app\models\RawData;
use Yii;

use UseCases\Interfaces\DirectionRepositoryInterface;
use app\models\Direction as DirectionModel;
use Entities\Direction;

class DirectionRepository extends MainRepository implements DirectionRepositoryInterface
{
	protected $model = DirectionModel::class;

	public function create(Direction $data) : array
	{
		$model = new $this->model();
		$model->name = $data->getName();
		$model->project_id = $data->getProjectId();
		$model->is_archive = $data->getIsArchive();

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

	public function update(Direction $data) : bool
	{
		$model = $this->model::findOne(['id' => $data->getId()]);
		$model->name = $data->getName();
		$model->updated_at = date('Y-m-d H:i:s');

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

		if ($model->delete())
		{
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