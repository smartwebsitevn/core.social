<?php namespace App\LogActivity\ActivityLogger;

use App\LogActivity\Library\ActivityLogger;

class Withdraw extends ActivityLogger
{
	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Withdraw',
		];
	}
}