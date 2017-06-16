<?php
class Server_model extends MY_Model {
	
	var $table = 'server';
	public $order	= array( array('sort_order', 'asc'),array('id', 'desc'));

}
?>