<?php

namespace UseCases\Managers;

use Entities\Task;
use UseCases\Interfaces\TaskRepositoryInterface;
use UseCases\Interfaces\TaskTypeRepositoryInterface;
use UseCases\Interfaces\TaskStatusRepositoryInterface;
use UseCases\Interfaces\TaskResponsibleRepositoryInterface;
use UseCases\Interfaces\UserRepositoryInterface;
use UseCases\Interfaces\DirectionRepositoryInterface;

use Yii;

class TaskManager
{
	private $taskRepository;
	private $taskTypeRepository;
	private $taskStatusRepository;
	private $taskResponsibleRepository;
	private $directionRepository;
	private $userRepository;

	public function __construct(TaskRepositoryInterface $taskRepository, TaskTypeRepositoryInterface $taskTypeRepository, TaskStatusRepositoryInterface $taskStatusRepository, TaskResponsibleRepositoryInterface $taskResponsibleRepository, DirectionRepositoryInterface $directionRepository, UserRepositoryInterface $userRepository)
	{
		$this->taskRepository = $taskRepository;
		$this->taskTypeRepository = $taskTypeRepository;
		$this->taskStatusRepository = $taskStatusRepository;
		$this->taskResponsibleRepository = $taskResponsibleRepository;
		$this->directionRepository = $directionRepository;
		$this->userRepository = $userRepository;
	}

	public function getSummaryByDirections(string $accessToken, int $projectID) : array
	{
		$result = array();
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);

		// *раскоментить, когда контроль доступа нужен будет

		$data = $this->taskRepository->getSummaryByDirections($projectID, $currentUserId);

		// получить все названия статусов для данного проекта
		$taskStatusesProject = $this->taskStatusRepository->find(array(
			'where' => array('project_id' => $projectID),
			'index' => array('id')
		));

		foreach ($data as $value)
		{
			if (!isset($result[$value['direction_id']]))
			{
				$taskGroup = array(
					"id" => $value['direction_id'],
					"name" => $value['direction_name'],
					"number" => $value['direction_number'],
					"tasks" => array(
						array(
							"count" => $value['task_count'],
						)
					)
				);

				if ($value['task_status'])
				{
					$taskGroup["tasks"][0]["status"] = array(
						"value" => $value['task_status'],
						"text" => $taskStatusesProject[$value['task_status']]['name'],
					);
				}

				$result[$value['direction_id']] = $taskGroup;
			} else
			{
				$taskGroup = array(
					"count" => $value['task_count']
				);

				if ($value['task_status'])
				{
					$taskGroup["status"] = array(
						"value" => $value['task_status'],
						"text" => $taskStatusesProject[$value['task_status']]['name'],
					);
				}

				$result[$value['direction_id']]["tasks"][] = $taskGroup;
			}
		}

		$result = array_values($result);

