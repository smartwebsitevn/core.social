<?php namespace App\Withdraw\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Withdraw as WithdrawReason;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Withdraw\Command\CreateWithdrawCommand as CreateWithdrawCommand;
use App\Withdraw\Command\WithdrawAdminCommand as WithdrawAdminCommand;
use App\Withdraw\Model\WithdrawModel as WithdrawModel;
use App\Withdraw\WithdrawFactory as WithdrawFactory;

class WithdrawAdmin extends \Core\Base\Job
{
	/**
	 * Command
	 *
	 * @var WithdrawAdminCommand
	 */
	protected $command;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param WithdrawAdminCommand $command
	 */
	public function __construct(WithdrawAdminCommand $command)
	{
		$this->command = $command;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return WithdrawModel
	 */
	public function handle()
	{
		$withdraw = $this->createInvoiceWithdraw();

		$this->subPurseBalance($withdraw->invoice_order);

		return $withdraw;
	}

	/**
	 * Tru balance cua purse
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	protected function subPurseBalance(InvoiceOrderModel $invoice_order)
	{
		$purse = $this->command->getPurse();

		$purse_amount = $this->command->amount;

		$reason = WithdrawReason::make($invoice_order);

		AccountantFactory::balance()->sub($purse, $purse_amount, $reason);
	}

	/**
	 * Tao invoice withdraw
	 *
	 * @return WithdrawModel
	 */
	protected function createInvoiceWithdraw()
	{
		$invoice = $this->createInvoice();

		$invoice_order = $this->createInvoiceOrder($invoice);

		$withdraw = $this->createWithdraw($invoice_order);

		$withdraw->fill(compact('invoice', 'invoice_order'));

		return $withdraw;
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
			'service_key'   => 'WithdrawAdmin',
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
	 * Tao withdraw
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return WithdrawModel
	 */
	protected function createWithdraw(InvoiceOrderModel $invoice_order)
	{
		$data = array_merge($this->command->data, [
			'invoice_order' => $invoice_order,
			'purse'         => $this->command->getPurse(),
			'method'        => 'admin',
			'amount'  		=> $this->command->amount,
			'desc'          => $this->command->desc,
			'status'        => OrderStatus::COMPLETED,
			'admin_id'      => $this->command->getAdmin()->id,
		]);

		$options = new CreateWithdrawCommand($data);

		return WithdrawFactory::withdraw()->create($options);
	}

}