<?php namespace App\Invoice\Service;

use App\Invoice\InvoiceFactory;
use App\Invoice\Library\InvoiceStatus;
use App\Invoice\Library\InvoiceStats;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel;
use TF\Support\Collection;

class StatsService
{
	public static function _t()
	{
		$me = new static;

//		$v = $me->total();
		$v = $me->services(['TransferReceive']);

		pr($v, 0);
	}

	/**
	 * Lay tong doanh thu ban hang
	 *
	 * @param array $filter
	 * @return InvoiceStats|mixed|null
	 */
	public function revenueOrder(array $filter = [])
	{
		$services = InvoiceFactory::invoiceServiceManager()->listInfo();

		$services = collect($services)->whereLoose('type', ServiceType::ORDER);

		$filter['service_key'] = $services->lists('key');

		$list = $this->getListStats($filter);

		return $list->count() ? $list->first() : new InvoiceStats;
	}

	/**
	 * Lay thong ke theo services
	 *
	 * @param array $service_keys
	 * @param array $filter
	 * @return Collection
	 */
	public function services(array $service_keys, array $filter = [])
	{
		$filter['service_key'] = $service_keys;

		$input = [
			'select'   => 'service_key',
			'group_by' => 'service_key',
		];

		return $this->getListStats($filter, $input);
	}

	/**
	 * Lay danh sach stats
	 *
	 * @param array $filter
	 * @param array $input
	 * @return Collection
	 */
	protected function getListStats(array $filter, array $input = [])
	{
		$select = array_filter([
			'SUM(amount) AS amount',
			'SUM(profit) AS profit',
			array_get($input, 'select'),
		]);

		$filter['invoice_status'] = InvoiceStatus::PAID;

		$input['select'] = implode(',', $select);

		$list = (new InvoiceOrderModel())->newQuery()->filter_get_list($filter, $input);

		return InvoiceStats::makeCollection($list);
	}
}