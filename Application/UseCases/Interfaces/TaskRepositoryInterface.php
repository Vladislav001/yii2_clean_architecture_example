<?php

namespace UseCases\Interfaces;

use Entities\Task;

interface TaskRepositoryInterface
{
	public function deleteAll(array $params) : int;

	public function getSummaryByDirections(int $projectID, int $userId): array;

	public function getList(array $params, int $userId): array;

	public function getById(int $id, int $userId): array;

	public function create(Task $data) : array;

	public function update(Task $data) : bool;

	public function moveToArchive(int $id, int $directionArchiveId) : bool;
}