<?php

namespace Adapters\Repositories;

use UseCases\Interfaces\TaskRepositoryInterface;
use Entities\Task;

use app\models\Task as TaskModel;

use app\models\Direction as DirectionModel; // todo подумать можно ли вынести
use app\models\Project as ProjectModel;// todo подумать можно ли вынести
use app\helpers\DataHelpers;

class TaskRepository extends MainRepository implements TaskRepositoryInterface
{
	protected $model = TaskModel::class;

	public function getSummaryByDirections(int $projectID, int $userId = 0) : array
	{
		$taskTableName = $this->model::tableName();
		$directionTableName = DirectionModel::tableName();
		$projectTableName = ProjectModel::tableName();

		$query = $this->model::find();

		$query->select([
				"count($taskTableName.id) as task_count, $taskTableName.status as task_status",
				"$directionTableName.id as direction_id",
				"$directionTableName.name as direction_name",
				"$directionTableName.number as direction_number",
			])->from($taskTableName)
			->rightJoin("$directionTableName", "$directionTableName.id = $taskTableName.direction_id")
			->innerJoin("$projectTableName", "$projectTableName.id = $directionTableName.project_id")
			->where(["$projectTableName.id" => $projectID])
			->groupBy('direction_name, task_status')
			->all();

		$result = $query->asArray()->all();

		return $result;
	}

	public function getList(array $params, int $userId) : array
	{
		$taskTableName = $this->model::tableName();
		$directionTableName = DirectionModel::tableName();
		$projectTableName = ProjectModel::tableName();

		$query = $this->model::find();

		$query->select("$taskTableName.*")->from($taskTableName)
			->rightJoin("$directionTableName", "$directionTableName.id = $taskTableName.direction_id")
			->innerJoin("$projectTableName", "$projectTableName.id = $directionTableName.project_id")
			->where($params['where'])
			->orderBy($this->getOrderBy($params['orderBy']));

		$result = $query->asArray()->all();

		return $result;
	}

	/**
	 * Получить задачу по ID
	 *
	 * @param int $id
	 * @param int $userId
	 *
	 * @return array
	 */
	public function getById(int $id, int $userId) : array
	{

		$query = $this->model::find()
			->where(['id' => $id]);
		$result = $query->asArray()->one();

		return $result;
	}

	public function create(Task $data) : array
	{
		$model = new $this->model();
		$model->direction_id = $data->getDirectionId();
		$model->name = $data->getName();
		$model->type = $data->getType();
		$model->term_plan = $data->getTermPlan();
		$model->status = $data->getStatus();
		$model->responsible = $data->getResponsible();
		$model->link = $data->getLink();
		$model->link_result = $data->getLinkResult();
		$model->comment =$data->getComment();

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

	public function update(Task $data) : bool
	{
		$model = $this->model::findOne(['id' => $data->getId()]);
		$model->updated_at = date('Y-m-d H:i:s');
		$model->direction_id = $data->getDirectionId() ?? $model->direction_id;
		$model->name = $data->getName() ?? $model->name;
		$model->type = $data->getType() ?? $model->type;
		$model->term_plan = $data->getTermPlan() ?? $model->term_plan;
		$model->status = $data->getStatus() ?? $model->status;
		$model->responsible = $data->getResponsible() ?? $model->responsible;
		$model->link = $data->getLink() ?? $model->link;
		$model->link_result = $data->getLinkResult() ?? $model->link_result;
		$model->comment = $data->getComment() ?? $model->comment;

		if ($model->validate())
		{
			$model->update();
			return true;
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}
	}

	public function moveToArchive(int $id, int $directionArchiveId) : bool
	{
		$model = $this->model::findOne(['id' => $id]);
		$model->direction_id = $directionArchiveId;

		if ($model->validate())
		{
			$model->update();
			return true;
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}
	}
}