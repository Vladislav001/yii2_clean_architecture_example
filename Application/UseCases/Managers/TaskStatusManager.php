<?php

namespace UseCases\Managers;

use Entities\TaskStatus;
use UseCases\Interfaces\TaskStatusRepositoryInterface;
use UseCases\Interfaces\UserRepositoryInterface;

use Yii;

class TaskStatusManager
{
	private $taskStatusRepository;
	private $userRepository;

	public function __construct(TaskStatusRepositoryInterface $taskStatusRepository, UserRepositoryInterface $userRepository)
	{
		$this->taskStatusRepository = $taskStatusRepository;
		$this->userRepository = $userRepository;
	}

	public function create(string $accessToken, array $data) : array
	{
		$data = new TaskStatus(null, $data['name'], $data['project_id'], $data['sort']);
		return $this->taskStatusRepository->create($data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		$data = new TaskStatus($data['id'], $data['name'], null, $data['sort']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskStatusData = $this->taskStatusRepository->find(array('where' => array('id' => $data->getId())))[0];

		if (!$taskStatusData)
		{
			throw new \Exception('task_status_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskStatusData['project_id']])
		)
		{
			return $this->taskStatusRepository->update($data);
		}

		return false;
	}

	public function delete(string $accessToken, int $id) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskStatusData = $this->taskStatusRepository->find(array('where' => array('id' => $id)))[0];

		if (!$taskStatusData)
		{
			throw new \Exception('task_status_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskStatusData['project_id']])
		)
		{
			return $this->taskStatusRepository->delete($id);
		}

		return false;
	}

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskStatusRepository->getList($filter, $sort, $pagination);
	}
}