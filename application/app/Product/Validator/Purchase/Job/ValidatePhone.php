<?php namespace App\Product\Validator\Purchase\Job;

use App\Product\Validator\Purchase\Factory;
use App\Product\Validator\Purchase\Error;
use App\Product\Validator\Purchase\PurchaseException;
use App\Product\Validator\Purchase\Base\Job;
use Core\Support\Phone;

class ValidatePhone extends Job
{
	/**
	 * So dien thoai
	 *
	 * @var string
	 */
	protected $phone;

	/**
	 * Khoi tao doi tuong
	 *
	 * @param Factory $factory
	 * @param string  $phone
	 */
	public function __construct(Factory $factory, $phone)
	{
		parent::__construct($factory);

		$this->phone = $phone;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @throws PurchaseException
	 */
	public function handle()
	{
		if ( ! $this->checkPhoneFormat())
		{
			$this->throwException(ERROR::PHONE_INVALID);
		}

//		if ( ! $this->checkPhoneProvider())
//		{
//			$this->throwException(Error::PHONE_PROVIDER_INVALID);
//		}
	}

	/**
	 * Kiem tra dinh dang phone
	 *
	 * @return bool
	 */
	protected function checkPhoneFormat()
	{
		return Phone::validPhone($this->getPhone());
	}

	/**
	 * Kiem tra nha mang cua phone
	 *
	 * @return bool
	 */
	protected function checkPhoneProvider()
	{
		$provider = Phone::getProvider($this->getPhone());

		return $this->getProduct()->type_type == $provider;
	}

	/**
	 * Lay phone
	 *
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

}