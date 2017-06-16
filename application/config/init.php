<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Tai file config chinh
$CI =& get_instance();
$CI->config->load('main', TRUE);

// Gan cac gia tri config cho he thong
$config['base_url'] 		= $CI->config->item('base_url', 'main');
$config['encryption_key'] 	= $CI->config->item('encryption_key', 'main');
$config['language'] 		= $CI->config->item('language', 'main');

// Gan mui gio mac dinh
if (function_exists('date_default_timezone_set'))
{
	date_default_timezone_set($CI->config->item('timezone', 'main'));
}
