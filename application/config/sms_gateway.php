<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Ten nguoi gui
$config['sender'] = $_SERVER['HTTP_HOST'];

// Cu phap
$config['key'] = 'c24';
$config['mods']['deposit'] 	= array('nt', '8765');
$config['mods']['register'] = array('dk', '8065');
$config['mods']['forgot'] 	= array('mk', '8065');
$config['mods']['forgot2'] 	= array('mk2', '8065');
$config['mods']['forgot_otp'] 	= array('otp', '8065');
$config['mods']['forgot_odp'] 	= array('odp', '8065');

// Route
$config['routes'] = [
	'vmg c24 nap([0-9]+) (.+)' => 'c24 nt $1 $2',
	'vmg nap([0-9]+) c24 (.+)' => 'c24 nt $1 $2',
];
