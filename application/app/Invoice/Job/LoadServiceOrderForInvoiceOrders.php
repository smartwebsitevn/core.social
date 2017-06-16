<?php namespace App\Invoice\Job;

use App\Invoice\InvoiceFactory as InvoiceFactory;

class LoadServiceOrderForInvoiceOrders extends \Core\Base\Job
{
	/**
	 * Danh sach InvoiceOrders
	 *
	 * @var array
	 */
	protected $invoice_orders;

	/**
	 * Danh sach services (bao gom orders)
	 *
	 * @var array
	 */
	protected $services_orders;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $invoice_orders
	 */
	public function __construct(array $invoice_orders)
	{
		$this->invoice_orders = $invoice_orders;
	}

	/**
	 * Thuc hien xu ly
	 *
	 * @return array
	 */
	public function handle()
	{
		foreach ($this->invoice_orders as $invoice_order)
		{
			$invoice_order->service_order = $this->findServiceOrder($invoice_order->service_key, $invoice_order->id);
		}

		return $this->invoice_orders;
	}

	/**
	 * Lay service order tuong ung voi invoice_order_id
	 *
	 * @param string $service_key
	 * @param int $invoice_order_id
	 * @return mixed|null
	 */
	protected function findServiceOrder($service_key, $invoice_order_id)
	{
		$orders = array_get($this->getServicesOrders(), $service_key, []);

		return collect($orders)->whereLoose('invoice_order_id', $invoice_order_id)->first();
	}

	/**
	 * Lay danh sach services (bao gom orders)
	 *
	 * @return array
	 */
	protected function getServicesOrders()
	{
		if (is_null($this->services_orders))
		{
			$this->services_orders = [];

			$services = $this->groupInvoiceOrdersByService();

			foreach ($services as $service_key => $invoice_orders)
			{
				$this->services_orders[$service_key] = $this->findServiceOrders($service_key, $invoice_orders);
			}
		}

		return $this->services_orders;
	}

	/**
	 * Nhom invoice_orders boi service
	 *
	 * @return array
	 */
	protected function groupInvoiceOrdersByService()
	{
		return collect($this->invoice_orders)->groupBy('service_key')->all();
	}

	/**
	 * Lay danh sach service order
	 *
	 * @param string $service_key
	 * @param array  $invoice_orders
	 * @return array
	 */
	protected function findServiceOrders($service_key, array $invoice_orders)
	{
		return InvoiceFactory::invoiceService($service_key)->findOrders($invoice_orders);
	}
}