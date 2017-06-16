<?php namespace App\LogActivity\ActivityOwner;

use App\LogActivity\Library\ActivityOwner;

class System extends ActivityOwner
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct('system');
	}
}