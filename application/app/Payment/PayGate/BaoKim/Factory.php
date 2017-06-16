<?php namespace App\Payment\PayGate\BaoKim;

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
		return 'BaoKim';
	}

}