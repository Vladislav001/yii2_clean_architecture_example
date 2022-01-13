<?php

namespace UseCases\Interfaces;

use Entities\TaskResponsible;

interface TaskResponsibleRepositoryInterface
{
	public function create(TaskResponsible $data) : array;

	public function update(TaskResponsible $data) : bool;

	public function delete(int $id) : bool;

	public function getList(int $projectId) : array;
}