<?php

namespace Adapters\Repositories;

use app\helpers\DataHelpers;

class MainRepository
{
	protected $model;

	public function find(array $params = array()) : array
	{
		$query = $this->model::find()->asArray()->where($params['where']);
		if (!empty($params['joins']))
		{
			$query->joinWith($params['joins'], $params['eagerLoading']);
		}
		if (!empty($params['select']))
		{
			$query->select($params['select']);
		}
		if (!empty($params['group']))
		{
			$query->groupBy($params['group']);
		}
		if (!empty($params['index']))
		{
			$query->indexBy($params['index']);
		}

		if (!empty($params['orderBy']))
		{
			$query->orderBy($this->getOrderBy($params['orderBy']));
		}

	//	logToFile($query->createCommand()->rawSql, 'raw.log');

		return $query->asArray()->all();
	}

	public function deleteAll(array $params) : int
	{
		$model = new $this->model();
		$countDelete = $model->deleteAll($params);

		return $countDelete;
	}

	public function getOrderBy(array $orderBy)
	{
		foreach ($orderBy as $key => $value)
		{
			if (!$value)
			{
				$value = SORT_ASC;
			}

			$orderBy[$key] = mb_strtoupper($value) == 'DESC' ? SORT_DESC : SORT_ASC;
		}

		return $orderBy;
	}

	public function addMultiple(array $data) : int
	{
		$result = DataHelpers::batchInsert($data, $this->model::tableName(), 1);

		if (!$result)
		{
			$result = 0;
		}

		return $result;
	}

	public function updateMultiple(array $data) : int
	{
		$result = DataHelpers::batchUpdate($data, $this->model::tableName(), 1);

		if (!$result)
		{
			$result = 0;
		}

		return $result;
	}
}