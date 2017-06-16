<?php namespace App\Payment\Library;

use Core\Base\Model as BaseModel;
use Core\Support\DriverModelServiceableManager;
use App\Payment\PaymentFactory as PaymentFactory;
use App\Payment\Model\PaymentModel as PaymentModel;

class PaymentManager extends DriverModelServiceableManager
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$models = PaymentModel::all()->all();

		parent::__construct($models);
	}

	/**
	 * Thuc hien tao doi tuong service
	 *
	 * @param BaseModel $model
	 * @return mixed
	 */
	protected function makeServiceInstance(BaseModel $model)
	{
		return PaymentFactory::makePaygate($model->paygate_key)->makeService($model);
	}

	/**
	 * Lay thong tin tu id
	 *
	 * @param $id
	 * @return PaymentModel|null
	 */
	public function findById($id)
	{
		return $this->lists()->whereLoose('id', $id)->first();
	}

}