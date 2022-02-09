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

	public function getSummaryByDirections(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		$taskTableName = $this->model::tableName();
		$directionTableName = DirectionModel::tableName();
		$projectTableName = ProjectModel::tableName();

		$query = $this->model::find();

		$query->select([
				"count($taskTableName.id) as task_count, $taskTableName.status as task_status",
				"$directionTableName.id as direction_id",
				"$directionTableName.name as direction_name",
				"$directionTableName.sort as direction_sort",
			])->from($taskTableName)
			->rightJoin("$directionTableName", "$directionTableName.id = $taskTableName.direction_id")
			->innerJoin("$projectTableName", "$projectTableName.id = $directionTableName.project_id");

		if ($filter['project_id'])
		{
			$query->where(["$projectTableName.id" => $filter['project_id']]);
		}

		$query->groupBy('direction_name, task_status');

		if ($sort['field'] && $sort['type'])
		{
			$query->orderBy(["$directionTableName." . $sort['field'] => mb_strtoupper($sort['type']) == 'DESC' ? SORT_DESC : SORT_ASC]);
		}

		$result = $query->asArray()->all();

		return $result;
	}

	public function getList(int $userId, array $filter = null, array $sort = null, array $pagination = null) : array
	{
		$taskTableName = $this->model::tableName();
		$directionTableName = DirectionModel::tableName();
		$projectTableName = ProjectModel::tableName();

		$query = $this->model::find();

		$query->select("$taskTableName.*")->from($taskTableName)
			->rightJoin("$directionTableName", "$directionTableName.id = $taskTableName.direction_id")
			->innerJoin("$projectTableName", "$projectTableName.id = $directionTableName.project_id");

		if ($filter['direction_id'])
		{
			$query->where(["direction_id" => $filter['direction_id']]);
		}

		if ($sort['field'] && $sort['type'])
		{
			$query->orderBy(["$taskTableName." . $sort['field'] => mb_strtoupper($sort['type']) == 'DESC' ? SORT_DESC : SORT_ASC]);
		}

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