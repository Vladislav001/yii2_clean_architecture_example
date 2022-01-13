<?php

namespace UseCases\Interfaces;

use Entities\Project;

interface ProjectRepositoryInterface
{
	public function create(Project $project) : int;

	public function update(Project $project) : bool;

	public function find(array $params = array()) : array;

	public function getList(int $userId, string $permission) : array;
}