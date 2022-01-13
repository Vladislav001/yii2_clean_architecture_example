<?php

namespace UseCases\Interfaces;

use Entities\TaskType;

interface TaskTypeRepositoryInterface
{
	public function create(TaskType $data) : array;

	public function update(TaskType $data) : bool;

	public function delete(int $id) : bool;

	public function getList(int $projectId) : array;
}