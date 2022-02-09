<?php

namespace Adapters\Controllers;

use UseCases\Managers\DirectionManager;

class DirectionController
{
	private $directionManager;

	public function __construct(DirectionManager $directionManager)
	{
		$this->directionManager = $directionManager;
	}

	public function create(array $data) : array
	{
		return $this->directionManager->create($data);
	}

	public function update(string $accessToken, array $data) : bool
	{
		return $this->directionManager->update($accessToken, $data);
	}

	public function delete(string $accessToken, int $id) : bool
	{
		return $this->directionManager->delete($accessToken, $id);
	}

	public function getList(string $accessToken, array $filter = null, array $sort = null, array $pagination = null) : array
	{
		return $this->directionManager->getList($accessToken, $filter, $sort, $pagination);
	}
}