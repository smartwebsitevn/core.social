<?php namespace App\Product\Job\ActiveOrder\Handler;

use App\Product\Job\ActiveOrder\Command;
use App\Product\Job\ActiveOrder\Handler;
use App\Product\Library\Provider\TranResponse;

class TopupMobile extends Handler
{
	/**
	 * Thuc hien request den provider
	 *
	 * @return TranResponse
	 */
	public function request()
	{
		return $this->dispatchCommandTopup(Command::TOPUP_MOBILE);
	}
}