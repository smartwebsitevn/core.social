<?php namespace Core\Support;

use InvalidArgumentException;
use TF\Support\Collection;
use Core\Base\Model as BaseModel;

abstract class DriverModelServiceableManager
{
	/**
	 * Danh sach models
	 *
	 * @var Collection
	 */
	protected $models;

	/**
	 * Danh sach doi tuong service cua cac models
	 *
	 * @var array
	 */
	protected $services = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $models
	 */
	public function __construct(array $models)
	{
		$this->models = collect($models);
	}

	/**
	 * Thuc hien tao doi tuong service
	 *
	 * @param BaseModel $model
	 * @return mixed
	 */
	abstract protected function makeServiceInstance(BaseModel $model);

	/**
	 * Lay danh sach models
	 *
	 * @return Collection
	 */
	public function lists()
	{
		return $this->models;
	}

	/**
	 * Lay thong tin model
	 *
	 * @param string $key
	 * @return BaseModel|null
	 */
	public function find($key)
	{
		return $this->lists()->whereLoose('key', $key)->first();
	}

	/**
	 * Kiem tra su ton tai cua model
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return $this->find($key) ? true : false;
	}

	/**
	 * Lay danh sach models dang hoat dong
	 *
	 * @return Collection
	 */
	public function listActive()
	{
		return $this->lists()->whereLoose('status', 1);
	}

	/**
	 * Kiem tra model co dang kich hoat hay khong
	 *
	 * @param string $key
	 * @return bool
	 */
	public function isActive($key)
	{
		return $this->listActive()->whereLoose('key', $key)->count() ? true : false;
	}

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

	/**
	 * Tao doi tuong service
	 *
	 * @param string $key
	 * @return mixed
	 * @throw InvalidArgumentException
	 */
	protected function createService($key)
	{
		if ( ! $model = $this->find($key))
		{
			throw new InvalidArgumentException("Service [{$key}] not found");
		}

		return $this->makeServiceInstance($model);
	}

}