<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_deposit_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		if (isset($row->status))
		{
			$row->_status = mod('order')->status_name($row->status);
		}
		
		return $row;
	}

	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function url($row)
	{
		$row->_url_payment = site_url('tran/deposit/payment/'.$row->tran_id);
		
		return $row;
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 * 
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */
	public function can_do($row, $action)
	{
		return mod('order')->can_do($row, $action);
	}
	
	/**
	 * Thuc hien hanh dong
	 * 
	 * @param object|int $row
	 * @param string $action
	 * @return boolean
	 */
	public function action($row, $action)
	{
		if (is_numeric($row))
		{
			$row = $this->_model()->get_info($row);
		}
		
		if ( ! $this->can_do($row, $action))
		{
			return FALSE;
		}
		
		$row = $this->add_info($row);
		
		switch ($action)
		{
			// Kich hoat don hang
			case 'active':
			case 'active_hand':
			{
				return $this->action_active($row, $action);
				
				break;
			}
			
			// Hoan thanh don hang
			case 'completed':
			{
				$this->_model()->update_field($row->tran_id, 'status', mod('order')->status('completed'));
				
				break;
			}
			
			// Huy bo don hang
			case 'cancel':
			{
				$this->_model()->update_field($row->tran_id, 'status', mod('order')->status('cancel'));
				
				break;
			}
			
			// Xoa don hang
			case 'del':
			{
				$this->_model()->del($row->tran_id);
				
				break;
			}
			
			// Lay thong tin
			case 'get':
			{
				return $row;
				
				break;
			}
		}
		
		return TRUE;
	}
	
	/**
	 * Thuc hien kich hoat don hang
	 * 
	 * @param object $row
	 * @param string $action
	 * @return boolean
	 */
	protected function action_active($row, $action)
	{
		// Kiem tra kich hoat tu dong
		if ($action == 'active' && ! $this->can_auto_active())
		{
			return FALSE;
		}
		
		// Lay thong tin tran
		$tran = model('tran')->get_info($row->tran_id);
		
		// Cap nhat user_balance
		$user_balance = model('user')->balance_plus($tran->user_id, $tran->amount);

		// Cap nhat user_balance vao tran
		model('tran')->update_field($row->tran_id, 'user_balance', security_encrypt($user_balance, 'encode'));
		
		// Cap nhat status order
		$this->_model()->update_field($row->tran_id, 'status', mod('order')->status('completed'));
		
		return TRUE;
	}
	
	/**
	 * Kiem tra order type co duoc phep tu dong active hay khong
	 * 
	 * @param string $type
	 * @return boolean
	 */
	public function can_auto_active()
	{
		$setting = (array) mod('order')->setting('auto_active');
		
		return in_array('deposit', $setting, true);
	}
	
}