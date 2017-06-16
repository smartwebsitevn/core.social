<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tran_payment_model extends MY_Model {
	
	var $table = 'tran_payment';
	var $key = 'tran_id';
	
	
	/**
	 * Them moi
	 */
	function create($data)
	{
		// Kiem tra id nay da ton tai hay chua
		$id 	= $data[$this->key];
		$info 	= $this->get_info($id, $this->key);
		
		// Neu da ton tai thi cap nhat
		if ($info)
		{
			$this->update($id, $data);
		}
		// Neu chua ton tai thi them moi
		else 
		{
			parent::create($data);
		}
	}
	
	/**
	 * Luu thong tin tra ve tu payment
	 */
	function set($tran_id, $data)
	{
		$_data = array();
		$_data['tran_id'] 	= $tran_id;
		$_data['data'] 		= serialize($data);
		$this->create($_data);
	}
	
	/**
	 * Lay thong tin tra ve tu payment
	 */
	function get($tran_id)
	{
		$info = $this->get_info($tran_id, 'data');
		if ($info)
		{
			return @unserialize($info->data);
		}
		
		return FALSE;
	}
	
}
?>