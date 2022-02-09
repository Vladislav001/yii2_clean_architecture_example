<?php

namespace Adapters\Controllers;

use Entities\Project;
use UseCases\Managers\ProjectManager;

class ProjectController
{
	private $projectManager;

	public function __construct(ProjectManager $projectManager)
	{
		$this->projectManager = $projectManager;
	}

	public function create(string $accessToken, array $data) : int
	{
		return $this->projectManager->create($accessToken, $data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		return $this->projectManager->update($accessToken, $data);
	}

	public function getList(string $accessToken, array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->projectManager->getList($accessToken, $filter, $sort, $pagination);
	}

	public function createPermission(string $accessToken, int $projectId, int $userId, string $permission) : bool
	{
		return $this->projectManager->createPermission($accessToken, $projectId, $userId, $permission);
	}

	public function deletePermission(string $accessToken, int $projectId, int $userId, string $permission) : bool
	{
		return $this->projectManager->deletePermission($accessToken, $projectId, $userId, $permission);
	}
}