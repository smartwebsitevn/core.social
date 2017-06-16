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
		lang('customer')     => $invoice->user_id
									? t('html')->a($invoice->user->{'adminUrl:view'}, $invoice->user->name, ['target' => '_blank'])
									: $invoice->customer_name,
									
		lang('tran_status')  => $this->macro->tran_status($invoice),
		lang('order_status') => macro()->status_color($invoice_order->order_status, $invoice_order->order_status_name),
		lang('amount')       => $invoice_order->{'format:amount'},
		lang('profit')       => $invoice_order->{'format:profit'},
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
 * Make columns
 */
$this->register('make_columns', function()
{
	return [
	    'id'               => lang('id'),
	    //'amount'           => lang('amount'),
		'title'             => lang('title'),
		'user'             => lang('user'),
		'device'           => lang('device'),
		'status'           => lang('status'),
		'created'           => lang('created'),
		'expire'           => lang('expire'),
		'action'           => lang('action'),
	];
});

/**
 * Make rows
 */
$this->register('make_rows', function($list)
{
	$rows = [];

	$_data_status = function($row){
		if($row->expire_to < now())
			$status =macro()->status_color('expired') ;
		else
			$status = macro()->status_color($row->status, lang('service_status_' . $row->status));


		return $status.='<p style="margin-top:10px"><b>'.lang('last_update_status').': </b>'.get_date($row->last_update_status, 'full').(($row->admin_update) ? "<b style='color:red'> - ".$row->admin_update."</b>" : "").'</p>';

	};
	foreach ($list as $row)
	{
		$invoice = $row->invoice;

		//$action = '<a class="btn btn-primary btn-xs" title="'.lang('view').'" href="'.admin_url('service_order/view/'.$row->id).'">'.lang('view').'</a>';
		$action = '<a class="btn btn-primary btn-xs lightbox" title="'.lang('edit').'" href="'.admin_url('service_order/edit/'.$row->id).'">'.lang('edit').'</a>';
		
	    
		if($row->status != 'suspended' && $row->expire_to > now())
		{
		    $action .= '<a class="btn btn-warning btn-xs  verify_action" title="'.lang('suspend').'" notice="'.lang("you_definitely_want_to_lock_this_service").'" href="" _url="'.admin_url('service_order/suspend/'.$row->id).'">'.lang('suspend').'</a>';
		}
	$action .= '<a class="btn btn-danger btn-xs  verify_action" title="'.lang('button_delete').'" notice="'.lang("notice_confirm_del").'" href="" _url="'.admin_url('service_order/del/'.$row->id).'">'.lang('button_delete').'</a>';
		/*
		if(mod('service_order')->can_do($row, 'renew'))
		{
		    $action .= '<p style="margin-top:8px"><a href="'. admin_url('service_order/renew/'.$row->id) .'" class="btn btn-success btn-xs lightbox" title="'.lang('renew').'" >'.lang('renew').'</a></p>';
		}
		*/

		$rows[] = [
			'id'              => $row->id,
			'title'              => $row->title,
			'user'     => implode('<br>', array_filter($row->user
				? [$row->user->username, $row->user->email, $row->user->phone]
				: array_only($invoice->info_contact, ['name', 'phone']
			))),
			'device'          =>$row->device ,
			'status'          => $_data_status($row),
			'created'          => ($row->_created_full),
			//'expire'          => ($row->expire_to > 0) ? $row->{'format:expire_to'}.'<br/>'.$row->{'format:expire_from'} : macro()->status_color('expired'),
			'expire'          => $row->{'format:expire_to'},
			'action'       => $action,// macro('mr::table')->actions_data($row, ['view', 'edit', 'suspend']),
		];
	}

	return $rows;
});
