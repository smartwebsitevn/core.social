<?php namespace Core\Base;

use Core\App\App;
use Core\FormHandler\FormHandlerInterface;
use Core\Support\Arr;

abstract class FormHandler implements FormHandlerInterface
{
	/**
	 * Form input
	 *
	 * @var array
	 */
	protected $input = [];

	/**
	 * Form data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Form errors
	 *
	 * @var array
	 */
	protected $errors = [];


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $input
	 */
	public function __construct(array $input = null)
	{
		$this->input = $input ?: (array) t('input')->post();
	}

	/**
	 * Lay form view data
	 *
	 * @return array
	 */
	public function form()
	{
		return [];
	}

	/**
	 * Xu ly form error
	 *
	 * @return array
	 */
	public function error()
	{
		return [];
	}

	/**
	 * Lay input
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function input($key = null, $default = null)
	{
		return array_get($this->input, $key, $default);
	}

	/**
	 * Lay input cua cac keys
	 *
	 * @param string|array $keys
	 * @return array
	 */
	public function inputOnly($keys)
	{
		return Arr::pick($this->input(), $keys);
	}

	/**
	 * Lay error
	 *
	 * @param string $key
	 * @return string
	 */
	public function getError($key)
	{
		return array_get($this->errors, $key);
	}

	/**
	 * Lay danh sach errors
	 *
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Gan validation rules
	 *
	 * @param array $rules
	 */
	protected function setValidationRules(array $rules)
	{
		App::validation()->setRules($rules);
	}

}