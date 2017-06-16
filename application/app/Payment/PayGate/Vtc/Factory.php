<?php namespace App\Payment\PayGate\Vtc;

use App\Payment\Library\PayGateFactory;

class Factory extends PayGateFactory
{
	/**
	 * Lay key cua driver
	 *
	 * @return string
	 */
	public function key()
	{
		return 'Vtc';
	}
}