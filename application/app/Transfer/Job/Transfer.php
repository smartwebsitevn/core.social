<?php namespace App\Transfer\Job;

use App\Accountant\AccountantFactory;
use App\Accountant\ChangeBalanceReason\Send as SendReason;
use App\Accountant\ChangeBalanceReason\Receive as ReceiveReason;
use App\Invoice\InvoiceFactory;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Model\InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel;
use App\Transaction\Library\TranStatus;
use App\Transfer\Command\TransferCommand;
use App\Transfer\Model\TransferModel;

class Transfer extends \Core\Base\Job
{
	/**
	 * Doi tuong TransferCommand
	 *
	 * @var TransferCommand
	 */
	protected $command;


	public static function _t()
	{
		$command = new TransferCommand([
			'sender' => \App\User\Model\UserModel::find(1),
			'sender_purse' => \App\Purse\Model\PurseModel::find(2),
			'receiver_purse' => \App\Purse\Model\PurseModel::find(5),
			'amount' => 10,
			'desc' => 'abc',
		]);

		$me = new static($command);

		$v = $me->handle();

		pr($v, 0);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param TransferCommand $command
	 */
	public function __construct(TransferCommand $command)
	{
		$this->command = $command;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return TransferModel
	 */
	public function handle()
	{
		$transfer = $this->createInvoiceTransfer();

		$this->subSenderPurseBalance($transfer->send_invoice_order);

		$this->addReceiverPurseBalance($transfer->receive_invoice_order);

		return $transfer;
	}

	/**
	 * Tao cac invoice transfer
	 *
	 * @return TransferModel
	 */
	protected function createInvoiceTransfer()
	{
		$send_invoice = $this->createSendInvoice();

		$send_invoice_order = $this->createSendInvoiceOrder($send_invoice);

		$receive_invoice = $this->createReceiveInvoice();

		$receive_invoice_order = $this->createReceiveInvoiceOrder($receive_invoice);

		$transfer = $this->createTransfer($send_invoice_order, $receive_invoice_order);

		$transfer->fill(compact('send_invoice_order', 'receive_invoice_order'));

		return $transfer;
	}
	
	/**
	 * Tao invoice gui
	 *
	 * @return InvoiceModel
	 */
	protected function createSendInvoice()
	{
		$amount = currency_convert_amount_default(
			$this->command->send_amount,
			$this->command->getSendCurrency()->id
		);

		$options = new CreateInvoiceOptions([
			'amount'  => $amount,
			'status'  => InvoiceStatus::PAID,
			'user_id' => $this->command->getSenderPurse()->user_id,
		]);

		return InvoiceFactory::invoice()->create($options);
	}

	/**
	 * Tao invoice order gui
	 *
	 * @param InvoiceModel $send_invoice
	 * @return InvoiceOrderModel
	 */
	protected function createSendInvoiceOrder(InvoiceModel $send_invoice)
	{
		$options = new CreateInvoiceOrderOptions([
			'invoice'    	=> $send_invoice,
			'service_key'   => 'TransferSend',
			'amount'        => $send_invoice->amount,
			'order_status'  => TranStatus::SUCCESS,
			'order_options' => $this->makeInvoiceOrderOptions(),
		]);

		return InvoiceFactory::invoiceOrder()->create($options);
	}

	/**
	 * Tao invoice nhan
	 *
	 * @return InvoiceModel
	 */
	protected function createReceiveInvoice()
	{
		$amount = currency_convert_amount_default(
			$this->command->receive_amount,
			$this->command->getReceiveCurrency()->id
		);

		$options = new CreateInvoiceOptions([
			'amount'  => $amount,
			'status'  => InvoiceStatus::PAID,
			'user_id' => $this->command->getReceiverPurse()->user_id,
		]);

		return InvoiceFactory::invoice()->create($options);
	}

	/**
	 * Tao invoice order nhan
	 *
	 * @param InvoiceModel $receive_invoice
	 * @return InvoiceOrderModel
	 */
	protected function createReceiveInvoiceOrder(InvoiceModel $receive_invoice)
	{
		$options = new CreateInvoiceOrderOptions([
			'invoice'    	=> $receive_invoice,
			'service_key'   => 'TransferReceive',
			'amount'        => $receive_invoice->amount,
			'order_status'  => TranStatus::SUCCESS,
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
		$sender_purse = $this->command->getSenderPurse();

		$receiver_purse = $this->command->getReceiverPurse();

		return [

			'sender_purse_id'     => $sender_purse->id,
			'sender_purse_number' => $sender_purse->number,
			'sender_id'           => $sender_purse->user_id,
			'sender_username'     => $sender_purse->user->username,
			'sender_email'        => $sender_purse->user->email,
			'sender_phone'        => $sender_purse->user->phone,
			'send_currency_id'    => $sender_purse->currency_id,
			'send_amount'         => $this->command->send_amount,

			'receiver_purse_id'     => $receiver_purse->id,
			'receiver_purse_number' => $receiver_purse->number,
			'receiver_id'           => $receiver_purse->user_id,
			'receiver_username'     => $receiver_purse->user->username,
			'receiver_email'        => $receiver_purse->user->email,
			'receiver_phone'        => $receiver_purse->user->phone,
			'receive_currency_id'   => $receiver_purse->currency_id,
			'receive_amount'        => $this->command->receive_amount,

			'amount'                => $this->command->amount,
			'fee'                   => $this->command->fee,
			'net'                   => $this->command->net,
			'currency_id'           => $sender_purse->currency_id,

		];
	}

	/**
	 * Tao transfer
	 *
	 * @param InvoiceOrderModel $send_invoice_order
	 * @param InvoiceOrderModel $receive_invoice_order
	 * @return TransferModel
	 */
	protected function createTransfer(InvoiceOrderModel $send_invoice_order, InvoiceOrderModel $receive_invoice_order)
	{
		$data = $this->command->only([
			'send_amount', 'receive_amount', 'amount', 'fee', 'net', 'desc'
		]);

		$data = array_merge($data, [
			'sender_id'                => $this->command->getSenderPurse()->user_id,
			'sender_purse_id'          => $this->command->getSenderPurse()->id,
			'send_invoice_order_id'    => $send_invoice_order->id,
			'receiver_id'              => $this->command->getReceiverPurse()->user_id,
			'receiver_purse_id'        => $this->command->getReceiverPurse()->id,
			'receive_invoice_order_id' => $receive_invoice_order->id,
			'status'                   => TranStatus::SUCCESS,
			'currency_id'              => $this->command->getSendCurrency()->id,

		]);

		return TransferModel::create($data);
	}

	/**
	 * Tru balance cua purse gui
	 *
	 * @param InvoiceOrderModel $send_invoice_order
	 */
	protected function subSenderPurseBalance(InvoiceOrderModel $send_invoice_order)
	{
		$purse = $this->command->getSenderPurse();

		$purse_amount = $this->command->send_amount;

		$reason = SendReason::make($send_invoice_order);

		AccountantFactory::balance()->sub($purse, $purse_amount, $reason);
	}

	/**
	 * Cong balance cho purse nhan
	 *
	 * @param InvoiceOrderModel $receive_invoice_order
	 */
	protected function addReceiverPurseBalance(InvoiceOrderModel $receive_invoice_order)
	{
		$purse = $this->command->getReceiverPurse();

		$purse_amount = $this->command->receive_amount;

		$reason = ReceiveReason::make($receive_invoice_order);

		AccountantFactory::balance()->add($purse, $purse_amount, $reason);
	}
}