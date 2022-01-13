<?php

namespace UseCases\Managers;

use Entities\Project;
use Entities\Direction;
use UseCases\Interfaces\ProjectRepositoryInterface;
use UseCases\Interfaces\DirectionRepositoryInterface;
use UseCases\Interfaces\UserRepositoryInterface;

use Yii;

class ProjectManager
{
	private $projectRepository;
	private $directionRepository;
	private $userRepository;

	public function __construct(ProjectRepositoryInterface $projectRepository, DirectionRepositoryInterface $directionRepository, UserRepositoryInterface $userRepository)
	{
		$this->projectRepository = $projectRepository;
		$this->directionRepository = $directionRepository;
		$this->userRepository = $userRepository;
	}

	public function create(string $accessToken, array $data) : int
	{
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$project = new Project(null, $currentUserId, $data['name'], $data['logo']);

		$transaction = Yii::$app->db->beginTransaction();
		try
		{
			$newProjectId = $this->projectRepository->create($project);
			$this->projectRepository->update(new Project($newProjectId, null, null, $project->getLogo()));

			// создать direction Архив задач
			$direction= new Direction(null, $newProjectId, DICTIONARY['DIRECTION_ARCHIVE'], 1);
			$newDirectionId = $this->directionRepository->create($direction);

			$transaction->commit();

			return $newProjectId;
		} catch (\Exception $e)
		{
			$transaction->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	public function update($accessToken, array $data) : bool
	{
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$project = new Project($data['id'], $currentUserId, $data['name']);
		$userPermissions = $this->userRepository->getPermissionsInfo();

		// todo на update проекта мб отдельно правило
		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' =>$data['id']])
		)
		{
			return $this->projectRepository->update($project);
		}

		return false;
	}

	// todo проверить используется ли где то, если нет - вычистить
	public function find(array $params) : array
	{
		return $this->projectRepository->find($params);
	}

	public function getList(string $accessToken) : array
	{
		$userRoles = $this->userRepository->getRolesInfo();
		$userId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$isAdmin = Yii::$app->authManager->checkAccess($userId, $userRoles['admin']['key']);

		if ($isAdmin)
		{
			$result = $this->projectRepository->find();
		} else
		{
			$userPermissions = $this->userRepository->getPermissionsInfo();
			$result =  $this->projectRepository->getList($userId, $userPermissions['get_project']['key']);
		}

		foreach ($result as $key => $value)
		{
			$result[$key]['logo'] = $value['logo'] ? Yii::$app->request->hostInfo . PATH_WEB . $value['logo'] : '';
		}

		return $result;
	}

	public function createPermission(string $accessToken, int $projectId, int $userId, string $permission) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['change_rules_project']['key'],
			['project_id' => $projectId])
		)
		{
			$user = $this->userRepository->find(array('where' => array('id' => $userId)))[0];

			if (!$user)
			{
				throw new \Exception('user_not_found');
			}

			return $this->projectRepository->createPermission($userId, $permission, $projectId);
		}

		return false;
	}

	public function deletePermission(string $accessToken, int $projectId, int $userId, string $permission) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['change_rules_project']['key'],
			['project_id' => $projectId])
		)
		{
			$user = $this->userRepository->find(array('where' => array('id' => $userId)))[0];

			if (!$user)
			{
				throw new \Exception('user_not_found');
			}

			return $this->projectRepository->deletePermission($userId, $permission, $projectId);
		}

		return false;
	}
}