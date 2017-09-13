<?php namespace App\Payment\PayGate\BlockChain;

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
		return 'BlockChain';
	}
}