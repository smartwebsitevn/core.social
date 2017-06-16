<?php namespace App\LogActivity\ActivityLogger;

use App\LogActivity\Library\ActivityLogger;

class Tran extends ActivityLogger
{
	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Transaction',
		];
	}

}