<?php

namespace UseCases\Interfaces;

use Entities\TaskStatus;

interface TaskStatusRepositoryInterface
{
	public function create(TaskStatus $data) : array;

	public function update(TaskStatus $data) : bool;

	public function delete(int $id) : bool;

	public function getList(array $filter = null, array $sort = null, array $pagination = null) : array;
}