<?php

namespace Entities;

class User
{
	private $id;
	private $email;
	private $name;
	private $password;

	public function __construct($id, $email, $name, $password = null)
	{
		$this->id = $id;
		$this->email = $email;
		$this->name = $name;
		$this->password = $password;
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
	public function getEmail()
	{
		return $this->email;
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
	public function getPassword()
	{
		return $this->password;
	}
}