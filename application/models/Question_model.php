<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class question_model extends MY_Model {
	
	var $table 	= 'question';
	var $order = array('question.sort_order', 'asc');
	
}