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

	public function getSummaryByDirections(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskManager->getSummaryByDirections($filter, $sort, $pagination);
	}

	public function getList(string $accessToken, array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskManager->getList($accessToken, $filter, $sort, $pagination);
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