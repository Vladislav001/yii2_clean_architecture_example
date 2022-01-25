<?php

namespace UseCases\Managers;

use Entities\Direction;
use UseCases\Interfaces\DirectionRepositoryInterface;
use UseCases\Interfaces\TaskRepositoryInterface;
use UseCases\Interfaces\UserRepositoryInterface;

use Yii;

class DirectionManager
{
	private $directionRepository;
	private $taskRepository;

	public function __construct(DirectionRepositoryInterface $directionRepository, TaskRepositoryInterface $taskRepository, UserRepositoryInterface $userRepository)
	{
		$this->directionRepository = $directionRepository;
		$this->taskRepository = $taskRepository;
		$this->userRepository = $userRepository;
	}

	public function create(string $accessToken, array $data) : array
	{
		$data = new Direction(null, $data['project_id'], $data['name']);
		return $this->directionRepository->create($data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		$data = new Direction($data['id'], null, $data['name']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$directionData = $this->directionRepository->find(array('where' => array('id' => $data->getId())))[0];

		if (!$directionData)
		{
			throw new \Exception('direction_not_found');
		}

		// todo потом вынести
		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $directionData['project_id']])
		)
		{
			return $this->directionRepository->update($data);
		}

		return false;
	}

	public function delete(string $accessToken, int $id) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$directionData = $this->directionRepository->find(array('where' => array('id' => $id)))[0];

		if (!$directionData)
		{
			throw new \Exception('direction_not_found');
		}

		// todo потом вынести
		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $directionData['project_id']])
		)
		{
			$transaction = Yii::$app->db->beginTransaction();
			try
			{
				if ($directionData['is_archive'])
				{
					throw new \Exception('direction_forbidden_delete_archive');
				}

				// найти архив для данного проекта
				$directionArchive = $this->directionRepository->find(array(
					"where" => [
						'project_id' => $directionData['project_id'],
						'is_archive' => 1
					],
					"select" => ['id']
				))[0];

				// найти задачи удаляемого direction
				$tasks = $this->taskRepository->find(array(
					"where" => [
						'direction_id' => $directionData['id'],
					],
					"select" => ['id', 'direction_id']
				));

				// переместить задачи в архив проекта
				foreach ($tasks as $key => $value)
				{
					$tasks[$key]['direction_id'] = $directionArchive['id'];
				}

				$this->taskRepository->updateMultiple($tasks);

				// удалить проект
				$resDelete = $this->directionRepository->delete($id);

				$transaction->commit();

				return $resDelete;
			} catch (\Exception $e)
			{
				$transaction->rollBack();
				throw new \Exception($e->getMessage());
			}
		}

		return false;
	}

	public function getList(string $accessToken, int $projectId) : array
	{
		return $this->directionRepository->getList($projectId);
	}
}