<?php

namespace UseCases\Interfaces;

use Entities\Task;

interface TaskRepositoryInterface
{
	public function deleteAll(array $params) : int;

	public function getSummaryByDirections(array $filter = null, array $sort = null, array $pagination = null): array;

	public function getList(int $userId, array $filter = null, array $sort = null, array $pagination = null): array;

	public function getById(int $id, int $userId): array;

	public function create(Task $data) : array;

	public function update(Task $data) : bool;

	public function moveToArchive(int $id, int $directionArchiveId) : bool;
}