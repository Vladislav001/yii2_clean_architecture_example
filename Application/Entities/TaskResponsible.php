<?php

namespace Entities;

class TaskResponsible
{
	private $id;
	private $name;
	private $project_id;

	public function __construct($id, $name, $project_id)
	{
		$this->id = $id;
		$this->name = $name;
		$this->project_id = $project_id;
	}

	/**
	 * @return int
	 */
	public function getId() : string
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getProjectId() : int
	{
		return $this->project_id;
	}
}