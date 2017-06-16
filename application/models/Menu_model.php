<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends MY_Model {
	
	var $table 	= 'menu';
	var $key 	= 'id';
	var $order	= array('sort_order,id', 'asc');
	

	// --------------------------------------------------------------------
	/**
	 * Lay thong tin tu key
	 *
	 * @param string $key
	 * @return array|bool|object
	 */
	public function get($key)
	{
	    return $this->get_info_rule(compact('key'));
	}
	
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
	{
		switch ($act)
		{
			// Del
			case 'del':
			{
				$where 	= $params[0];
				
				if (isset($where[$this->key]))
				{
					$id = $where[$this->key];
					
					// Xoa item
					$this->load->model('menu_item_model');
					$this->menu_item_model->del_rule(array('menu' => $id));
				}
				
				break;
			}
		}
	}
	
}

