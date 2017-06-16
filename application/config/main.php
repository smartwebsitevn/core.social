<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Check mcrypt
if ( ! function_exists('mcrypt_encrypt'))
{
    exit('mcrypt not exists');
}
// Include main status
include('main_status.php');
// Include main const
include('main_const.php');

/*
 * ------------------------------------------------------
 *  Main Config
 * ------------------------------------------------------
 */

// Base
$config['base_url']			= 'http://'.$_SERVER['HTTP_HOST'].'/smart/core.social';
$config['encryption_key'] 	= 'c5a15cb929c90063432a18e1d1b2e72e';
$config['admin_folder'] 	= 'admin';
$config['admin_email'] 		= '';

// Database
$config['db']['hostname'] 	= 'localhost';
$config['db']['username'] 	= 'root';
$config['db']['password'] 	= '';
$config['db']['database'] 	= 'smart_core_social';

// Kich hoat da ngon ngu
$config['language_multi'] 	= false;

// Kich hoat da tien te
$config['currency_multi'] 	= false;


/*
 * ------------------------------------------------------
 *  ========= Cac tham so cau hinh tu duoi tro di co the se duoc ghi de boi thiet lap tu CSDL ==========
 * ------------------------------------------------------
 */
$config['server_ip'] 		= '127.0.0.1';

$config['language']			= 'vi';

$config['timezone'] 		= 'Asia/Ho_Chi_Minh';


// Kich hoat seo url
$config['seo_url'] 			= TRUE;

// Uri su dung https (*: Tat ca su dung https)
$config['uri_https'] 		= array();


// Log
$config['log_access'] = TRUE;
$config['log_activity'] = TRUE;
$config['log_user_balance'] = TRUE;

// Limit
$config['list_limit'] 		= 36;
$config['list_limit_short'] = 10;
$config['list_auto_limit'] 	= 10;



// Thoi han hieu luc cua url action
$config['url_action_expire'] = 1*24*60*60;

// Date format
$config['date_format']			= '%d/%m/%Y';
$config['date_format_time']		= '%d/%m/%Y - %H:%i';
$config['date_format_full']		= '%d/%m/%Y - %H:%i:%s';

// Date format display
$config['date_format_display']			= '%d-%m-%Y';
$config['date_format_display_time']		= '%d-%m-%Y - %H:%i';
$config['date_format_display_full']		= '%d-%m-%Y - %H:%i:%s';

// Cookie
$config['cookie_expire'] 		= 30*24*60*60;
$config['cookie_expire_login']	= 30*24*60*60;

// Cache
$config['cache_expire'] 		= 24*60*60;
$config['cache_expire_short'] 	= 60*60;
$config['cache_expire_long'] 	= 30*24*60*60;

// IP time out
$config['ip_time_out']			= 24*60*60;

// Thong tin cua payment, card co luu trong data hay khong
$config['payment_setting_in_data'] 	= TRUE;
$config['card_setting_in_data'] 	= TRUE;



// loai captcha
$config['captcha_type'] = 'google'; // mac dinh la system, co the thay doi trong admin
$config['captcha_types'] = array('system', 'google');
// Google captcha
$config['captcha_google_api_url']     = 'https://www.google.com/recaptcha/api/siteverify';
$config['captcha_google_secret_key']  = '6LcfUA4TAAAAAEHJAUi6hr8g7gRttAVSPhwAzYgb';
$config['captcha_google_site_key']    = '6LcfUA4TAAAAAJ7oJw2Puu4ZUYwz28noYIedEXf2';
/*
 * ------------------------------------------------------
 *  Upload config
 * ------------------------------------------------------
 */
$config['upload']['path'] 					= realpath(APPPATH.'../').'/';
$config['upload']['folder'] 				= 'upload';
$config['upload']['overwrite'] 				= FALSE;
$config['upload']['encrypt_name'] 			= FALSE;
$config['upload']['allowed_types'] 			=  'gif|jpg|png|jpeg|txt|doc|rar|zip|flv|swf';

$config['upload']['max_size'] 				= 1024*5;
$config['upload']['max_size_admin'] 		= 1024*50;
//$config['upload']['max_chunk_size'] 		= 1024*50;

$config['upload']['img']['allowed_types'] 	= 'gif|jpg|jpeg|png|svg';
$config['upload']['img']['maintain_ratio'] 	= FALSE;
$config['upload']['img']['max_width'] 		= '3000';
$config['upload']['img']['max_height'] 		= '3000';
$config['upload']['img']['thumb_width'] 	= '200';
$config['upload']['img']['thumb_height'] 	= '270';
$config['upload']['img']['resize_width'] 	= '';
$config['upload']['img']['resize_height'] 	= '';

$config['upload']['img']['thumb1_width'] 	= '';
$config['upload']['img']['thumb1_height'] 	= '';
$config['upload']['img']['thumb2_width'] 	= '';
$config['upload']['img']['thumb2_height'] 	= '';
$config['upload']['img']['thumb3_width'] 	= '';
$config['upload']['img']['thumb3_height'] 	= '';
$config['upload']['img']['thumb4_width'] 	= '';
$config['upload']['img']['thumb4_height'] 	= '';
$config['upload']['img']['thumb5_width'] 	= '';
$config['upload']['img']['thumb5_height'] 	= '';

$config['upload']['server']['status'] 			= false;
$config['upload']['server']['save_on_local'] 			= false;
$config['upload']['server']['url'] 			= 'http://'.$_SERVER['HTTP_HOST'].'/php/ftp/';
$config['upload']['server']['hostname'] 	= $_SERVER['HTTP_HOST'];
$config['upload']['server']['username'] 	= 'root';
$config['upload']['server']['password'] 	= '';


/*
 * ------------------------------------------------------
 *   Duong dan den database index
 * ------------------------------------------------------
 */

$config['data_index'] 		= './data';