<?php namespace App\Purse\Model;

use App\Currency\Model\CurrencyRelationTrait;
use App\Purse\PurseFactory as PurseFactory;
use App\Purse\Library\BalanceEncrypter;
use App\User\Model\UserModel as UserModel;
use App\Currency\CurrencyFactory as CurrencyFactory;
use App\Currency\Model\CurrencyModel as CurrencyModel;
use App\User\Model\UserRelationTrait;

class PurseModel extends \Core\Base\Model
{
	use UserRelationTrait;
	use CurrencyRelationTrait;

	protected $table = 'purse';

	protected $casts = [
		'balance_decode' => 'float',
	];

	protected $formats = [
		'balance' => 'amount',
		'created' => 'date',
	];


	/**
	 * Gan balance
	 *
	 * @param float $value
	 */
	protected function setBalanceAttribute($value)
	{
		$this->attributes['balance'] = $this->makeEncrypter()->encode($value);
		$this->attributes['balance_decode'] = $value;
	}

	/**
	 * Lay balance
	 *
	 * @param string $value
	 * @return float
	 */
	protected function getBalanceAttribute($value)
	{
		return $this->makeEncrypter()->decode($value);
	}

	/**
	 * Tao doi tuong Encrypter
	 *
	 * @return BalanceEncrypter
	 */
	protected function makeEncrypter()
	{
		return new BalanceEncrypter($this->getKey());
	}

	/**
	 * Cap nhat balance
	 *
	 * @param float $balance
	 * @return bool
	 */
	public function updateBalance($balance)
	{
		return $this->update([
			'balance' => $balance,
			'balance_decode' => $balance,
		]);
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
			case 'balance':
			{
				$balance = $this->getAttribute('balance');
				$currency_id = $this->getAttribute('currency_id');

				return currency_format_amount($balance, $currency_id);
			}
		}

		return parent::format($key, $option);
	}

	/**
	 * Tao options cho invoice_order tuong ung voi purse
	 *
	 * @return array
	 */
	public function makeInvoiceOrderOptions()
	{
		$user = $this->getAttribute('user');

		return [
			'purse_id'      => $this->getAttribute('id'),
			'purse_number'  => $this->getAttribute('number'),
			'currency_id'   => $this->getAttribute('currency_id'),
			'user_id'       => $this->getAttribute('user_id'),
			'user_username' => $user->username,
			'user_email'    => $user->email,
			'user_phone'    => $user->phone,
		];
	}
}