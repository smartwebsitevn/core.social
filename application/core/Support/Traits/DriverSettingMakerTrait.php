<?php namespace Core\Support\Traits;

trait DriverSettingMakerTrait
{
	/**
	 * Load config setting
	 *
	 * @return array
	 */
	abstract protected function loadSettingConfig();

	/**
	 * Lay config setting
	 *
	 * @param null|string $key
	 * @param mixed       $default
	 * @return mixed
	 */
	public function settingConfig($key = null, $default = null)
	{
		$config = $this->loadSettingConfig();

		$config = $this->makeSettingConfig($config);

		$config = $this->handleSettingConfig($config);

		return array_get($config, $key, $default);
	}

	/**
	 * Tao config setting
	 *
	 * @param array $config
	 * @return array
	 */
	protected function makeSettingConfig($config)
	{
		$config = is_array($config) ? $config : [];

		foreach ($config as $name => &$args)
		{
			$args = array_add($args, 'type', 'text');
			$args = array_add($args, 'name', $name);
			$args = array_add($args, 'rules', '');
		}

		return $config;
	}

	/**
	 * Xu ly config setting
	 *
	 * @param array $config
	 * @return array
	 */
	protected function handleSettingConfig(array $config)
	{
		return $config;
	}

	/**
	 * Tao config form setting
	 *
	 * @param string $param
	 * @param array  $data
	 * @return array
	 */
	public function settingFormConfig($param = null, array $data = [])
	{
		$config = $this->settingConfig();

		foreach ($config as $name => &$args)
		{
			$args['param'] = $param ? $param.'['.$name.']' : $name;

			$args['value'] = array_get($data, $name, array_get($args, 'value'));

			$args = array_except($args, 'rules');
		}

		return $config;
	}

	/**
	 * Tao form setting
	 *
	 * @param array $config
	 * @return null|string
	 */
	public function settingForm(array $form_config){}

	/**
	 * Lay list cac gia tri cua 1 key config setting
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return array
	 */
	public function listSettingConfig($key, $default = null)
	{
		return array_map(function($args) use ($key, $default)
		{
			return array_get($args, $key, $default);
		}, $this->settingConfig());
	}

	/**
	 * Validate du lieu
	 *
	 * @param array  $input
	 * @param string $error
	 * @return bool
	 */
	public function validateSetting(array $input, &$error = null)
	{
		return true;
	}

	/**
	 * Lay setting rules
	 *
	 * @param null $param
	 * @return array
	 */
	public function settingRules($param = null)
	{
		$rules = [];

		foreach ($this->settingConfig() as $name => $args)
		{
			$field = $param ? $param.'['.$name.']' : $name;

			$rules[$field] = [
				'field' => $field,
				'label' => $args['name'],
				'rules' => $args['rules'],
			];
		}

		return $rules;
	}

	/**
	 * Xu ly gia tri setting
	 *
	 * @param array $setting
	 * @return array
	 */
	public function handleSettingValue(array $setting)
	{
		return $setting;
	}

	/**
	 * Kiem tra co su dung setting hay khong
	 *
	 * @return bool
	 */
	public function useSetting()
	{
		return count($this->settingConfig()) ? true : false;
	}

}