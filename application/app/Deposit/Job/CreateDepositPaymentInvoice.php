<?php namespace App\Deposit\Job;

use App\Deposit\DepositFactory as DepositFactory;
use App\Deposit\Library\CreateDepositOptions;
use App\Deposit\Model\DepositModel as DepositModel;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Purse\Model\PurseModel as PurseModel;

class CreateDepositPaymentInvoice extends \Core\Base\Job
{
	/**
	 * Purse can nap
	 *
	 * @var PurseModel
	 */
	protected $purse;

	/**
	 * So tien can nap (tinh theo tien te cua vi)
	 *
	 * @var float
	 */
	protected $amount;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param PurseModel $purse
	 * @param float      $amount
	 */
	public function __construct(PurseModel $purse, $amount)
	{
		$this->purse = $purse;
		$this->amount = $amount;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return DepositModel
	 */
	public function handle()
	{
		$invoice = $this->createInvoice();

		$invoice_order = $this->createInvoiceOrder($invoice);

		$deposit = $this->createDeposit($invoice_order);

		$deposit->fill(compact('invoice', 'invoice_order'));

		return $deposit;
	}

	/**
	 * Tao invoice
	 *
	 * @return InvoiceModel
	 */
	protected function createInvoice()
	{
		$amount = currency_convert_amount_default(
			$this->amount,
			$this->purse->currency_id
		);

		$options = new CreateInvoiceOptions([
			'amount'  => $amount,
			'user_id' => $this->purse->user_id,
		]);

		return InvoiceFactory::invoice()->create($options);
	}

	/**
	 * Tao invoice order
	 *
	 * @param InvoiceModel $invoice
	 * @return InvoiceOrderModel
	 */
	protected function createInvoiceOrder(InvoiceModel $invoice)
	{
		$options = new CreateInvoiceOrderOptions([
			'invoice'    	=> $invoice,
			'service_key'   => 'DepositPayment',
			'amount'        => $invoice->amount,
			'order_options' => $this->makeInvoiceOrderOptions(),
		]);

		return InvoiceFactory::invoiceOrder()->create($options);
	}

	/**
	 * Tao order_options luu tru trong invoice_order
	 *
	 * @return array
	 */
	protected function makeInvoiceOrderOptions()
	{
		$purse = $this->purse;

		return array_merge($purse->makeInvoiceOrderOptions(), [
			'amount' => $this->amount,
		]);
	}

	/**
	 * Tao deposit
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return DepositModel
	 */
	protected function createDeposit(InvoiceOrderModel $invoice_order)
	{
		$options = new CreateDepositOptions([
			'invoice_order' => $invoice_order,
			'purse'         => $this->purse,
			'method'        => 'payment',
			'amount'        => $this->amount,
		]);

		return DepositFactory::deposit()->create($options);
	}

}