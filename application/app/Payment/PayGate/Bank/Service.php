<?php namespace App\Payment\PayGate\Bank;

use App\Payment\Library\PayGateService;

class Service extends PayGateService
{
	/**
	 * Co the thuc hien thanh toan qua payment hay khong
	 *
	 * @return bool
	 */
	public function canPayment()
	{
		return false;
	}

	/**
	 * Co the thuc hien rut tien qua payment hay khong
	 *
	 * @return bool
	 */
	public function canWithdraw()
	{
		return true;
	}

}