		return $result;
	}

	public function getList(string $accessToken, array $params) : array
	{
		$result = array();
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$directionData = $this->directionRepository->find(array('where' => array('id' => $params['where']['direction_id'])))[0];

		if (!$directionData)
		{
			throw new \Exception('direction_not_found');
		}

		// *раскоментить, когда контроль доступа нужен будет
		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $directionData['project_id']])
		)
		{
			$result = $this->taskRepository->getList($params, $currentUserId);

			if ($result)
			{
				$projectID = $directionData['project_id'];

				$taskStatusesProject = $this->taskStatusRepository->find(array(
					'where' => array('project_id' => $projectID),
					'index' => array('id')
				));

				$taskTypesProject = $this->taskTypeRepository->find(array(
					'where' => array('project_id' => $projectID),
					'index' => array('id')
				));

				$taskResponsibleProject = $this->taskResponsibleRepository->find(array(
					'where' => array('project_id' => $projectID),
					'index' => array('id')
				));

				foreach ($result as $key => $value)
				{
					$result[$key]['status'] = array(
						"id" => $taskStatusesProject[$value['status']]['id'],
						"name" => $taskStatusesProject[$value['status']]['name'],
					);

					$result[$key]['type'] = array(
						"id" => $taskTypesProject[$value['type']]['id'],
						"name" => $taskTypesProject[$value['type']]['name'],
					);

					$result[$key]['responsible'] = array(
						"id" => $taskResponsibleProject[$value['responsible']]['id'],
						"name" => $taskResponsibleProject[$value['responsible']]['name'],
					);
				}
			}
		}
		else
		{
			throw new \Exception('forbidden'); // todo коды ошибок
		}

		return $result;
	}

	/**
	 * Получить задачу по ID
	 *
	 * @param string $accessToken
	 * @param int    $id
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getById(string $accessToken, int $id): array
	{
		$result = array();
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskData = $this->taskRepository->find(array('where' => array('id' => $id)))[0];

		// проверим сперва существет ли задача вообще в базе данных
		if (!$taskData)
		{
			throw new \Exception('task_not_found');
		}
		// проверим пользователя и получим задачу
		$result = $this->taskRepository->getById($id, $currentUserId);

		return $result;
	}

	public function create(string $accessToken, array $data) : array
	{
		$data = new Task(null, $data['direction_id'], $data['name'], $data['type'], $data['term_plan'],
			$data['status'], $data['responsible'], $data['link'], $data['link_result'], $data['comment']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$directionData = $this->directionRepository->find(array('where' => array('id' => $data->getDirectionId())))[0];

		if (!$directionData)
		{
			throw new \Exception('direction_not_found');
		}

		// todo оптимизация
		$taskTypeData = $this->taskTypeRepository->find(array('where' => array('id' => $data->getType(), 'project_id' =>  $directionData['project_id'])))[0];
		$taskStatusData = $this->taskStatusRepository->find(array('where' => array('id' => $data->getStatus(), 'project_id' =>  $directionData['project_id'])))[0];
		$taskResponsibleData = $this->taskResponsibleRepository->find(array('where' => array('id' => $data->getResponsible(), 'project_id' =>  $directionData['project_id'])))[0];

		if (!$taskTypeData || !$taskStatusData || !$taskResponsibleData)
		{
			throw new \Exception('invalid_data');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $directionData['project_id']])
		)
		{
			return $this->taskRepository->create($data);
		}
		else
		{
			throw new \Exception('forbidden'); // todo коды ошибок
		}
	}

	public function update(string $accessToken, array $data) : bool
	{
		$data = new Task($data['id'], $data['direction_id'], $data['name'], $data['type'], $data['term_plan'],
			$data['status'], $data['responsible'], $data['link'], $data['link_result'], $data['comment']);
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskData = $this->taskRepository->find(array('where' => array('id' => $data->getId())))[0];

		if (!$taskData)
		{
			throw new \Exception('task_not_found');
		}

		$directionData = $this->directionRepository->find(array('where' => array('id' => $taskData['direction_id'])))[0];

		if (!$directionData)
		{
			throw new \Exception('direction_not_found');
		}

		$taskData['project_id'] = $directionData['project_id'];

		// todo оптимизация
		$taskTypeData = $this->taskTypeRepository->find(array('where' => array('id' => $data->getType(), 'project_id' =>  $directionData['project_id'])))[0];
		$taskStatusData = $this->taskStatusRepository->find(array('where' => array('id' => $data->getStatus(), 'project_id' =>  $directionData['project_id'])))[0];
		$taskResponsibleData = $this->taskResponsibleRepository->find(array('where' => array('id' => $data->getResponsible(), 'project_id' =>  $directionData['project_id'])))[0];

		if (($data->getType() && !$taskTypeData) || ($data->getStatus() && !$taskStatusData)
			|| ($data->getResponsible() && !$taskResponsibleData))
		{
			throw new \Exception('invalid_data');
		}

		// на случай если сменить direction (проверить, что у текущей задачи можем сменить && именно этому направлению задать)
		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $taskData['project_id']])
		 && Yii::$app->authManager->checkAccess(
				$currentUserId,
				$userPermissions['get_project']['key'],
				['project_id' => $directionData['project_id']]))
		{
			return $this->taskRepository->update($data);
		}
		else
		{
			throw new \Exception('forbidden'); // todo коды ошибок
		}
	}

	public function moveToArchive(string $accessToken, int $id) : bool
	{
		$userPermissions = $this->userRepository->getPermissionsInfo();
		$currentUserId = $this->userRepository->findUserIdByAccessToken($accessToken);
		$taskData = $this->taskRepository->find(array(
			'where' => array('id' => $id),
			'select' => ['direction_id']
		))[0];

		if (!$taskData)
		{
			throw new \Exception('task_not_found');
		}

		$directionData = $this->directionRepository->find(array('where' => array('id' => $taskData['direction_id'])))[0];

		if (!$directionData)
		{
			throw new \Exception('direction_not_found');
		}

		if(Yii::$app->authManager->checkAccess(
			$currentUserId,
			$userPermissions['get_project']['key'],
			['project_id' => $directionData['project_id']])
		)
		{
			if ($directionData['is_archive'])
			{
				throw new \Exception('task_already_in_direction_archive');
			} else
			{
				// найти архив для данного проекта
				$directionArchive = $this->directionRepository->find(array(
					"where" => [
						'project_id' => $directionData['project_id'],
						'is_archive' => 1
					],
					"select" => ['id']
				))[0];

				// переместить задачу в архив
				return $this->taskRepository->moveToArchive($id, $directionArchive['id']);
			}
		}
		else
		{
			throw new \Exception('forbidden'); // todo коды ошибок
		}
	}
}