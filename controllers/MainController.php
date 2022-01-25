<?php

namespace app\controllers;

use app\models\TaskResponsible;
use DI\ContainerBuilder;

use Adapters\Controllers\UserController;
use Adapters\Controllers\ProjectController;
use Adapters\Controllers\DirectionController;
use Adapters\Controllers\TaskController;
use Adapters\Controllers\TaskStatusController;
use Adapters\Controllers\TaskTypeController;
use Adapters\Controllers\TaskResponsibleController;

use yii\web\Controller;
use Yii;

class MainController extends Controller
{
	protected $container;

	protected $accessToken;
	protected $refreshToken;

	protected $userInstance;
	protected $projectInstance;
	protected $directionInstance;
	protected $taskInstance;
	protected $taskStatusInstance;
	protected $taskTypeInstance;
	protected $taskResponsibleInstance;

	public function beforeAction($action)
	{
		parent::beforeAction($action);

		$containerBuilder = new ContainerBuilder;
		$containerBuilder->addDefinitions(PATH_APPLICATION_DEPENDENCIES);
		$this->container = $containerBuilder->build();

		$this->userInstance = $this->container->get(UserController::class);
		$this->projectInstance = $this->container->get(ProjectController::class);
		$this->directionInstance = $this->container->get(DirectionController::class);
		$this->taskInstance = $this->container->get(TaskController::class);
		$this->taskStatusInstance = $this->container->get(TaskStatusController::class);
		$this->taskTypeInstance = $this->container->get(TaskTypeController::class);
		$this->taskResponsibleInstance = $this->container->get(TaskResponsibleController::class);

		$headers = Yii::$app->request->headers;
		$this->accessToken = $headers->get('access-token');
		$this->refreshToken = $headers->get('refresh-token');

		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: POST,GET,PUT,PATCH,DELETE,HEAD,OPTIONS");
		header("Access-Control-Allow-Headers: *");

		return true;
	}
}