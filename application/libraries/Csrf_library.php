<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Csrf_library
{
	/**
	 * Thuc hien delay request
	 */
	public function delay()
	{
		$seconds = rand(1, 500) / 100;

		usleep($seconds * 1000000);
	}

	/**
	 * Kiem tra request, neu khong hop le thi hien thi error
	 */
	public function check()
	{
		if ( ! $this->valid())
		{
			show_error("Invalid Request");
		}
	}

	/**
	 * Kiem tra thoi gian request hien tai
	 *
	 * @return bool
	 */
	public function valid()
	{
		if ( ! $this->validRequestTime())
		{
		    return false;
		}

		$this->updateRequestTime();

		return true;
	}

	/**
	 * Kiem tra thoi gian request hien tai
	 *
	 * @return bool
	 */
	protected function validRequestTime()
	{
		return now() > $this->getRequestTime();
	}

	/**
	 * Lay thoi gian request gan nhat
	 *
	 * @return int
	 */
	protected function getRequestTime()
	{
		return (int) t('session')->userdata('csrf_request_time');
	}

	/**
	 * Cap nhat thoi gian request hien tai
	 */
	protected function updateRequestTime()
	{
		t('session')->set_userdata('csrf_request_time', now());
	}

}