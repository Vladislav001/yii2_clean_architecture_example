<?php

namespace Entities;

class Project
{
	private $id;
	private $creator_id;
	private $name;
	private $logo;
	private $sort;

	public function __construct($id, $creator_id, $name, $logo = null, $sort = 0)
	{
		$this->id = $id;
		$this->creator_id = $creator_id;
		$this->name = $name;
		$this->logo = $logo;
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
	public function getCreatorId()
	{
		return $this->creator_id;
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
	public function getLogo()
	{
		return $this->logo;
	}

	/**
	 * @return int|mixed
	 */
	public function getSort()
	{
		return $this->sort;
	}
}