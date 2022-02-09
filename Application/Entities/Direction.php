<?php

namespace Entities;

class Direction
{
	private $id;
	private $project_id;
	private $name;
	private $is_archive;
	private $sort;
	private $created_at;
	private $updated_at;

	public function __construct($id, $project_id, $name, $is_archive = null, $sort = 0, $created_at = null, $updated_at = null)
	{
		$this->id = $id;
		$this->project_id = $project_id;
		$this->name = $name;
		$this->is_archive = $is_archive;
		$this->sort = $sort;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
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
	public function getProjectId()
	{
		return $this->project_id;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed|null
	 */
	public function getIsArchive()
	{
		return $this->is_archive;
	}

	/**
	 * @return int|mixed
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @return mixed|null
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}

	/**
	 * @return mixed|null
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}
}