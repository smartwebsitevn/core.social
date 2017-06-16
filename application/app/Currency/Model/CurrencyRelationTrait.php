<?php namespace App\Currency\Model;

use App\Currency\CurrencyFactory as CurrencyFactory;
use App\Currency\Model\CurrencyModel as CurrencyModel;

trait CurrencyRelationTrait
{
	/**
	 * Lay currency
	 *
	 * @return CurrencyModel|null
	 */
	protected function getCurrencyAttribute()
	{
		$currency_id = $this->getAttribute('currency_id');

		return CurrencyFactory::currency()->find($currency_id);
	}

}