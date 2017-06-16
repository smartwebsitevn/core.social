<?php namespace App\Invoice\Job;

use App\Invoice\Model\InvoiceModel as InvoiceModel;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Invoice\Library\CreateInvoiceOptions;
use App\Invoice\Library\CreateInvoiceOrderOptions;

class CreateInvoice extends \Core\Base\Job
{
	/**
	 * Options
	 *
	 * @var CreateInvoiceOptions
	 */
	protected $options;


	public static function _t()
	{
		$options = CreateInvoiceOptions::make([
			'amount' => 100,
			'amounts_currency' => [
				8 => 5,
			],
			'invoice_orders' => [
				[
					'service_key' => 'Order',
					'amount' => 70,
				],
				[
					'service_key' => 'Order',
					'amount' => 30,
				],
			],
		]);

		$me = new static($options);

		$v = $me->handle();

		pr($v);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param CreateInvoiceOptions $options
	 */
	public function __construct(CreateInvoiceOptions $options)
	{
		$this->options = $options;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return InvoiceModel
	 */
	public function handle()
	{
		$invoice = $this->createInvoice();

		$invoice_orders = [];

		foreach ($this->options->get('invoice_orders') as $order_options)
		{
			$invoice_orders[] = $this->createInvoiceOrder($invoice, $order_options);
		}

		return $invoice;
	}

	/**
	 * Tao invoice
	 *
	 * @return InvoiceModel
	 */
	protected function createInvoice()
	{
		$data = $this->options->except('invoice_orders');

		$data['secret_key'] = $this->createSecretKey();
		//pr($data);
		return InvoiceModel::create($data);
	}

	/**
	 * Tao InvoiceOrder
	 *
	 * @param InvoiceModel $invoice
	 * @param array        $options
	 * @return InvoiceOrderModel
	 */
	protected function createInvoiceOrder(InvoiceModel $invoice, array $options)
	{
		$options['invoice'] = $invoice;

		$options = new CreateInvoiceOrderOptions($options);

		$data = array_merge($options->except(['invoice']), [
			'invoice_id' => $invoice->id,
			'user_id'    => $invoice->user_id,
		]);

		return InvoiceOrderModel::create($data);
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