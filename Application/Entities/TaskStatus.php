<?php

namespace Entities;

class TaskStatus
{
	private $id;
	private $name;
	private $project_id;
	private $sort;

	public function __construct($id, $name, $project_id, $sort)
	{
		$this->id = $id;
		$this->name = $name;
		$this->project_id = $project_id;
		$this->sort = $sort;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getProjectId()
	{
		return $this->project_id;
	}

	/**
	 * @return mixed
	 */
	public function getSort()
	{
		return $this->sort;
	}
}