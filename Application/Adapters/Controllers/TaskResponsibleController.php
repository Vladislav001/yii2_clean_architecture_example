<?php

namespace Adapters\Controllers;

use UseCases\Managers\TaskResponsibleManager;

class TaskResponsibleController
{
	private $taskResponsibleManager;

	public function __construct(TaskResponsibleManager $taskResponsibleManager)
	{
		$this->taskResponsibleManager = $taskResponsibleManager;
	}

	public function create(string $accessToken, array $data) : array
	{
		return $this->taskResponsibleManager->create($accessToken, $data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		return $this->taskResponsibleManager->update($accessToken, $data);
	}

	public function delete(string $accessToken, int $id) : bool
	{
		return $this->taskResponsibleManager->delete($accessToken, $id);
	}

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskResponsibleManager->getList($filter, $sort, $pagination);
	}
}