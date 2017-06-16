<?php namespace Core\Support;

use Core\Support\Traits\DriverSettingMakerTrait;

abstract class DriverInstallableBase extends DriverBase
{
	use DriverSettingMakerTrait;

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return array_merge(
			array_only($this->config(), ['name', 'desc', 'version']),
			['key' => $this->key()]
		);
	}

	/**
	 * Load config
	 *
	 * @return array
	 */
	protected function loadSettingConfig()
	{
		return $this->config('setting', []);
	}

}