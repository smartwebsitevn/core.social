<?php namespace App\Product\Job\ActiveOrder\Handler;

use App\Product\Job\ActiveOrder\Command;
use App\Product\Job\ActiveOrder\Handler;
use App\Product\Library\Provider\TranResponse;

class TopupMobilePost extends Handler
{
	/**
	 * Thuc hien request den provider
	 *
	 * @return TranResponse
	 */
	public function request()
	{
		return $this->dispatch(Command::TOPUP_MOBILE_POST, [
			'key_connection' => $this->getProduct()->provider_key_connection,
			'account'        => $this->getOrder()->account,
			'amount'         => $this->getOrder()->quantity,
		]);
	}
}