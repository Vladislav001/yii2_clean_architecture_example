<?php

namespace Entities;

class Task
{
	private $id;
	private $direction_id;
	private $name;
	private $type;
	private $term_plan;
	private $status;
	private $responsible;
	private $link;
	private $link_result;
	private $comment;
	private $number;
	private $created_at;
	private $updated_at;

	public function __construct($id, $direction_id, $name, $type, $term_plan, $status, $responsible, $link = null, $link_result = null, $comment = null, $number = null, $created_at = null, $updated_at = null)
	{
		$this->id = $id;
		$this->direction_id = $direction_id;
		$this->name = $name;
		$this->type = $type;
		$this->term_plan = $term_plan;
		$this->status = $status;
		$this->responsible = $responsible;
		$this->link = $link;
		$this->link_result = $link_result;
		$this->comment = $comment;
		$this->number = $number;
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
	public function getDirectionId()
	{
		return $this->direction_id;
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
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getTermPlan()
	{
		return $this->term_plan;
	}

	/**
	 * @return mixed
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return mixed
	 */
	public function getResponsible()
	{
		return $this->responsible;
	}

	/**
	 * @return mixed|null
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @return mixed|null
	 */
	public function getLinkResult()
	{
		return $this->link_result;
	}

	/**
	 * @return mixed|null
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 * @return mixed|null
	 */
	public function getNumber()
	{
		return $this->number;
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