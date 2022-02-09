<?php

namespace Adapters\Controllers;

use UseCases\Managers\TaskStatusManager;

class TaskStatusController
{
	private $taskStatusManager;

	public function __construct(TaskStatusManager $taskStatusManager)
	{
		$this->taskStatusManager = $taskStatusManager;
	}

	public function create(string $accessToken, array $data) : array
	{
		return $this->taskStatusManager->create($accessToken, $data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		return $this->taskStatusManager->update($accessToken, $data);
	}

	public function delete(string $accessToken, int $id) : bool
	{
		return $this->taskStatusManager->delete($accessToken, $id);
	}

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskStatusManager->getList($filter, $sort, $pagination);
	}
}