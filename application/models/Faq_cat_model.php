<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq_cat_model extends MY_Model {
	
	public $table 	= 'faq_cat';
	public $order 	= array('sort_order', 'asc');
	public $translate_auto = TRUE;
	public $translate_fields = array('name');
	
}