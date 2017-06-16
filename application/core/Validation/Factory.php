<?php namespace Core\Validation;

use CI_Form_validation as Validator;

class Factory
{
	/**
	 * Doi tuong Validator
	 *
	 * @var Validator
	 */
	protected $validator;


	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('load')->library('form_validation');

		$this->validator = t('form_validation');
	}

	/**
	 * Tao rules
	 *
	 * @param array $rules
	 * @return array
	 */
	public function makeRules(array $rules)
	{
		foreach ($rules as $key => &$options)
		{
			$options = is_array($options) ? $options : ['rules' => $options];

			$options = array_add($options, 'field', $key);
			$options = array_add($options, 'label', lang($key));
		}

		return $rules;
	}

	/**
	 * Gan rules
	 *
	 * @param array $rules
	 */
	public function setRules(array $rules)
	{
		$rules = $this->makeRules($rules);

		$this->validator->set_rules($rules);
	}

	/**
	 * Kiem tra du lieu co hop le hay khong
	 *
	 * @return bool
	 */
	public function run()
	{
		return $this->validator->run();
	}

	/**
	 * Lay error
	 *
	 * @param string $param
	 * @return string
	 */
	public function error($param)
	{
		return $this->validator->error($param);
	}

	/**
	 * Lay error cua cac bien
	 *
	 * @param array $params
	 * @return array
	 */
	public function errors(array $params)
	{
		$errors = [];

		foreach ($params as $param)
		{
			$errors[$param] = $this->error($param);
		}

		return $errors;
	}
}