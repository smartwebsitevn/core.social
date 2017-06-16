<?php namespace Core\Support\Traits;

trait PropertyAccessTrait
{
	/**
	 * Gan gia tri cho thuoc tinh
	 *
	 * @param string|array $key
	 * @param mixed|null   $value
	 * @return $this
	 */
	public function set($key, $value = null)
	{
		$args = is_array($key) ? $key : [$key => $value];

		foreach ($args as $key => $value)
		{
			if ($this->hasProperty($key))
			{
				$this->$key = $value;
			}
		}

		return $this;
	}

	/**
	 * Lay gia tri cua thuoc tinh
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		return $this->hasProperty($key) ? $this->$key : $default;
	}

	/**
	 * Kiem tra su ton tai cua property
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function hasProperty($key)
	{
		return property_exists($this, $key);
	}

} 