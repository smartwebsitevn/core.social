<?php

/**
 * View
 */
$this->register('view', function($invoice)
{
	return macro()->info([
		lang('id')          => $invoice->id,
		lang('desc')        => $invoice->title . $invoice->desc,
		// lang('tran_status')  => $this->macro->tran_status($invoice),
		lang('status') 		=> macro()->status_color( $invoice->status, lang('invoice_status_'.$invoice->status) ),
		lang('amount')      => $invoice->{'format:amount'},
		lang('created')     => $invoice->{'format:created,full'},
	]);
});


/**
 * Make filter
 */
$this->register('make_filter', function(array $args)
{
	$filter 	= $args['filter'];

	return [

		'data' => $filter,

		'rows' => [

			[
				'param' => 'id',
			],

			[
				'param' => 'created',
				'type'  => 'date',
				'name'  => lang('from_date'),
			],

			[
				'param' => 'created_to',
				'type'  => 'date',
				'name'  => lang('to_date'),
			],

		],

	];
});


/**
 * Make columns
 */
$this->register('make_columns', function()
{
	return [
		'id'           	=> lang('id'),
		'status' 		=> lang('order'),
		'amount'       	=> lang('amount'),
		// 'fee_shipping'  => lang('fee_shipping'),
		// 'fee_tax'      	=> lang('fee_tax'),
		// 'shipping'      => lang('shipping'),
		'payment'      	=> lang('payment'),
		'created'      	=> lang('created'),
		'action'       	=> lang('action'),
	];
});


/**
 * Make rows
 */
$this->register('make_rows', function($list)
{
	$rows = [];

	foreach ($list as $row)
	{
		$invoice = $row->invoice;

		$rows[] = [
			'id'           => $row->_id,
			'status' 		=> macro('mr::meta')->status_color( $row->status, lang('invoice_status_'.$row->status ) ),
			'amount'       	=> $row->{'format:amount'},
			// 'fee_shipping'  => $row->{'format:fee_shipping'},
			// 'fee_tax'       => $row->{'format:fee_tax'},
			// 'shipping'      => $row->_shipping_name,
			'payment'       => lang($row->_payment_name),
			'created'      	=> $row->{'format:created,time'},
			'action'       	=> macro('mr::table')->actions_data($row, ['view']),
		];
	}

	return $rows;
});

