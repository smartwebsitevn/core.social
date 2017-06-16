<?php namespace Core\Support\Traits;

trait FormMakerTrait
{
	/**
	 * Load config
	 *
	 * @return array
	 */
	abstract protected function loadConfig();

	/**
	 * Lay config
	 *
	 * @param null|string $key
	 * @param mixed       $default
	 * @return array|mixed
	 */
	public function config($key = null, $default = null)
	{
		$config = $this->loadConfig();

		$config = $this->makeConfig($config);

		$config = $this->handleConfig($config);

		return array_get($config, $key, $default);
	}

	/**
	 * Tao config
	 *
	 * @param array $config
	 * @return array
	 */
	protected function makeConfig($config)
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
	 * Xu ly config
	 *
	 * @param array $config
	 * @return array
	 */
	protected function handleConfig(array $config)
	{
		return $config;
	}

	/**
	 * Lay list cac gia tri cua 1 key config
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return array
	 */
	public function listConfig($key, $default = null)
	{
		return array_map(function($args) use ($key, $default)
		{
			return array_get($args, $key, $default);
		}, $this->config());
	}

	/**
	 * Tao config form
	 *
	 * @param string $param
	 * @param array  $data
	 * @return array
	 */
	public function formConfig($param = null, array $data = [])
	{
		$config = $this->config();

		foreach ($config as $name => &$args)
		{
			$args['param'] = $param ? $param.'['.$name.']' : $name;

			$args['value'] = array_get($data, $name, array_get($args, 'value'));

			$args = array_except($args, 'rules');
		}

		return $config;
	}

	/**
	 * Tao form
	 *
	 * @param array $form_config
	 * @return null|string
	 */
	public function form(array $form_config){}

	/**
	 * Validate du lieu
	 *
	 * @param array  $input
	 * @param string $error
	 * @return bool
	 */
	public function validate(array $input, &$error = null)
	{
		return true;
	}

	/**
	 * Lay rules
	 *
	 * @param null $param
	 * @return array
	 */
	public function rules($param = null)
	{
		$rules = [];

		foreach ($this->config() as $name => $args)
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
	 * Xu ly gia tri
	 *
	 * @param array $data
	 * @return array
	 */
	public function value(array $data)
	{
		return $data;
	}

	/**
	 * Kiem tra co su dung hay khong
	 *
	 * @return bool
	 */
	public function isUse()
	{
		return count($this->config()) ? true : false;
	}

}