<?php

namespace UseCases\Managers;

use Entities\TaskResponsible;
use UseCases\Interfaces\TaskResponsibleRepositoryInterface;
use UseCases\Interfaces\UserRepositoryInterface;
use Yii;

class TaskResponsibleManager
{
	private $taskResponsibleRepository;
	private $userRepository;

	public function __construct(TaskResponsibleRepositoryInterface $taskResponsibleRepository, UserRepositoryInterface $userRepository)
	{
		$this->taskResponsibleRepository = $taskResponsibleRepository;
		$this->userRepository = $userRepository;
	}

	public function create(string $accessToken, array $data) : array
	{
		$data = new TaskResponsible(null, $data['name'], $data['project_id']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $data->getProjectId()])
		)
		{
			return $this->taskResponsibleRepository->create($data);
		}

		return [];
	}

	public function update(string $accessToken, array $data) : bool
	{
		$data = new TaskResponsible($data['id'], $data['name'], $data['project_id']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskResponsibleData = $this->taskResponsibleRepository->find(array('where' => array('id' => $data->getId())))[0];

		if (!$taskResponsibleData)
		{
			throw new \Exception('task_responsible_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskResponsibleData['project_id']])
		)
		{
			return $this->taskResponsibleRepository->update($data);
		}

		return false;
	}

	public function delete(string $accessToken, int $id) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskResponsibleData = $this->taskResponsibleRepository->find(array('where' => array('id' => $id)))[0];

		if (!$taskResponsibleData)
		{
			throw new \Exception('task_responsible_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskResponsibleData['project_id']])
		)
		{
			return $this->taskResponsibleRepository->delete($id);
		}

		return false;
	}

	public function getList(string $accessToken, int $projectId) : array
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $projectId])
		)
		{
			return $this->taskResponsibleRepository->getList($projectId);
		}

		return [];
	}
}