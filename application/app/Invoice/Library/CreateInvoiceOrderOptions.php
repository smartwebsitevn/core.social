<?php namespace App\Invoice\Library;

use Core\Support\OptionsAccess;

class CreateInvoiceOrderOptions extends OptionsAccess
{
	protected $config = [

		'invoice' => [
			'required' => true,
		],

		'service_key' => [
			'required' => true,
		],

		'amount' => [
			'required' => true,
			'cast' => 'float',
		],

		'fee_tax' => [
			'cast' => 'float',
		],

		'profit' => [
			'cast' => 'float',
		],

		'amount_par' => [
			'cast' => 'float',
		],

		'order_status' => [
			'default' => OrderStatus::PENDING,
		],

		'order_options' => [
			'default' => [],
			'allowed_types' => 'array',
		],

	];

	/**
	 * Lay amount_par
	 *
	 * @param float $value
	 * @return float
	 */
	protected function getAmountParOption($value)
	{
		return $value ?: $this->get('amount');
	}

	/**
	 * Lay keywords
	 *
	 * @return string
	 */
	protected function getKeywordsOption()
	{
		$order_options = $this->get('order_options');

		$keywords = [];

		foreach (array_values($order_options) as $key)
		{
			$key = str_replace([',', '.'], '', $key);
			$key = trim($key);

			if ($key != '')
			{
			    $keywords[] = $key;
			}
		}

		return ','.implode(',', $keywords).',';
	}

}