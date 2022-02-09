<?php

namespace UseCases\Managers;

use Entities\TaskType;
use UseCases\Interfaces\TaskTypeRepositoryInterface;
use UseCases\Interfaces\UserRepositoryInterface;

use Yii;

class TaskTypeManager
{
	private $taskTypeRepository;
	private $userRepository;

	public function __construct(TaskTypeRepositoryInterface $taskTypeRepository, UserRepositoryInterface $userRepository)
	{
		$this->taskTypeRepository = $taskTypeRepository;
		$this->userRepository = $userRepository;
	}

	public function create(string $accessToken, array $data) : array
	{
		$data = new TaskType(null, $data['name'], $data['project_id'], $data['sort']);
		return $this->taskTypeRepository->create($data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		$data = new TaskType($data['id'], $data['name'], null, $data['sort']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskTypeData = $this->taskTypeRepository->find(array('where' => array('id' => $data->getId())))[0];

		if (!$taskTypeData)
		{
			throw new \Exception('task_type_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskTypeData['project_id']])
		)
		{
			return $this->taskTypeRepository->update($data);
		}

		return false;
	}

	public function delete(string $accessToken, int $id) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskTypeData = $this->taskTypeRepository->find(array('where' => array('id' => $id)))[0];

		if (!$taskTypeData)
		{
			throw new \Exception('task_type_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskTypeData['project_id']])
		)
		{
			return $this->taskTypeRepository->delete($id);
		}

		return false;
	}

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskTypeRepository->getList($filter, $sort, $pagination);
	}
}