<?php namespace App\Invoice\Library\InvoiceService;

use Core\Support\OptionsAccess;

class Order extends OptionsAccess
{
	protected $config = [

		'invoice_order_id' => [
			'required' => true,
		],

		'status' => [
			'required' => true,
			'cast' => 'string',
		],

		'status_label' => [
			'cast' => 'string',
		],

	];

	/**
	 * Lay status_label
	 *
	 * @param string $value
	 * @return string
	 */
	protected function getStatusLabelOption($value)
	{
		return $value ?: lang('order_status_'.$this->get('status'));
	}
}