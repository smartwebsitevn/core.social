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
		lang('desc')         => $invoice_order->title.'<br>'.implode('<br>', (array) $invoice_order->order_desc),
		lang('customer')     => $invoice->user_id
									? t('html')->a($invoice->user->{'adminUrl:view'}, $invoice->user->name, ['target' => '_blank'])
									: $invoice->customer_name,
		lang('tran_status')  => $this->macro->tran_status($invoice),
		lang('order_status') => macro()->status_color($invoice_order->order_status, $invoice_order->order_status_name),
		lang('amount')       => $invoice_order->{'format:amount'},
		//lang('profit')       => $invoice_order->{'format:profit'},
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
		echo t('html')->a($tran->{'adminUrl:view'}, lang('button_detail'), ['target' => '_blank']);
	}
?>

<?php return ob_get_clean(); });


/**
 * Tran payment
 */
$this->register('tran_payment', function($invoice_order)
{
	$trans = $invoice_order->invoice->trans;
	$tran=$trans->first();
	ob_start(); ?>
	<?php

		if (isset( $invoice_order->invoice->_payment_name))
			echo  $invoice_order->invoice->_payment_name;//"--";
		if ($tran && $tran->payment)
			echo $tran->payment->name ;

	?>


	<p>
		<?php echo t('html')->img(public_url('img/world/'.strtolower($invoice_order->user_country_code).'.gif')); ?>
		<?php echo $invoice_order->user_ip; ?>
	</p>

	<?php return ob_get_clean();
});
/**
 * Tran payment
 */
/*
$this->register('tran_payment', function($invoice_order)
{
	$tran = $invoice_order->invoice->tran;

	ob_start(); ?>

	<?php if ($tran && $tran->payment)
			echo $tran->payment->name ;
	else
		//echo  "Chuyển khoản";
		echo  "--";
	?>

	<p>
		<?php echo t('html')->img(public_url('img/world/'.strtolower($invoice_order->user_country_code).'.gif')); ?>
		<?php echo $invoice_order->user_ip; ?>
	</p>

	<?php return ob_get_clean();
});
*/
/**
 * Make columns
 */
$this->register('make_columns', function()
{
	return [
		'id'           => lang('id'),
		'invoice_id'           => lang('invoice'),
		'service_key'  => lang('type'),
		'desc'         => lang('desc'),
		'customer'     => lang('customer'),
		'tran_status'  => lang('transaction'),
		'order_status' => lang('order'),
		'amount'       => lang('amount'),
		//'profit'       => lang('profit'),
		'payment'      => lang('payment'),
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
		//$a=$invoice['info_contact'];
		//pr($invoice->info_contact);
		$info=[];
		if(isset($row->user->username))
			$info=[$row->user->username, $row->user->email, $row->user->phone];
		elseif($invoice->info_contact)
			$info=array_only((array)$invoice->info_contact, ['name','email', 'phone']);
		$rows[] = [
			'id'           => $row->id,
			'invoice_id'           => $invoice->id,
			'service_key'  => $row->service_name,
			//'desc'         => implode('<br>', (array) $row->order_desc),
			'desc'         => implode('<br>', (array) $row->title),
			'customer'     => implode('<br>', $info),

			'tran_status'  => macro()->status_color($invoice->tran_status, lang('tran_status_'.$invoice->tran_status)),

			'order_status' => macro()->status_color($row->order_status, $row->order_status_name),

			'amount'       => $row->{'format:amount'},

			'profit'       => $row->{'format:profit'},

			'payment'      => $this->macro->tran_payment($row),

			'created'      => $row->{'format:created,time'},

			'action'       => macro('mr::table')->actions_data($row, ['view']),

		];
	}

	return $rows;
});
