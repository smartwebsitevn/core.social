<?php namespace Core\Support;

use Core\Support\Traits\AttributeMutatorTrait;
use JsonSerializable;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionsAccess implements JsonSerializable
{
	use AttributeMutatorTrait;

	/**
	 * Options data
	 *
	 * @var array
	 */
	protected $options = [];

	/**
	 * Options config
	 *
	 *    $config[option] => [
	 *        'required' => true,
	 *        'default' => 'Default',
	 *        'allowed_types' => 'array', // is_<type>() function is defined in PHP
	 *        'allowed_values' => 'Value',
	 *        'cast' => 'int', // ('int', 'float', 'string', 'bool')
	 *    ]
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Danh sach options bo sung
	 *
	 * @var array
	 */
	protected $additional = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		$this->initConfig();

		$this->setOptions($options);
	}

	/**
	 * Tao doi tuong
	 *
	 * @param array $options
	 * @return static
	 */
	public static function make(array $options = [])
	{
		return new static($options);
	}

	/**
	 * Callback khoi tao config
	 */
	protected function initConfig(){}

	/**
	 * Gan options
	 *
	 * @param array $options
	 */
	protected function setOptions(array $options)
	{
		$options = $this->resolveOptions($options);

		foreach ($options as $key => $value)
		{
			$this->setOption($key, $value);
		}
	}

	/**
	 * Xu ly options
	 *
	 * @param array $options
	 * @return array
	 */
	protected function resolveOptions(array $options)
	{
		$resolver = new OptionsResolver;

		$resolver
			->setDefined(array_keys($options))
			->setRequired(array_keys($this->listConfig('required')))
			->setDefaults($this->listConfig('default'))
			->setAllowedTypes($this->listConfig('allowed_types'))
			->setAllowedValues($this->listConfig('allowed_values'));

		return $resolver->resolve($options);
	}

	/**
	 * Lay list config theo key
	 *
	 * @param string $key
	 * @return array
	 */
	protected function listConfig($key)
	{
		$result = [];

		foreach (array_keys($this->config) as $option)
		{
			if ( ! is_null($value = $this->getOptionConfig($option, $key)))
			{
				$result[$option] = $value;
			}
		}

		return $result;
	}

	/**
	 * Lay config cua option
	 *
	 * @param string $option
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	protected function getOptionConfig($option, $key = null, $default = null)
	{
		$config = array_get($this->config, $option, []);

		return array_get($config, $key, $default);
	}

	/**
	 * Gan option
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	protected function setOption($key, $value)
	{
		if ($this->hasSetMutator($key))
		{
			return $this->callSetMutator($key, $value);
		}

		$this->options[$key] = $value;
	}

	/**
	 * Lay options
	 *
	 * @return array
	 */
	public function all()
	{
		$keys = array_merge(array_keys($this->config), array_keys($this->options));

		return $this->only(array_unique($keys));
	}

	/**
	 * Lay option cua cac keys
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function only($keys)
	{
		$result = [];

		foreach ((array) $keys as $key)
		{
			$result[$key] = $this->get($key);
		}

		return $result;
	}

	/**
	 * Lay option ngoai tru cac keys
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function except($keys)
	{
		return array_except($this->all(), $keys);
	}

	/**
	 * Lay option
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		$value = $this->getOptionValue($key);

		if ($this->hasGetMutator($key))
		{
			return $this->callGetMutator($key, $value);
		}

		if ($this->hasCast($key))
		{
			return $this->castOption($key, $value);
		}

		return $value;
	}

	/**
	 * Lay gia tri cua option
	 *
	 * @param string $key
	 * @return mixed
	 */
	protected function getOptionValue($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Kiem tra key co duoc gan kieu du lieu hay khong
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function hasCast($key)
	{
		return $this->getCastType($key) ? true : false;
	}

	/**
	 * Lay kieu du lieu cua key
	 *
	 * @param string $key
	 * @return string
	 */
	protected function getCastType($key)
	{
		return $this->getOptionConfig($key, 'cast');
	}

	/**
	 * Xu ly gia tri theo kieu du lieu cua key
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	protected function castOption($key, $value)
	{
		switch ($this->getCastType($key))
		{
			case 'int':
			case 'integer':
			{
				return (int) $value;
			}
			case 'real':
			case 'float':
			case 'double':
			{
				return (float) $value;
			}
			case 'string':
			{
				return (string) $value;
			}
			case 'bool':
			case 'boolean':
			{
				return (bool) $value;
			}
		}

		return $value;
	}

	/**
	 * Lay ten method cua SetMutator
	 *
	 * @param string $key
	 * @return string
	 */
	protected function methodSetMutator($key)
	{
		return 'set'.studly_case($key).'Option';
	}

	/**
	 * Lay ten method cua GetMutator
	 *
	 * @param string $key
	 * @return string
	 */
	protected function methodGetMutator($key)
	{
		return 'get'.studly_case($key).'Option';
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->get($key);
	}

	/**
	 * Isset
	 *
	 * @param string $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return ! is_null($this->get($key));
	}

	/**
	 * Convert instance to its string representation.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * Convert instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->all();
	}

	/**
	 * Convert instance to JSON.
	 *
	 * @param  int $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

}