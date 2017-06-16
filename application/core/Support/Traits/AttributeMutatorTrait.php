<?php namespace Core\Support\Traits;

trait AttributeMutatorTrait
{
	/**
	 * Kiem tra su ton tai cua SetMutator
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function hasSetMutator($key)
	{
		return method_exists($this, $this->methodSetMutator($key));
	}

	/**
	 * Goi SetMutator
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	protected function callSetMutator($key, $value)
	{
		return call_user_func_array([$this, $this->methodSetMutator($key)], [$value]);
	}

	/**
	 * Lay ten method cua SetMutator
	 *
	 * @param string $key
	 * @return string
	 */
	protected function methodSetMutator($key)
	{
		return 'set'.studly_case($key).'Attribute';
	}

	/**
	 * Kiem tra su ton tai cua GetMutator
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function hasGetMutator($key)
	{
		return method_exists($this, $this->methodGetMutator($key));
	}

	/**
	 * Goi GetMutator
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return mixed
	 */
	protected function callGetMutator($key, $value)
	{
		return call_user_func_array([$this, $this->methodGetMutator($key)], [$value]);
	}

	/**
	 * Lay ten method cua GetMutator
	 *
	 * @param string $key
	 * @return string
	 */
	protected function methodGetMutator($key)
	{
		return 'get'.studly_case($key).'Attribute';
	}
}