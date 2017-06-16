<?php namespace App\Payment\PayGate\NganLuong;

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
		return 'NganLuong';
	}
}