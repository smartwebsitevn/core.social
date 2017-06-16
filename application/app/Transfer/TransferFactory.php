<?php namespace App\Transfer;

use App\Transfer\Service\TransferService;

class TransferFactory extends \Core\Base\Factory
{
	/**
	 * Lay namespace root
	 *
	 * @return string
	 */
	public function getRootNamespace()
	{
		return __NAMESPACE__;
	}

	/**
	 * Lay doi tuong TransferService
	 *
	 * @return TransferService
	 */
	public static function transfer()
	{
		return static::makeService('TransferService');
	}

}