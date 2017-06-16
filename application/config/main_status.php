<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/*********************************************************
 * Transaction
 *********************************************************/
// Type
$config['tran_types'] = array('order','admin_deposit', 'admin_withdraw', 'refund',
	'deposit', 'withdraw',
	//'send', 'receive',

	// 'deposit_card',  'deposit_bank',  'deposit_sms',
	// 'topup_offline',
);
foreach ($config['tran_types'] as $k => $v)
{
	$config['tran_type_'.$v] = $k;
}

// Status
//$config['tran_statuss'] = array('pending', 'completed', 'failed', 'cancel', 'verify', 'refund','suspended');
$config['tran_statuss'] = array('success', 'pending', 'failed', 'canceled','fraude');
foreach ($config['tran_statuss'] as $k => $v)
{
	$config['tran_status_'.$v] = $k;
}

$config['invoice_statuss'] = array('paid', 'unpaid', 'canceled', 'overdue', 'partial', 'draft');

// don hang
$config['order_types'] = array('admin_deposit', 'admin_withdraw', 'refund',
	'deposit', 'withdraw',
	//'send', 'receive',

	// 'deposit_card',  'deposit_bank',  'deposit_sms',
	// 'topup_offline',
);
$config['order_statuss'] = array('completed', 'pending', 'canceled','processing', 'failed', 'expired','refunded', 'chargeback');
$config['service_statuss'] = array('active', 'inactive', 'canceled','suspended', 'restored', 'deleted','expired','refunded');

// Status
foreach ($config['order_statuss'] as $k => $v)
{
	$config['order_status_'.$v] = $k;
}
