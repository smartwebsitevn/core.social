<?php namespace App\Deposit\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Deposit as DepositReason;
use App\Deposit\DepositFactory as DepositFactory;
use App\Deposit\Library\CreateDepositOptions;
use App\Deposit\Model\DepositModel as DepositModel;
use App\Deposit\Command\DepositAdminCommand as DepositAdminCommand;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Invoice\Library\InvoiceStatus;

class DepositAdmin extends \Core\Base\Job
{
	/**
	 * Command
	 *
	 * @var DepositAdminCommand
	 */
	protected $command;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param DepositAdminCommand $command
	 */
	public function __construct(DepositAdminCommand $command)
	{
		$this->command = $command;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return DepositModel
	 */
	public function handle()
	{
		$deposit = $this->createInvoiceDeposit();

		$this->addPurseBalance($deposit->invoice_order);

		return $deposit;
	}

	/**
	 * Thuc hien cong tien cho purse
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	protected function addPurseBalance(InvoiceOrderModel $invoice_order)
	{
		$purse = $this->command->getPurse();

		$purse_amount = $this->command->amount;

		$reason = DepositReason::make($invoice_order);

		AccountantFactory::balance()->add($purse, $purse_amount, $reason);
	}

	/**
	 * Tao invoice deposit
	 *
	 * @return DepositModel
	 */
	protected function createInvoiceDeposit()
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
			$this->command->amount,
			$this->command->getPurse()->currency_id
		);

		$options = new CreateInvoiceOptions([
			'amount'  => $amount,
			'status'  => InvoiceStatus::PAID,
			'user_id' => $this->command->getPurse()->user_id,
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
			'service_key'   => 'DepositAdmin',
			'amount'        => $invoice->amount,
			'order_status'  => OrderStatus::COMPLETED,
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
		$purse = $this->command->getPurse();

		return array_merge($purse->makeInvoiceOrderOptions(), [
			'admin_id'       => $this->command->getAdmin()->id,
			'admin_username' => $this->command->getAdmin()->username,
			'amount'         => $this->command->amount,
			'desc'           => $this->command->desc,
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
		$data = array_merge($this->command->data, [
			'invoice_order' => $invoice_order,
			'purse'         => $this->command->getPurse(),
			'method'        => 'admin',
			'amount'        => $this->command->amount,
			'desc'          => $this->command->desc,
			'status'        => OrderStatus::COMPLETED,
			'admin_id'      => $this->command->getAdmin()->id,
		]);

		$options = new CreateDepositOptions($data);

		return DepositFactory::deposit()->create($options);
	}

}