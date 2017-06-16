<?php namespace App\Withdraw\Job;

use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Withdraw as WithdrawReason;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Invoice\Library\InvoiceStatus;
use App\Withdraw\WithdrawFactory as WithdrawFactory;
use App\Withdraw\Model\WithdrawModel as WithdrawModel;
use App\Withdraw\Command\WithdrawPaymentCommand;
use App\Withdraw\Command\CreateWithdrawCommand as CreateWithdrawCommand;

class WithdrawPayment extends \Core\Base\Job
{
	/**
	 * Thong tin command
	 *
	 * @var WithdrawPaymentCommand
	 */
	protected $command;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param WithdrawPaymentCommand $command
	 */
	public function __construct(WithdrawPaymentCommand $command)
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

		$invoice  = $withdraw->invoice;
		$invoice_order = $withdraw->invoice_order;
		
		$status = $this->subPurseBalance($invoice_order);

		//cập nhật trạng thái đã trừ tiền
		if($status === true)
		{
			model('invoice')->update($invoice->id, ['status'  => InvoiceStatus::PAID]);
		}else{
			//hủy bỏ đơn hàng
			model('withdraw')->update($withdraw->id, ['status'  => 'canceled']);
			model('invoice_order')->update($invoice_order->id, ['order_status'  => 'canceled']);		
		}
		
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
		
		return true;
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
			'status'  => InvoiceStatus::UNPAID,
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
			'service_key'   => 'WithdrawPayment',
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
		$purse = $this->command->getPurse();

		return array_merge($purse->makeInvoiceOrderOptions(), [
			'amount'     => $this->command->amount,
			'payment_id' => $this->command->getPayment()->id,
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
		$data = $this->command->only(['receiver', 'amount', 'fee', 'receive_amount']);

		$data = array_merge($data, [
			'invoice_order'       => $invoice_order,
			'purse'               => $this->command->getPurse(),
			'payment_id'          => $this->command->getPayment()->id,
			'method'              => 'payment',
			'receive_currency_id' => $this->command->getPayment()->currency_id,
		]);

		$command = new CreateWithdrawCommand($data);

		return WithdrawFactory::withdraw()->create($command);
	}
}