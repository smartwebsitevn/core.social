<?php namespace App\DepositCard\Job;

use App\Accountant\AccountantFactory;
use App\Accountant\ChangeBalanceReason\Deposit as DepositReason;
use App\Deposit\Command\DepositCardCommand;
use App\Deposit\Library\CardDeposit;
use App\Deposit\Model\CardTypeModel;
use App\Deposit\Model\DepositCardModel;
use App\Invoice\InvoiceFactory;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\CreateInvoiceOrderOptions;
use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel;
use App\Purse\Model\PurseModel;

class DepositCard extends \Core\Base\Job
{
	/**
	 * Command
	 *
	 * @var DepositCardCommand
	 */
	protected $command;


	public static function _t()
	{
		$command = new DepositCardCommand([
			'purse' => PurseModel::find(1),
			'amount' => 8000,
			'card' => new CardDeposit([
				'type'   => CardTypeModel::find(1),
				'code'   => random_string('numeric', 14),
				'serial' => random_string('numeric', 10),
				'amount' => 10000,
			]),
//			'provider' => 'maxpay',
			'data' => [
				'request_id' => now(),
			],
		]);

//		pr($command->only(['provider', 'fee', 'profit_amount']));

		$me = new static($command);

		$v = $me->handle();

		pr($v, 0);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param DepositCardCommand $command
	 */
	public function __construct(DepositCardCommand $command)
	{
		$this->command = $command;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return DepositCardModel
	 */
	public function handle()
	{
		$deposit_card = $this->createInvoiceDepositCard();

		$this->addPurseBalance($deposit_card->invoice_order);

		return $deposit_card;
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
	 * Tao invoice deposit_card
	 *
	 * @return DepositCardModel
	 */
	protected function createInvoiceDepositCard()
	{
		$invoice = $this->createInvoice();

		$invoice_order = $this->createInvoiceOrder($invoice);

		$deposit_card = $this->createDepositCard($invoice_order);

		$deposit_card->fill(compact('invoice', 'invoice_order'));

		return $deposit_card;
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
			'service_key'   => 'DepositCard',
			'amount'        => $invoice->amount,
			'profit'        => $this->command->profit_amount,
			'amount_par'  	=> $this->command->getCard()->amount,
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

		$card = $this->command->getCard();

		return array_merge($purse->makeInvoiceOrderOptions(), [
			'amount'         => $this->command->amount,
			'card_type_id'   => $card->type->id,
			'card_type_key'  => $card->type->key,
			'card_type_name' => $card->type->name,
			'card_code'      => $card->code,
			'card_serial'    => $card->serial,
			'card_amount'    => $card->amount,
			'provider'       => $this->command->provider,
		]);
	}

	/**
	 * Tao deposit_card
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return DepositCardModel
	 */
	protected function createDepositCard(InvoiceOrderModel $invoice_order)
	{
		$purse = $this->command->getPurse();
		$card = $this->command->getCard();
		$data = array_merge($this->command->data, [
			'invoice_id'       => $invoice_order->invoice_id,
			'invoice_order_id' => $invoice_order->id,
			'purse_id'         => $purse->id,
			'provider'         => $this->command->provider,
			'card_type_id'     => $card->type->id,
			'card_type_key'    => $card->type->key,
			'card_code'        => $card->code,
			'card_serial'      => $card->serial,
			'card_amount'      => $card->amount,
			'amount'           => $this->command->amount,
			'fee'              => $this->command->fee,
			'profit'           => $card->profit,
			'profit_amount'    => $this->command->profit_amount,
			'user_id'          => $purse->user_id,
			'currency_id'      => $purse->currency_id,
			'status'           => OrderStatus::COMPLETED,
		]);

		return DepositCardModel::create($data);
	}

}