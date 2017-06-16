<?php namespace Core\Support;

use Core\Base\Model as BaseModel;
use TF\Support\Collection;

abstract class DriverInstallableManager extends DriverManager
{
	/**
	 * Danh sach da cai dat
	 *
	 * @var Collection
	 */
	protected $list_installed;


	/**
	 * Lay thong tin tu config
	 *
	 * @param string $key
	 * @return array
	 */
	abstract protected function getInfoData($key);

	/**
	 * Tao doi tuong model
	 *
	 * @param array $info
	 * @return BaseModel
	 */
	abstract protected function newModelInstance(array $info);

	/**
	 * Lay danh sach du lieu trong database
	 *
	 * @return Collection
	 */
	abstract protected function getListModelData();

	/**
	 * Tao thong tin tu config
	 *
	 * @param string $key
	 * @param $key
	 * @return BaseModel
	 */
	public function makeInfo($key)
	{
		return $this->newModelInstance($this->getInfoData($key));
	}

	/**
	 * Tao list thong tin tu config
	 *
	 * @param string|array $keys
	 * @return Collection
	 */
	public function makeListInfo($keys)
	{
		$keys = is_array($keys) ? $keys : func_get_args();

		$result = [];
		foreach ($keys as $key)
		{
			$result[] = $this->makeInfo($key);
		}

		return collect($result);
	}

	/**
	 * Lay danh sach da cai dat
	 *
	 * @return Collection
	 */
	public function listInstalled()
	{
		if (is_null($this->list_installed))
		{
			$this->list_installed = $this->getListModelData();
		}

		return $this->list_installed;
	}

	/**
	 * Lay danh sach chua duoc cai dat
	 *
	 * @return Collection
	 */
	public function listNotInstalled()
	{
		$list = $this->lists();

		$list_installed = $this->listInstalled()->lists('key');

		$list_not_installed = array_diff($list, $list_installed);

		return $this->makeListInfo($list_not_installed);
	}

	/**
	 * Lay danh sach dang hoat dong
	 *
	 * @return Collection
	 */
	public function listActive()
	{
		return $this->listInstalled()->whereLoose('status', 1);
	}

	/**
	 * Lay thong tin trong data
	 *
	 * @param string $key
	 * @return BaseModel|null
	 */
	public function data($key)
	{
		return $this->listInstalled()->where('key', $key)->first();
	}

	/**
	 * Kiem tra da duoc cai dat hay chua
	 *
	 * @param string $key
	 * @return bool
	 */
	public function installed($key)
	{
		return $this->data($key) ? true : false;
	}

	/**
	 * Kiem tra co dang kich hoat hay khong
	 *
	 * @param string $key
	 * @return bool
	 */
	public function isActive($key)
	{
		return $this->listActive()->where('key', $key)->count() ? true : false;
	}

	/**
	 * Lay thong tin
	 *
	 * @param string $key
	 * @param string $param
	 * @param mixed  $default
	 * @return mixed
	 */
	public function info($key, $param = null, $default = null)
	{
		$info = $this->data($key) ?: $this->makeInfo($key);

		return data_get($info, $param, $default);
	}

	/**
	 * Lay danh sach bao gom ca thong tin
	 *
	 * @return array
	 */
	public function listInfo()
	{
		return $this->makeListInfo($this->lists());
	}

	/**
	 * Kiem tra co the cai dat hay khong
	 *
	 * @param string $key
	 * @return bool
	 */
	public function canInstall($key)
	{
		return ($this->has($key) && ! $this->installed($key));
	}

	/**
	 * Kiem tra co the go bo hay khong
	 *
	 * @param $key
	 * @return bool
	 */
	public function canUninstall($key)
	{
		return $this->installed($key);
	}

}