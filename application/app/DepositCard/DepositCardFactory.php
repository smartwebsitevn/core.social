<?php namespace App\DepositCard;

use App\DepositCard\Service\DepositCardService;

class DepositCardFactory extends \Core\Base\Factory
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
	public static function depositCard()
	{
		return static::makeService('DepositCardService');
	}

}