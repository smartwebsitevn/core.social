<?php namespace App\Product\Service;

use App\Invoice\Library\OrderStatus;
use App\Product\Model\OrderModel;
use Core\Support\Arr;
use TF\Support\Collection;

class OrderStatsService
{
	/**
	 * Lay thong ke tong
	 *
	 * @param array $filter
	 * @return array
	 */
	public function total(array $filter = [])
	{
		$list = $this->getListStats($filter);

		$default = [
			'amount' => 0,
			'profit' => 0,
		];

		return count($list) ? head($list) : $default;
	}

	/**
	 * Lay thong ke theo types
	 *
	 * @param array $types
	 * @param array $filter
	 * @return Collection
	 */
	public function types(array $types, array $filter = [])
	{
		$filter['type'] = $types;

		$input = [
			'select'   => 'type',
			'group_by' => 'type',
		];

		$list = $this->getListStats($filter, $input);

		return collect($list);
	}

	/**
	 * Lay danh sach thong ke
	 *
	 * @param array $filter
	 * @param array $input
	 * @return array
	 */
	protected function getListStats(array $filter, array $input = [])
	{
		$select = array_filter([
			'SUM(amount) AS amount',
			'SUM(profit) AS profit',
			array_get($input, 'select'),
		]);

		$filter['status'] = OrderStatus::COMPLETED;

		$input['select'] = implode(',', $select);

		$list = (new OrderModel())->newQuery()->filter_get_list($filter, $input);

		foreach ($list as &$row)
		{
			$row = Arr::toArray($row);
		}

		return $list;
	}
}