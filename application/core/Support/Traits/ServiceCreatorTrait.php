<?php namespace Core\Support\Traits;

trait ServiceCreatorTrait
{
	/**
	 * Danh sach doi tuong service cua cac models
	 *
	 * @var array
	 */
	protected $services = [];


	/**
	 * Tao doi tuong service
	 *
	 * @param string $key
	 * @return mixed
	 */
	abstract protected function createService($key);

	/**
	 * Lay doi tuong service
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function service($key)
	{
		if ( ! isset($this->services[$key]))
		{
			$this->services[$key] = $this->createService($key);
		}

		return $this->services[$key];
	}

}