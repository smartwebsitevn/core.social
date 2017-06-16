<?php
$mr = [];

$_data = function() use ($order, $mr)
{

	//return array('type', 'bank', 'acc_name', 'acc', 'amount', 'date', 'desc');
	$invoice_order = $order->invoice_order;
	$detail =(object)$invoice_order->order_options;
	$body = macro()->info([
		lang('id')               => $invoice_order->id,
		lang('transfer_type')             => $detail->type,
		lang('transfer_bank')             => $detail->bank,
		lang('transfer_acc_name')             => $detail->acc_name,
		lang('transfer_acc')             => $detail->acc,
	    
		lang('amount')           => $invoice_order->{'format:amount'},
		lang('transfer_desc') => $detail->desc,
		lang('order_status')     => macro()->status_color($order->status, lang('order_status_' . $order->status)),
		lang('created')          => $invoice_order->{'format:created, full'},
	]);

	return macro('mr::box')->box([
		'title'   => lang('title_deposit_bank'),
		'content' => $body,
	]);
};

?>

<div class="row">

	<div class="col-md-12">

		<?php echo $_data(); ?>

	</div>

</div>