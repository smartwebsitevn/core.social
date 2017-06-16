<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Country_group_model extends MY_Model {
	
	var $table 	= 'country_group';
	//var $order 	= array('name', 'asc');
	var $order = array(array('feature', 'desc'),array('name', 'asc'));

}