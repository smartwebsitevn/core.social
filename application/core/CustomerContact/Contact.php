<?php namespace Core\CustomerContact;

use Core\Support\AttributesAccess;

class Contact extends AttributesAccess
{
	/**
	 * Doi tuong Factory
	 *
	 * @var Factory
	 */
	protected $factory;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param Factory $factory
	 * @param array   $attributes
	 */
	public function __construct(Factory $factory, array $attributes = [])
	{
		$this->factory = $factory;

		parent::__construct($attributes);
	}

	/**
	 * Update thong tin
	 *
	 * @param array $attributes
	 */
	public function update(array $attributes)
	{
		$this->fill($attributes)->save();
	}

	/**
	 * Luu thong tin
	 */
	public function save()
	{
		$this->factory->setContact($this->attributes);
	}

	/**
	 * Lay doi tuong Factory
	 *
	 * @return Factory
	 */
	public function getFactory()
	{
		return $this->factory;
	}

}