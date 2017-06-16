<?php namespace App\Accountant\Model;

use App\Accountant\AccountantFactory;
use App\Accountant\Library\Reason;
use App\Currency\Model\CurrencyRelationTrait;
use App\Purse\Model\PurseRelationTrait;
use App\User\Model\UserRelationTrait;

class LogBalanceModel extends \Core\Base\Model
{
	use PurseRelationTrait;
	use UserRelationTrait;
	use CurrencyRelationTrait;

	protected $table = 'log_balance';

	protected $microtime = true;

	protected $casts = [
		'amount'         => 'float',
		'balance'        => 'float',
		'purse_amount'   => 'float',
		'purse_balance'  => 'float',
		'reason_options' => 'array',
	];

	protected $defaults = [
		'reason_options' => [],
	];

	protected $formats = [
		'amount'        => 'amount',
		'balance'       => 'amount',
		'purse_amount'  => 'amount',
		'purse_balance' => 'amount',
		'created'       => 'date',
	];


	/**
	 * Tao doi tuong moi va gan $attributes
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function newWithAttributes(array $attributes)
	{
		$relations = static::pullRelationsFromAttributes($attributes, [
			'purse' => 'App\Purse\Model\PurseModel',
			'user'  => 'App\User\Model\UserModel',
		]);

		return parent::newWithAttributes($attributes)->fill($relations);
	}

	/**
	 * Lay purse_balance_pre
	 *
	 * @return float
	 */
	protected function getPurseBalancePreAttribute()
	{
		$status = $this->getAttribute('status');
		$purse_amount = $this->getAttribute('purse_amount');
		$purse_balance = $this->getAttribute('purse_balance');

		return ($status == '+')
			? $purse_balance - $purse_amount
			: $purse_balance + $purse_amount;
	}

	/**
	 * Lay reason_desc
	 *
	 * @return string
	 */
	protected function getReasonDescAttribute()
	{
		return $this->reasonInstance()->desc() ?: $this->getAttribute('desc');
	}

	/**
	 * Lay doi tuong reason
	 *
	 * @return Reason
	 */
	public function reasonInstance()
	{
		$key = $this->getAttribute('reason_key');

		$options = $this->getAttribute('reason_options');

		return AccountantFactory::balance()->makeReason($key, $options);
	}

	/**
	 * Tao url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function url($action, array $opt = [])
	{
		switch ($action)
		{
			case 'detail':
			{
				return $this->reasonInstance()->urlDetail();
			}
		}

		return parent::url($action, $opt);
	}

	/**
	 * Tao admin url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function adminUrl($action, array $opt = [])
	{
		switch ($action)
		{
			case 'detail':
			{
				return $this->reasonInstance()->adminUrlDetail();
			}
		}

		return parent::adminUrl($action, $opt);
	}

	/**
	 * Format du lieu
	 *
	 * @param string $key
	 * @param mixed  $option
	 * @return string|null
	 */
	public function format($key, $option = null)
	{
		switch($key)
		{
			case 'amount':
			case 'balance':
			{
				$amount = $this->getAttribute($key);

				return currency_format_amount_default($amount);
			}

			case 'purse_amount':
			case 'purse_balance':
			case 'purse_balance_pre':
			{
				$amount = $this->getAttribute($key);
				$currency_id = $this->getAttribute('currency_id');

				return currency_format_amount($amount, $currency_id);
			}
		}

		return parent::format($key, $option);
	}

}