<?php namespace App\Transfer\Handler\Form\Transfer;

use App\Purse\Model\PurseModel;
use App\Purse\PurseFactory;
use App\Transfer\TransferFactory;
use App\User\Model\UserModel;
use Core\Support\AttributesAccess;

class TransferForm extends AttributesAccess
{
	/**
	 * Danh sach attribute bo sung
	 *
	 * @var array
	 */
	protected $additional = [];

	/**
	 * Key luu tru
	 */
	const STORAGE_KEY = 'transfer';


	/**
	 * Lay thong tin purse gui
	 *
	 * @return PurseModel|null
	 */
	protected function getSenderPurseAttribute()
	{
		if ( ! array_key_exists('sender_purse', $this->additional))
		{
			$sender_purse_number = $this->getAttribute('sender_purse_number');

			$this->additional['sender_purse'] = PurseFactory::purse()->findByNumber($sender_purse_number);
		}

		return $this->additional['sender_purse'];
	}

	/**
	 * Lay sender
	 *
	 * @return UserModel|null
	 */
	protected function getSenderAttribute()
	{
		$purse = $this->getAttribute('sender_purse');

		return $purse ? $purse->user : null;
	}

	/**
	 * Lay thong tin purse nhan
	 *
	 * @return PurseModel|null
	 */
	protected function getReceiverPurseAttribute()
	{
		if ( ! array_key_exists('receiver_purse', $this->additional))
		{
			$receiver_purse_number = $this->getAttribute('receiver_purse_number');

			$this->additional['receiver_purse'] = PurseFactory::purse()->findByNumber($receiver_purse_number);
		}

		return $this->additional['receiver_purse'];
	}

	/**
	 * Lay receiver
	 *
	 * @return UserModel|null
	 */
	protected function getReceiverAttribute()
	{
		$purse = $this->getAttribute('receiver_purse');

		return $purse ? $purse->user : null;
	}

	/**
	 * Lay fee
	 *
	 * @return float
	 */
	protected function getFeeAttribute()
	{
		return $this->getAmount('fee');
	}

	/**
	 * Lay net
	 *
	 * @return float
	 */
	protected function getNetAttribute()
	{
		return $this->getAmount('net');
	}

	/**
	 * Lay transfer amount
	 *
	 * @param string $key
	 * @return float
	 */
	protected function getAmount($key = null)
	{
		if ( ! array_key_exists('amounts', $this->additional))
		{
			$this->additional['amounts'] = $this->makeAmounts();
		}

		return array_get($this->additional['amounts'], $key);
	}

	/**
	 * Tinh transfer amounts
	 *
	 * @return array
	 */
	protected function makeAmounts()
	{
		$sender_purse = $this->getAttribute('sender_purse');

		$amount = $this->getAttribute('amount');

		return $sender_purse
			? TransferFactory::transfer()->getAmounts($sender_purse, $amount)
			: [];
	}

	/**
	 * Format du lieu
	 *
	 * @param $key
	 * @return string
	 */
	public function format($key)
	{
		$currency_id = $this->getAttribute('sender_purse')->currency_id;

		switch($key)
		{
			case 'amount':
			case 'fee':
			case 'net':
			{
				$amount = $this->getAttribute($key);

				return currency_format_amount($amount, $currency_id);
			}
		}
	}

	/**
	 * Luu du lieu
	 */
	public function save()
	{
		t('session')->set_userdata(static::STORAGE_KEY, $this->attributes);
	}

	/**
	 * Lay thong tin
	 *
	 * @return null|static
	 */
	public static function get()
	{
		$attributes = t('session')->userdata(static::STORAGE_KEY);

		return $attributes ? new static($attributes) : null;
	}

	/**
	 * Xoa du lieu
	 */
	public static function delete()
	{
		t('session')->unset_userdata(static::STORAGE_KEY);
	}

}