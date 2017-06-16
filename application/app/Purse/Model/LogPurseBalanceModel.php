<?php namespace App\Purse\Model;

class LogPurseBalanceModel extends \Core\Base\Model
{
	protected $table = 'log_purse_balance';

	protected $casts = [
		'balance_before' => 'float',
		'amount'         => 'float',
		'balance'        => 'float',
	];

	/**
	 * Tao log
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function createLog(array $attributes)
	{
		$attributes = array_add($attributes, 'url', current_url(true));
		$attributes = array_add($attributes, 'ip', t('input')->ip_address());

		return static::create($attributes);
	}

}