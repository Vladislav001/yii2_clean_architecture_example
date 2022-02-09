<?php

namespace Adapters\Repositories;

use UseCases\Interfaces\ProjectRepositoryInterface;
use Entities\Project;

use Yii;

use app\models\Project as ProjectModel;
use app\models\RelationProjectAuthItem as RelationProjectAuthItem;
use app\models\UploadProjectLogoForm;
use yii\web\UploadedFile;

class ProjectRepository extends MainRepository implements ProjectRepositoryInterface
{
	protected $model = ProjectModel::class;
	protected $relationProjectAuthItemModel = RelationProjectAuthItem::class;

	public function create(Project $project) : int
	{
		$model = new $this->model;
		$model->name = $project->getName();
		$model->creator_id = $project->getCreatorId();
		$model->sort = $project->getSort();

		if ($model->validate())
		{
			$model->save();
			return $model->getPrimaryKey();
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}
	}

	public function update(Project $project) : bool
	{
		$id = $project->getId();
		$model = $this->model::findOne($id);
		$model->name = $project->getName() ?? $model->name;
		$model->logo = $project->getLogo() ?? $model->logo;
		$model->sort = $project->getSort() ?? $model->sort;

		if ($model->logo)
		{
			$modelLogo = new UploadProjectLogoForm();
			$modelLogo->projectId = $id;
			$modelLogo->deleteAllFiles();

			$modelLogo->file = UploadedFile::getInstanceByName('logo');
			$model->logo = $modelLogo->upload();
		}

		if ($model->validate())
		{
			$model->update();
			return true;
		} else
		{
			throw new \Exception(current($model->errors)[0]);
		}
	}

	public function getList(int $userId, string $permission, array $filter = null, array $sort = null, array $pagination = null) : array
	{
		$projectTableName = $this->model::tableName();
		$relationProjectAuthItemModel = $this->relationProjectAuthItemModel::tableName();

		$query = $this->model::find();

		$query->select([
			"$projectTableName.id",
			"$projectTableName.creator_id",
			"$projectTableName.name",
			"$projectTableName.logo",
			"$projectTableName.sort"
		])->from($projectTableName)
			->leftJoin("$relationProjectAuthItemModel", "$relationProjectAuthItemModel.project_id = $projectTableName.id")
			->where(["$projectTableName.creator_id" => $userId])
			->orWhere([
				"$relationProjectAuthItemModel.user_id" => $userId,
				"$relationProjectAuthItemModel.name" => $permission
			]);

		if ($sort['field'] && $sort['type'])
		{
			$query->orderBy(["$projectTableName." . $sort['field'] => mb_strtoupper($sort['type']) == 'DESC' ? SORT_DESC : SORT_ASC]);
		}

		// logToFile($query->createCommand()->rawSql, 'raw.log');

		$result = $query->asArray()->all();

		return $result;
	}

	public function createPermission(int $userId, string $permission, int $projectId) : bool
	{
		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			$relationProjectAuthItemModel = new $this->relationProjectAuthItemModel;
			$relationProjectAuthItemModel->project_id = $projectId;
			$relationProjectAuthItemModel->name = $permission;
			$relationProjectAuthItemModel->user_id = $userId;

			if (!$relationProjectAuthItemModel->save())
			{
				throw new \Exception(current($relationProjectAuthItemModel->errors)[0]);
			}

			$transaction->commit();
			return true;
		} catch (\Exception $e)
		{
			$transaction->rollBack();
			throw new \Exception('create_permission');
		}
	}

	public function deletePermission(int $userId, string $permission, int $projectId) : bool
	{
		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			$relationProjectAuthItemModel = new $this->relationProjectAuthItemModel;
			$relationProjectAuthItemModel = $relationProjectAuthItemModel::findOne([
				'project_id' => $projectId,
				'name' => $permission,
				'user_id' => $userId
			]);

			if (!$relationProjectAuthItemModel->delete())
			{
				throw new \Exception(current($relationProjectAuthItemModel->errors)[0]);
			}

			$transaction->commit();
			return true;
		} catch (\Exception $e)
		{
			$transaction->rollBack();
			throw new \Exception('delete_permission');
		}
	}

	// *если делать удаление, то также удалять логотип, направления и задачи.
//	public function delete()
//	{
//
//	}

	// todo
//	public function deleteLogo()
//	{
//
//	}
}