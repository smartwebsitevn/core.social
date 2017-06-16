<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_tran_model extends MY_Model {
	
	public $table = 'admin_tran';
	public $key = 'tran_id';
	
	public $relations = array(
		'admin' => 'one',
	);
	
}