<?php
// ~путь до файла cron /backend-ren-dashboard/public_html/yii dashboard/import
namespace app\commands\dashboard;

if (!$_SERVER['DOCUMENT_ROOT'])
{
	$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(dirname(__FILE__)));
}

include_once $_SERVER['DOCUMENT_ROOT'] . "/global_functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . '/constants.php';


use yii\console\Controller;
use yii\console\ExitCode;
use Google\Client;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use DI\ContainerBuilder;
use Adapters\Controllers\ProjectController;
use Adapters\Repositories\DirectionRepository;
use Adapters\Repositories\TaskRepository;
use Adapters\Repositories\TaskStatusRepository;
use Adapters\Repositories\TaskTypeRepository;
use Adapters\Repositories\TaskResponsibleRepository;

class ImportController extends Controller
{
	const GOOGLE_DRIVE_FILE_ID = "1J_bYpOJBGn-MAZ7QU7Mfx57gxdLCYe7H";
	const FILE_NAME = "data.xlsx";
	const PROJECT_ID = 12;

	private $ProjectController; // TODO del

	private $DirectionRepository;
	private $TaskRepository;
	private $TaskStatusRepository;
	private $TaskTypeRepository;
	private $TaskResponsibleRepository;

	/**
	 * Импорт информации о проекте, направлениях, задачах в БД из гугл excel
	 */
	public function actionIndex()
	{
		// 1. Скачать файл
		$this->loadFileFromGoogleDrive();

		// 2. Обработать данные в нужный вид
		$excelData = $this->getExcelData();

		// todo пока это единижды задано
		// 3. Создать/обновить проект
//		$accessToken = '';
//		$newProjectId = $this->ProjectController->create($accessToken, array(
//			'creator_id' => 22,
//			'name' => 'test'
//		));
//		$res = $this->ProjectController->update($accessToken, array(
//			'id' => 11,
//			'creator_id' => 1,
//			'name' => 'test33'
//		));

		// 4. Создать направления проекта (не обновляем, т.к только имя есть и все - а на имени завязка)
		$directionDataFromExcel = $this->getDirectionDataFromExcel($excelData['directions']);

		// 4.1 Найти существующие направления по названию
		$existDirections = $this->getExistDirections($directionDataFromExcel);

		$createDirections = array();

		foreach ($directionDataFromExcel as $directionData)
		{
			$exist = false;

			foreach ($existDirections as $existDirection)
			{
				if ($existDirection['name'] == $directionData['name'])
				{
					$exist = true;
					break;
				}
			}

			if (!$exist)
			{
				$createDirections[] = array(
					'name' => $directionData['name'],
					'number' => $directionData['number'],
					'project_id' => self::PROJECT_ID,
					'is_archive' => $directionData['is_archive'] ?? null,
				);
			}
		}

		// 4.2 Создать новые направления
		if ($createDirections)
		{
			$this->DirectionRepository->addMultiple($createDirections);
		}

		// 5.1 Создать типы,статусы,ответственных для проекта (по задачам) при необходимости
		$this->createTaskStatusesAndTypesAndResponsibles($excelData);
		$taskStatusesAndTypesAndResponsibles = $this->getTaskStatusesAndTypesAndResponsibles();

		// 6. Создать/обновить задачи направления
		// 6.1 Получить задачи из excel
		$tasksByDirectionFromExcel = $this->getTasksByDirectionFromExcel($excelData['directions']);

		// 6.2 Найти существующие направления по названию
		$existDirections = $this->getExistDirections($directionDataFromExcel);

		// 6.3 раскидать задачи по направлениям проекта из БД
		$findTasks = array();
		$findTaskNames = array();
		$findTaskDirectionIDs = array();

		foreach ($existDirections as $existDirection)
		{
			$tasksByDirection = $tasksByDirectionFromExcel[$existDirection['name']];

			foreach ($tasksByDirection as $taskByDirection)
			{
				$findTasks[] = array(
					"name" => $taskByDirection['name'],
					"direction_id" => $existDirection['id']
				);
				$findTaskNames[$taskByDirection['name']] = $taskByDirection['name'];
				$findTaskDirectionIDs[$existDirection['id']] = $existDirection['id'];
			}
		}

		// 6.4 Найти существующие задачи по названию и направлению
		$existDirectionIDs = array();
		$existTasks = $this->TaskRepository->find(array(
			'where' => array(
				'name' => $findTaskNames,
				'direction_id' => $findTaskDirectionIDs,
			)
		));

		foreach ($excelData['directions'] as $directionData)
		{
			$directionID = $this->getDirectionIdByName($existDirections, $directionData['name']);
			$existDirectionIDs[$directionID] = $directionID;

			foreach ($directionData['tasks'] as $directionTask)
			{
				$directionTask['id'] = false;
				$directionTask['direction_id'] = $directionID;

				// 6.5 У задач проставить связи с типами, статусами, ответственными
				$directionTask['type'] = $taskStatusesAndTypesAndResponsibles['task_types'][$directionTask['type']]['id'];
				$directionTask['status'] = $taskStatusesAndTypesAndResponsibles['task_statuses'][$directionTask['status']]['id'];
				$directionTask['responsible'] = $taskStatusesAndTypesAndResponsibles['task_responsibles'][$directionTask['responsible']]['id'];

				foreach ($existTasks as $existTask)
				{
					if ($existTask['number'] == $directionTask['number'] && $existTask['name'] == $directionTask['name'] && $existTask['direction_id'] == $directionID)
					{
						$directionTask['id'] = $existTask['id'];
						break;
					}
				}

				if (!$directionTask['id'])
				{
					$createTasks[] = $directionTask;
				} else
				{
					$updateTasks[] = $directionTask;
				}
			}
		}

		// 6.6 Обновить существующие задачи
		if ($updateTasks)
		{
			// 6.6.1 Удалить из БД задачи, которых нет в excel (но есть у нас в БД)
			$existTaskIDs = array();
			foreach ($updateTasks as $value)
			{
				$existTaskIDs[] = $value['id'];
			}
			$countDelete = $this->TaskRepository->deleteAll(['AND', ['in', 'direction_id', $existDirectionIDs], ['not in', 'id', $existTaskIDs]]);

			// 6.6.2 Обновить
			$this->TaskRepository->updateMultiple($updateTasks);
		}

		// 6.7 Создать новые задачи
		if ($createTasks)
		{
			$this->TaskRepository->addMultiple($createTasks);
		}

		return ExitCode::OK;
	}

