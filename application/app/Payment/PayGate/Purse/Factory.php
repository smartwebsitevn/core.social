<?php namespace App\Payment\PayGate\Purse;

use App\Payment\Library\PayGateFactory;
use App\Currency\CurrencyFactory as CurrencyFactory;

class Factory extends PayGateFactory
{
	/**
	 * Lay key cua driver
	 *
	 * @return string
	 */
	public function key()
	{
		return 'Purse';
	}

}