<?php

namespace UseCases\Interfaces;

use Entities\Direction;

interface DirectionRepositoryInterface
{
	public function create(Direction $data) : array;

	public function update(Direction $data) : bool;

	public function delete(int $id) : bool;

	public function getList(int $projectId) : array;
}