	protected function loadFileFromGoogleDrive()
	{
		$fileId = self::GOOGLE_DRIVE_FILE_ID;

		$googleClient = new Client();
		putenv('GOOGLE_APPLICATION_CREDENTIALS=' . dirname(__FILE__) . '/credentials.json');
		$googleClient->addScope(\Google_Service_Drive::DRIVE_READONLY);
		$googleClient->useApplicationDefaultCredentials();

		$service = new \Google_Service_Drive($googleClient);

		try
		{
			$content = $service->files->get($fileId, array(
				'alt' => 'media'
			));
			file_put_contents(dirname(__FILE__) . '/' . self::FILE_NAME, $content->getBody()->getContents());

		} catch (Exception $e)
		{
			//echo 'Ошибка: ',  $e->getMessage(), "\n";
			echo json_encode(array("error" => 1));
			exit();
		}
	}

	protected function getExcelData()
	{
		$result = array();

		$reader = new Xlsx();
		$spreadsheet = $reader->load(dirname(__FILE__) . '/' . self::FILE_NAME);
		$countPages = $spreadsheet->getSheetCount(); // 1 страница - общая
		//$countPages = $countPages - 1; // последняя страница - Архив

		$taskTypes = array();
		$taskStatuses = array();
		$taskResponsibles = array();

		for ($i = 1; $i < $countPages; $i++)
		{
			$sheetData = $spreadsheet->getSheet($i)->toArray();
			$directionTasks = array();

			if (!$sheetData[0][0])
			{
				continue;
			}

			foreach ($sheetData as $data)
			{
				// однозначное идентифицировать строчку как задача
				if ($data[0] && $data[1] && $data[8] && $data[12] && $data[14] && $data[0] != '№')
				{
					$directionTasks[] = array(
						"number" => $data[0],
						"name" => $data[1],
						"type" => $data[8],
						"term_plan" => $data[10],
						"status" => $data[12],
						"responsible" => $data[14],
						"link_result" => $data[16],
						"comment" => $data[18],
					);

					$taskTypes[$data[8]] = $data[8];
					$taskStatuses[$data[12]] = $data[12];

					if ($data[14])
					{
						$taskResponsibles[$data[14]] = $data[14];
					}
				}
			}

			$direction = array(
				"name" => $sheetData[0][0],
				"number" => $i,
				"tasks" => $directionTasks
			);

			if ($direction['name'] == 'Archive')
			{
				$direction['is_archive'] = 1;
				$direction['name'] = DICTIONARY['DIRECTION_ARCHIVE'];
			}

			$result["directions"][] =$direction;
			$result['types'] = $taskTypes;
			$result['statuses'] = $taskStatuses;
			$result['responsibles'] = $taskResponsibles;
		}

		return $result;
	}

