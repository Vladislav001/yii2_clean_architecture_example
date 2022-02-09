<?php

namespace Adapters\Controllers;

use UseCases\Managers\TaskTypeManager;

class TaskTypeController
{
	private $taskTypeManager;

	public function __construct(TaskTypeManager $taskTypeManager)
	{
		$this->taskTypeManager = $taskTypeManager;
	}

	public function create(string $accessToken, array $data) : array
	{
		return $this->taskTypeManager->create($accessToken, $data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		return $this->taskTypeManager->update($accessToken, $data);
	}

	public function delete(string $accessToken, int $id) : bool
	{
		return $this->taskTypeManager->delete($accessToken, $id);
	}

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskTypeManager->getList($filter, $sort, $pagination);
	}
}