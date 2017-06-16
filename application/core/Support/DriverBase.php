<?php namespace Core\Support;

abstract class DriverBase
{
	/**
	 * Trang thai load config
	 *
	 * @var bool
	 */
	protected $driver_config_loaded = false;

	/**
	 * Trang thai load lang
	 *
	 * @var bool
	 */
	protected $driver_lang_loaded = false;


	/**
	 * Lay key cua driver
	 *
	 * @return string
	 */
	abstract public function key();

	/**
	 * Lay type cua driver
	 *
	 * @return string
	 */
	abstract protected function getDriverType();

	/**
	 * Lay config
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function config($key = null, $default = null)
	{
		$this->loadConfig();

		$config = config($this->makeResourceName(), '') ?: [];

		return array_get($config, $key, $default);
	}

	/**
	 * Load config
	 */
	protected function loadConfig()
	{
		if ($this->driver_config_loaded) return;

		$this->performLoadConfig();

		$this->driver_config_loaded = true;
	}

	/**
	 * Thuc hien load config
	 */
	protected function performLoadConfig()
	{
		t('config')->load($this->makeResourceName(), true, true);
	}

	/**
	 * Lay lang
	 *
	 * @param string $key
	 * @param array  $replace
	 * @return string
	 */
	public function lang($key, $replace = [])
	{
		$this->loadLang();

		return lang($this->makeLangKey($key), $replace);
	}

	/**
	 * Load lang
	 */
	protected function loadLang()
	{
		if ($this->driver_lang_loaded) return;

		$this->performLoadLang();

		$this->driver_lang_loaded = true;
	}

	/**
	 * Thuc hien load lang
	 */
	protected function performLoadLang()
	{
		t('lang')->load('extensions/'.$this->makeResourceName());
	}

	/**
	 * Tao lang key
	 *
	 * @param string $key
	 * @return string
	 */
	protected function makeLangKey($key)
	{
		return $this->getDriverType().'.'.$this->key().'.'.$key;
	}

	/**
	 * Load view
	 *
	 * @param string $view
	 * @param array  $data
	 * @param bool	 $return
	 */
	public function view($view, array $data = [], $return = false)
	{
		return t('view')->load($this->viewPath($view), $data, $return);
	}

	/**
	 * Lay view path
	 *
	 * @param string $view
	 * @return string
	 */
	public function viewPath($view)
	{
		return 'tpl::_'.$this->makeResourceName($view);
	}

	/**
	 * Tao name cho cac thanh phan
	 *
	 * @param string $name
	 * @return string
	 */
	protected function makeResourceName($name = '')
	{
		return $this->getDriverType().'/'.$this->key() . ($name ? '/'.$name : $name);
	}

}