<?php namespace Core\RequestHandler;

class Form extends RequestHandler
{
	/**
	 * Callback gan rules validate
	 *
	 * @var mixed
	 */
	protected $validation;

	/**
	 * Callback xu ly form khi du lieu hop le
	 *
	 * @var mixed
	 */
	protected $submit;

	/**
	 * Callback xu ly form error
	 *
	 * @var mixed
	 */
	protected $error;

	/**
	 * Callback hien thi form
	 *
	 * @var mixed
	 */
	protected $form;

	/**
	 * Location mac dinh sau khi submit form
	 *
	 * @var string
	 */
	protected $location;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $args
	 */
	public function __construct(array $args = [])
	{
		parent::__construct($args);

		t('load')->helper('form');
		t('load')->library('form_validation');
	}

	/**
	 * Run form
	 *
	 * @return mixed
	 */
	public function run()
	{
		if ($this->isSubmit())
		{
		    return $this->runSubmit();
		}

		return $this->call($this->form);
	}

	/**
	 * Kiem tra request hien tai co phai la form submit hay khong
	 *
	 * @return bool
	 */
	protected function isSubmit()
	{
		return strtolower(t('input')->method()) == 'post';
	}

	/**
	 * Xu ly form submit
	 */
	protected function runSubmit()
	{
		$result = [];

		$params = $this->call($this->validation);

		if ($this->isValid())
		{
		    $result = $this->submitPasses($params);
		}

		if (empty($result['complete']))
		{
			$result = array_merge($result, $this->submitFails($params, $result));
		}

		$this->response($result);
	}

	/**
	 * Kiem tra du lieu co hop le hay khong
	 *
	 * @return bool
	 */
	protected function isValid()
	{
		return t('form_validation')->run();
	}

	/**
	 * Xu ly submit passes
	 *
	 * @param array $params
	 * @return array
	 */
	protected function submitPasses(array $params)
	{
		$result = [
			'complete' => true,
			'location' => $this->location,
		];

		$submit_res = $this->call($this->submit, [$params]);

		if (is_array($submit_res))
		{
			$result = array_merge($result, $submit_res);
		}
		elseif ($submit_res)
		{
			$result['location'] = $submit_res;
		}

		return $result;
	}

	/**
	 * Xu ly submit fails
	 *
	 * @param array $params
	 * @return array
	 */
	protected function submitFails(array $params)
	{
		$errors = $this->call($this->error, [$params]);

		return is_array($errors) ? $errors : $this->getErrors($params);
	}

	/**
	 * Lay form errors
	 *
	 * @param array $params
	 * @return array
	 */
	protected function getErrors(array $params)
	{
		$errors = [];

		foreach ($params as $param)
		{
			$errors[$param] = form_error($param);
		}

		return $errors;
	}

	/**
	 * Tao response
	 *
	 * @param array $result
	 */
	protected function response(array $result)
	{
		set_output('json', json_encode($result));
	}

}