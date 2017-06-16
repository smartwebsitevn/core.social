<?php namespace App\Product\Service;

use Core\App\App;
use Core\CustomerContact\Factory as BaseCustomerContact;

class CustomerContactService extends BaseCustomerContact
{
	/**
	 * Lay rules
	 *
	 * @return array
	 */
	public function getRules()
	{
		return App::validation()->makeRules([
			'email' => 'required|valid_email',
		]);
	}

}