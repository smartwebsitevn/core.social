<?php namespace Modules\Invoice;

use App\Invoice\InvoiceFactory;
use App\Invoice\Model\InvoiceOrderModel;
use App\User\UserFactory;

class Module extends \MY_Module
{
	public $key = 'invoice';

	/**
	 * Lay config cua widget
	 */
	public function widget_get_config()
	{
		$config = parent::widget_get_config();

		$this->widget_set_config_service_key($config);

		return $config;
	}

	/**
	 * Gan config region
	 *
	 * @param array $config
	 */
	protected function widget_set_config_service_key(array &$config)
	{
		$services = InvoiceFactory::invoiceServiceManager()->listInfo();

		$config['user_invoice_orders']['setting']['service_keys']['values'] = array_pluck($services, 'name', 'key');
	}

	/**
	 * Lay thong tin de hien thi widget
	 *
	 * @param object $widget
	 */
	public function widget_run($widget)
	{
		t('lang')->load('modules/invoice/invoice');

		return parent::widget_run($widget);
	}

	/**
	 * Xu ly widget form_card
	 */
	protected function widget_run_user_invoice_orders($widget)
	{
		$user = UserFactory::auth()->user();

		$service_key = $widget->setting['service_keys'];

		$total = min(max(0, (int) $widget->setting['total']), 50);

		$filter = [
			'user_id' => (int) $user->id,
		];

		if ($service_key)
		{
			$filter['service_key'] = $service_key;
		}

		$list = model('invoice_order')->filter_get_list($filter, [
			'order' => ['id', 'desc'],
			'limit' => [0, $total],
			'relation' => 'invoice.tran',
		]);

		foreach ($list as &$row)
		{
			$invoice = (array) $row->invoice;

			$invoice['trans'] = array_pull($invoice, 'tran');

			$row->invoice = (object) $invoice;
		}

		$list = InvoiceOrderModel::makeCollection($list);

		$url_view_all = site_url('invoice_order');//.'?'.http_build_query(compact('service_key'));

		return compact('list', 'url_view_all', 'user');
	}

}
