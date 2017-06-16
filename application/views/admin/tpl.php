<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ------------------------------------------------------
 *  Template Config
 * ------------------------------------------------------
 */

// Cac region cua template
$tpl['regions']['content']['name'] = 'Content';
$tpl['regions']['content']['desc'] = 'Content desc';

// Cac layout cua template
$tpl['layouts']['home']['name'] 		= 'Home';
$tpl['layouts']['home']['regions'] 		= array('content');
$tpl['layouts']['main']['name'] 		= 'Main';
$tpl['layouts']['main']['regions'] 		= array('content');
$tpl['layouts']['login']['name'] 		= 'Login';
$tpl['layouts']['login']['regions'] 	= array('content');

// Layout mac dinh
$tpl['layout'] = 'main';

// Layout cho module
$tpl['layout_mod']['home'] = 'home';
$tpl['layout_mod']['login'] = 'login';

return $tpl;
