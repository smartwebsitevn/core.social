<?php namespace App\Invoice\Library;

use App\Invoice\InvoiceFactory;
use Core\Support\Arr;
use Core\Support\AttributesAccess;
use TF\Support\Collection;

class InvoiceStats extends AttributesAccess
{
	protected $casts = [
		'amount' => 'float',
		'profit' => 'float',
	];


	/**
	 * Lay service_name
	 *
	 * @return string
	 */
	protected function getServiceNameAttribute()
	{
		$service_key = $this->getAttribute('service_key');

		if ( ! $service_key) return;

		$service = InvoiceFactory::invoiceService($service_key);

		return array_get($service->info(), 'name', $service->key());
	}

	/**
	 * Tao collection
	 *
	 * @param array $list
	 * @return Collection
	 */
	public static function makeCollection(array $list)
	{
		$items = [];

		foreach ($list as $row)
		{
			$items[] = new static(Arr::toArray($row));
		}

		return collect($items);
	}

	/**
	 * Format du lieu
	 *
	 * @param $key
	 * @return string
	 */
	public function format($key)
	{
		switch($key)
		{
			case 'amount':
			case 'profit':
			{
				$amount = $this->getAttribute($key);

				return currency_format_amount_default($amount);
			}
		}
	}
}