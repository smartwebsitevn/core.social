<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class emailsend_to_model extends MY_Model {
	
	var $table 	= 'emailsend_to';
	var $order 	= array('id', 'asc');

	function setData($emailsend_id, $emaillist = array()){
		if(!$emailsend_id)
			return false;

		// xoa email cu
		$this->del_rule(array('emailsend_id' => $emailsend_id));
		if(!$emaillist)
			return true;

		// them vao email
		$data = array();
		foreach($emaillist as $email){
			$item = array();
			$item['email'] = $email;
			$item['emailsend_id'] = $emailsend_id;
			$data[] = $item;
		}
		$this->db->insert_batch($this->table, $data);
	}
}