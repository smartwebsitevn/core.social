<?php namespace App\LogActivity\Library;

class ActivityOwner
{
	/**
	 * Owner type
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Owner key
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Owner attributes
	 *
	 * @var array
	 */
	protected $attributes = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param string $type
	 * @param string $key
	 * @param array  $attributes
	 */
	public function __construct($type, $key = null, array $attributes = [])
	{
		$this->type = $type;

		$this->key = $key;

		$this->attributes = $attributes;
	}

	/**
	 * Get owner type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get owner key
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Get owner attributes
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

}