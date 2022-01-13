<?php

namespace Adapters\Controllers;

use Entities\Task;
use UseCases\Managers\TaskManager;

class TaskController
{
	private $taskManager;

	public function __construct(TaskManager $taskManager)
	{
		$this->taskManager = $taskManager;
	}

	public function getSummaryByDirections(string $accessToken, int $projectID) : array
	{
		return $this->taskManager->getSummaryByDirections($accessToken, $projectID);
	}

	public function getList(string $accessToken, $params) : array
	{
		return $this->taskManager->getList($accessToken, $params);
	}

	/**
	 * Получить задачу по ID
	 *
	 * @param string $accessToken
	 * @param int    $id
	 *
	 * @return array
	 */
	public function getById(string $accessToken, int $id) : array
	{
		return $this->taskManager->getById($accessToken, $id);
	}

	public function create(string $accessToken, array $data) : array
	{
		return $this->taskManager->create($accessToken, $data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		return $this->taskManager->update($accessToken, $data);
	}

	public function moveToArchive(string $accessToken, int $id) : bool
	{
		return $this->taskManager->moveToArchive($accessToken, $id);
	}
}