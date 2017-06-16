<?php namespace App\Purse\Library;

use CI_Encrypt as Encrypt;

class BalanceEncrypter
{
	/**
	 * Key ma hoa
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Doi tuong Encrypt
	 *
	 * @var Encrypt
	 */
	protected $encrypt;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param string $key
	 */
	public function __construct($key)
	{
		t('load')->library('encrypt');

		$this->key = $key;

		$this->encrypt = t('encrypt');
	}

	/**
	 * Encode balance
	 *
	 * @param float $balance
	 * @return string
	 */
	public function encode($balance)
	{
		return $this->encrypt->encode((float) $balance, $this->makeEncryptKey());
	}

	/**
	 * Giai ma balance
	 *
	 * @param string $string
	 * @return float
	 */
	public function decode($string)
	{
		return (float) $this->encrypt->decode($string, $this->makeEncryptKey());
	}

	/**
	 * Tao encrypt key
	 *
	 * @return string
	 */
	protected function makeEncryptKey()
	{
		return config('encryption_key', '').'-'.$this->key;
	}

	/**
	 * Lay key
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

}