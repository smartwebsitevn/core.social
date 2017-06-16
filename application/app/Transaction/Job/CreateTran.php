<?php namespace App\Transaction\Job;

use App\Transaction\TranFactory as TranFactory;
use App\Transaction\Library\CreateTranOptions;
use App\Transaction\Library\TranStatus;
use App\Transaction\Model\TranModel as TranModel;

class CreateTran extends \Core\Base\Job
{
	/**
	 * Options
	 *
	 * @var CreateTranOptions
	 */
	protected $options;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param CreateTranOptions $options
	 */
	public function __construct(CreateTranOptions $options)
	{
		$this->options = $options;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return TranModel
	 */
	public function handle()
	{
		$data = $this->makeTranData();

		return TranModel::create($data);
	}

	/**
	 * Tao tran data
	 *
	 * @return array
	 */
	protected function makeTranData()
	{
		$invoice = $this->options->get('invoice');
		$payment = $this->options->get('payment');

		$data = $this->options->except(['invoice', 'payment']);

		$data = array_merge($data, [
			'invoice_id' => $invoice->id,
			'amount'     => $invoice->amount,
			'user_id'    => $invoice->user_id,
			'status'     => TranStatus::PENDING,
			'secret_key' => $this->createSecretKey(),
		]);

		if ($payment)
		{
			$data = array_merge($data, TranFactory::tran()->makeTranDataWithPayment($invoice, $payment));
		}

		return $data;
	}

	/**
	 * Tao key bao mat
	 *
	 * @return string
	 */
	protected function createSecretKey()
	{
		return random_string('unique');
	}
}