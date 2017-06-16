<?php namespace App\Product\Provider\Site;

use App\Product\Library\ProviderFactory;

class Factory extends ProviderFactory
{
	/**
	 * Lay key cua driver
	 *
	 * @return string
	 */
	public function key()
	{
		return 'Site';
	}
}