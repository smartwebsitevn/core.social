<?php namespace Core\RequestHandler;

class Action extends RequestHandler
{
	/**
	 * Callback lay input
	 *
	 * @var mixed
	 */
	protected $input;

	/**
	 * Callback xu ly
	 *
	 * @var mixed
	 */
	protected $handle;

	/**
	 * Location mac dinh sau khi xu ly xong
	 *
	 * @var string
	 */
	protected $location;


	/**
	 * Thuc hien xu ly
	 *
	 * @return mixed
	 */
	public function run()
	{
		$input = $this->callInput();

		$result = $this->callHandle($input);

		if (t('input')->is_ajax_request())
		{
			$result = array_merge(['status' => true], $result);

			return set_output('json', json_encode($result));
		}

		$location = array_get($result, 'location');

		redirect($location ?: $this->location);
	}

	/**
	 * Goi callback input
	 *
	 * @return mixed
	 */
	protected function callInput()
	{
		return $this->call($this->input);
	}

	/**
	 * Goi callback handle
	 *
	 * @param mixed $input
	 * @return array
	 */
	protected function callHandle($input)
	{
		$result = $this->call($this->handle, [$input]);

		return is_array($result) ? $result : ['location' => $result ?: $this->location];
	}
}