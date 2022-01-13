<?php

use UseCases\Interfaces\UserRepositoryInterface;
use Adapters\Repositories\UserRepository;

use UseCases\Interfaces\ProjectRepositoryInterface;
use Adapters\Repositories\ProjectRepository;
use UseCases\Interfaces\DirectionRepositoryInterface;
use Adapters\Repositories\DirectionRepository;
use UseCases\Interfaces\TaskRepositoryInterface;
use Adapters\Repositories\TaskRepository;

use UseCases\Interfaces\TaskStatusRepositoryInterface;
use Adapters\Repositories\TaskStatusRepository;
use UseCases\Interfaces\TaskTypeRepositoryInterface;
use Adapters\Repositories\TaskTypeRepository;
use UseCases\Interfaces\TaskResponsibleRepositoryInterface;
use Adapters\Repositories\TaskResponsibleRepository;

return [
	UserRepositoryInterface::class => DI\create(UserRepository::class),
	ProjectRepositoryInterface::class => DI\create(ProjectRepository::class),
	DirectionRepositoryInterface::class => DI\create(DirectionRepository::class),
	TaskRepositoryInterface::class => DI\create(TaskRepository::class),
	TaskStatusRepositoryInterface::class => DI\create(TaskStatusRepository::class),
	TaskTypeRepositoryInterface::class => DI\create(TaskTypeRepository::class),
	TaskResponsibleRepositoryInterface::class => DI\create(TaskResponsibleRepository::class),
];