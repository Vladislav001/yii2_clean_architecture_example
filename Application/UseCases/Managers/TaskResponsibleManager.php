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
		$data = new TaskResponsible(null, $data['name'], $data['project_id'], $data['sort']);
		return $this->taskResponsibleRepository->create($data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		$data = new TaskResponsible($data['id'], $data['name'], $data['project_id'], $data['sort']);
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

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->taskResponsibleRepository->getList($filter, $sort, $pagination);
	}
}