	protected function findValueByName($data, $name)
	{
		foreach ($data as $value)
		{
			if ($value['name'] == $name)
			{
				return $value['value'];
			}
		}

		return false;
	}

	protected function getDirectionDataFromExcel($data)
	{
		$result = $data;

		foreach ($result as $key => $value)
		{
			$result[$value['name']] = $value;
			unset($result[$key]);
		}

		return $result;
	}

	protected function getTasksByDirectionFromExcel($data)
	{
		$result = array();

		foreach ($data as $value)
		{
			$result[$value['name']] = $value['tasks'];
		}

		return $result;
	}

	protected function getDirectionIdByName($data, $name)
	{
		$result = 0;

		foreach ($data as $value)
		{
			if ($value['name'] == $name)
			{
				$result = $value['id'];
				break;
			}
		}

		return $result;
	}

	public function getExistDirections($directionDataFromExcel)
	{
		return $this->DirectionRepository->find(array(
			'name' => array_keys($directionDataFromExcel),
			'project_id' => self::PROJECT_ID,
		));
	}

	protected function getTaskStatusesAndTypesAndResponsibles()
	{
		$result['task_statuses'] = $this->TaskStatusRepository->find(array(
			'where' => array(
				'project_id' => self::PROJECT_ID,
			),
			'index' => array('name'),
		));

		$result['task_types'] = $this->TaskTypeRepository->find(array(
			'where' => array(
				'project_id' => self::PROJECT_ID,
			),
			'index' => array('name'),
		));

		$result['task_responsibles'] = $this->TaskResponsibleRepository->find(array(
			'where' => array(
				'project_id' => self::PROJECT_ID,
			),
			'index' => array('name'),
		));

		return $result;
	}

	protected function createTaskStatusesAndTypesAndResponsibles($excelData)
	{
		$taskStatusesAndTypesAndResponsibles = $this->getTaskStatusesAndTypesAndResponsibles();

		$createTaskStatuses = array();
		$existTaskStatuses = $taskStatusesAndTypesAndResponsibles['task_statuses'];

		foreach ($excelData['statuses'] as $status)
		{
			$exist = false;

			foreach ($existTaskStatuses as $existStatus)
			{
				if ($existStatus['name'] == $status)
				{
					$exist = true;
					break;
				}
			}

			if (!$exist)
			{
				$createTaskStatuses[] = array(
					'name' => $status,
					'project_id' => self::PROJECT_ID,
				);
			}
		}

		if ($createTaskStatuses)
		{
			$this->TaskStatusRepository->addMultiple($createTaskStatuses);
		}

		$createTaskTypes = array();
		$existTaskTypes = $taskStatusesAndTypesAndResponsibles['task_types'];

		foreach ($excelData['types'] as $type)
		{
			$exist = false;

			foreach ($existTaskTypes as $existType)
			{
				if ($existType['name'] == $type)
				{
					$exist = true;
					break;
				}
			}

			if (!$exist)
			{
				$createTaskTypes[] = array(
					'name' => $type,
					'project_id' => self::PROJECT_ID,
				);
			}
		}

		if ($createTaskTypes)
		{
			$this->TaskTypeRepository->addMultiple($createTaskTypes);
		}

		$createTaskResponsibles = array();
		$existTaskResponsibles = $taskStatusesAndTypesAndResponsibles['task_responsibles'];

		foreach ($excelData['responsibles'] as $responsible)
		{
			$exist = false;

			foreach ($existTaskResponsibles as $existResponsible)
			{
				if ($existResponsible['name'] == $responsible)
				{
					$exist = true;
					break;
				}
			}

			if (!$exist)
			{
				$createTaskResponsibles[] = array(
					'name' => $responsible,
					'project_id' => self::PROJECT_ID,
				);
			}
		}

		if ($createTaskResponsibles)
		{
			$this->TaskResponsibleRepository->addMultiple($createTaskResponsibles);
		}
	}

	/**
	 * Типо __construct
	 * @param $action
	 * @return mixed
	 */
	public function beforeAction($action)
	{
		$containerBuilder = new ContainerBuilder;
		$containerBuilder->addDefinitions(PATH_APPLICATION_DEPENDENCIES);
		$container = $containerBuilder->build();

		$this->ProjectController = $container->get(ProjectController::class);
		$this->DirectionRepository = $container->get(DirectionRepository::class);
		$this->TaskRepository = $container->get(TaskRepository::class);
		$this->TaskStatusRepository = $container->get(TaskStatusRepository::class);
		$this->TaskTypeRepository = $container->get(TaskTypeRepository::class);
		$this->TaskResponsibleRepository = $container->get(TaskResponsibleRepository::class);

		return parent::beforeAction($action);
	}
}