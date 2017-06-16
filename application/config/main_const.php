<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*********************************************************
 * Main
 *********************************************************/
// Status type
$config['status'] = array('off', 'on');
foreach ($config['status'] as $k => $v)
{
	$config['status_'.$v] = strval($k);
}

// Verify type
$config['verify'] = array('no', 'yes');
foreach ($config['verify'] as $k => $v)
{
	$config['verify_'.$v] = strval($k);
}

// File type
$config['file'] = array('public', 'private');
foreach ($config['file'] as $k => $v)
{
	$config['file_'.$v] = strval($k);
}
// Email protocols
$config['mail_protocols'] 		= array('phpmail','sendmail','smtp','nencer_mail_api');

// Email protocol
//$config['mail_protocols'] = array('mail', 'sendmail', 'smtp');
foreach ($config['mail_protocols'] as $k => $v)
{
	$config['mail_protocol_'.$v] = $k;
}


// Date formats
$config['date_formats'] = array('%d-%m-%Y','%d/%m/%Y','%m/%d/%Y','%Y/%m/%d'/*,'%F %j, %Y'*/);





/*********************************************************
 * User
 *********************************************************/
// Verify status
$config['user_verifies'] = array('no', 'wait', 'yes');
foreach ($config['user_verifies'] as $k => $v)
{
	$config['user_verify_'.$v] = $k;
}

// Group type
foreach (array('client', 'user', 'teacher') as $k => $v)
{
	$config['user_groups'][$k+1] = $v;
	$config['user_group_'.$v] = $k+1;
}

// Sms
$config['sms_statuss'] = array('pending', 'completed', 'failed');
foreach ($config['sms_statuss'] as $k => $v)
{
	$config['sms_status_'.$v] = $k;
}


// -= product Modified =-
$config['payment_methods'] = array( 'pickup_at_store', 'shipping_home',  'banking','payment',);


// -= product Modified =-
$config['voucher_types'] = array( /*'vip', */'coupon',/* 'buyout'*/);