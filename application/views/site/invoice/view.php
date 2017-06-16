<div class="main-content">  
    <div class="container">
<?php
	$table = [];

	$table['title'] = lang('title_invoice_order_list');

	$table['columns'] = [
		'count'           => lang('count'),
		'desc'         => lang('desc'),
		'tran_status'  => lang('tran_status'),
		'order_status' => lang('status'),
		// 'qty'       	=> lang('quan'),
		'amount'       => lang('amount'),
		// 'fee_tax'       => lang('fee_tax'),
//		'created'      => lang('created'),
		'action'       => lang('action'),
	];

	$table['rows'] = [];

	$count = 1;
	foreach ($invoice->invoice_orders as $row)
	{
		$invoice = $row->invoice;
		$tmp = mod('product')->url( (object)array( 'name' => $row->title, 'id' => $row->product_id ) );

		$table['rows'][] = [
			'count'           => $count,
			'desc'         => '<a href="' . $tmp->_url_view . '" ><strong>' . $row->title . '</strong></a>' .
			implode('<br>', (array) $row->order_desc),
			'tran_status'  	=> macro()->status_color($invoice->tran_status, lang('tran_status_'.$invoice->tran_status)),
			'order_status' 	=> macro()->status_color($row->order_status, $row->order_status_name),
			// 'qty'      		=> $row->qty,
			'amount'       	=> $row->{'format:amount'},
			// 'fee_tax'       => $row->{'format:fee_tax'},
			'created'      	=> $row->{'format:created,time'},
			'action'       	=> t('html')->a($row->{'url:view'}, lang('button_view'), ['class' => 'btn btn-success btn-xs']),
		];
		$count++;
	}

	echo macro('mr::table')->table($table);
?>
	</div>
</div>