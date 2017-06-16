<?php

use App\Invoice\InvoiceFactory;
use App\Invoice\Library\InvoiceStats;

class Invoice_stats_widget extends MY_Widget
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/invoice/stats');
	}

	/**
	 * Thong ke theo cac thoi diem
	 *
	 * @param array $opts
	 */
	public function times(array $opts = [])
	{
		$list = [];

		foreach ($this->makeTimeRanges() as $type => $range)
		{
			$list[$type] = InvoiceFactory::stats()->revenueOrder(['created' => $range]);
		}

		$list['total_revenue'] = InvoiceFactory::stats()->revenueOrder();

		$this->data['list'] = $list;

		$this->_display($this->_make_view(array_get($opts, 'view'), __FUNCTION__));
	}

	/**
	 * Tao time ranges
	 */
	protected function makeTimeRanges()
	{
		$time = get_time_info();

		$ranges = [
			'today'      => get_date(),
			'yesterday'  => get_date(add_time(now(), ['d' => -1])),
			'this_month' => $time['m'] . '-' . $time['y'],
			'last_month' => ($time['m'] - 1) . '-' . $time['y'],
		];

		foreach ($ranges as &$range)
		{
			$range = get_time_between($range);
		}

		return $ranges;
	}

	/**
	 * Thong ke chi tiet theo cac service
	 *
	 * @param array $opts
	 */
	public function services(array $opts = [])
	{
		$services = [
			'ProductOrder',
			//'ServiceOrder',
			//'DepositCard',
			//'DepositAdmin',
			//'WithdrawAdmin',
		];

		$range = get_time_between(get_date());

		$stats = InvoiceFactory::stats()->services($services, ['created' => $range]);

		$list = [];

		foreach ($services as $service_key)
		{
			$row = $stats->whereLoose('service_key', $service_key)->first();

			$list[] = $row ?: new InvoiceStats(compact('service_key'));
		}

		$this->data['list'] = collect($list);

		$this->_display($this->_make_view(array_get($opts, 'view'), __FUNCTION__));
	}
}