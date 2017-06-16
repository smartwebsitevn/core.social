<?php

/**
 * View
 */
$this->register('view', function($invoice_order)
{
	$invoice = $invoice_order->invoice;

	return macro()->info([
		lang('id')           => $invoice_order->id,
		lang('type')         => $invoice_order->service_name,
		lang('desc')         => implode('<br>', (array) $invoice_order->order_desc),
		lang('tran_status')  => $this->macro->tran_status($invoice),
		lang('order_status') => macro()->status_color($invoice_order->order_status, $invoice_order->order_status_name),
		lang('amount')       => $invoice_order->{'format:amount'},
		lang('payment')      => $this->macro->tran_payment($invoice_order),
		lang('created')      => $invoice_order->{'format:created,full'},
	]);
});



/**
 * Tran status
 */
$this->register('tran_status', function($invoice){ ob_start(); ?>

<?php
	$status = $invoice->tran_status;

	echo macro()->status_color($status, lang('tran_status_'.$status));

	if ($tran = $invoice->tran)
	{
		echo t('html')->a($tran->{'url:view'}, lang('button_detail'), ['target' => '_blank']);
	}
?>

<?php return ob_get_clean(); });

/**
 * Tran payment
 */
$this->register('tran_payment', function($invoice_order)
{
	$tran = $invoice_order->invoice->tran;

	ob_start(); ?>

	<?php if ($tran && $tran->payment) echo $tran->payment->name ?>

	<p>
		<?php echo t('html')->img(public_url('img/world/'.strtolower($invoice_order->user_country_code).'.gif')); ?>
		<?php echo $invoice_order->user_ip; ?>
	</p>

	<?php return ob_get_clean();
});

/**
 * Make filter
 */
$this->register('make_filter', function(array $args)
{
	$filter 	= $args['filter'];
	$services 	= $args['services'];

	return [

		'data' => $filter,

		'rows' => [

			[
				'param' => 'id',
			],

			/*[
				'param'  => 'service_key',
				'type'   => 'select',
				'name'   => lang('type'),
				'values' => macro('mr::form')->make_options(array_pluck($services, 'name', 'key')),
			],

			[
				'param' => 'key',
				'name'  => lang('key'),
			],*/

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
		'id'           => lang('id'),
		'service_key'  => lang('type'),
		'desc'         => lang('desc'),
		//'tran_status'  => lang('transaction'),
		'order_status' => lang('order'),
		'amount'       => lang('amount'),
		'created'      => lang('created'),
		'action'       => lang('action'),
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
			'id'           => $row->id,
			'service_key'  => $row->service_name,
			'desc'         => implode('<br>', (array) $row->order_desc),
			'tran_status'  => macro()->status_color($invoice->tran_status, lang('tran_status_'.$invoice->tran_status)),
			'order_status' => macro()->status_color($row->order_status, $row->order_status_name),
			'amount'       => $row->{'format:amount'},
			'created'      => $row->{'format:created,time'},
			'action'       => macro('mr::table')->actions_data($row, ['view']),
		];
	}

	return $rows;
});

