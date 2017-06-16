<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_gateway extends MY_Controller {
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
		// Tai cac file thanh phan
		$this->lang->load('admin/sms_gateway');
	}
	
	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('install', 'uninstall', 'setting', 'set_default')))
		{
			$this->_action($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}
	
	
/*
 * ------------------------------------------------------
 *  List handle
 * ------------------------------------------------------
 */
	/**
	 * Danh sach
	 */
	function index()
	{
		// Lay danh sach da cai dat
		$list_installed = array();
		$list_data = $this->_model()->get_list_installed();
		
		$actions = array('setting', 'uninstall', 'set_default');
		foreach ($list_data as $v)
		{
			// Neu khong ton tai thi xoa bo
			if ( ! $this->_model()->exists($v))
			{
				$this->_model()->uninstall($v);
				continue;
			}
			
			$row = $this->_model()->get_info($v);
			foreach ($actions as $action)
			{
				$row->{'_url_'.$action} = $this->_url($action.'/'.$row->id);
				$row->{'_can_'.$action} = ($this->_can_do($row->id, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
			
			$list_installed[$row->id] = $row;
		}
		$this->data['list_installed'] = $list_installed;
		
		
		// Lay danh sach chua cai dat
		$list_uninstall = array();
		$list_file = $this->_model()->get_list_file();
		
		$actions = array('install');
		foreach ($list_file as $v)
		{
			if (isset($list_installed[$v]))
			{
				continue;
			}
			
			$row = $this->_model()->get_info($v, 'id, name');
			foreach ($actions as $action)
			{
				$row->{'_url_'.$action} = $this->_url($action.'/'.$row->id);
				$row->{'_can_'.$action} = ($this->_can_do($row->id, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
			
			$list_uninstall[$row->id] = $row;
		}
		$this->data['list_uninstall'] = $list_uninstall;
		
		// Hien thi view
		$this->_display();
	}
	
	
/*
 * ------------------------------------------------------
 *  Action handle
 * ------------------------------------------------------
 */
	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;
		
		// Thuc hien action
		foreach ($ids as $id)
		{
			// Kiem tra ton tai
			if ( ! $this->_model()->exists($id)) continue;
			
			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($id, $action)) continue;
			
			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($id);
		}
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($key, $action)
	{
		switch ($action)
		{
			case 'install':
			{
				return ( ! $this->_model()->installed($key)) ? TRUE : FALSE;
			}
			
			case 'uninstall':
			{
				return ($this->_model()->installed($key)) ? TRUE : FALSE;
			}
			case 'set_default':
			{
			    return ($this->_model()->get_default() != $key) ? TRUE : FALSE;
			}
			case 'test':
			case 'setting':
			{
				return ($this->_model()->installed($key) && count($this->sms_gateway->$key->setting)) ? TRUE : FALSE;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Cai dat
	 */
	function _install($key)
	{
		// Cai dat
		$this->_model()->install($key);
		
		// Chay fun install
		$this->sms_gateway->{$key}->install();
		
		// Gui thong bao
		set_message(lang('notice_update_success'));
		$this->_redirect();
	}
	
	/*
	 * Gan la cong mac dinh
	 */
	function _set_default($key)
	{
	    $this->_model()->set_default($key);
	    
	    // Gui thong bao
	    set_message(lang('notice_update_success'));
	    $this->_redirect();
	}
	
	/**
	 * Go bo
	 */
	function _uninstall($key)
	{
		// Chay fun uninstall
		$this->sms_gateway->{$key}->uninstall();
		
		// Go bo 
		$this->_model()->uninstall($key);
		
		// Gui thong bao
		set_message(lang('notice_update_success'));
		$this->_redirect();
	}
	
	/**
	 * Setting
	 */
	function _setting($key)
	{
		// Chay fun setting
		$this->data = $this->sms_gateway->{$key}->setting();
		
		$temp = (string) array_get($this->data, 'temp');
		$this->_display($temp);
	}
	
	/**
	 * Test
	 */
	function _test($card)
	{
		$this->sms_gateway->{$card}->test();
	}
	
}