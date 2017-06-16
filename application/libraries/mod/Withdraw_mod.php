<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Withdraw_mod extends MY_Mod
{
	/**
	 * Lay setting
	 * 
	 * @param string 	$key
	 * @param mixed 	$default
	 * @return mixed
	 */
	public function setting($key = null, $default = null)
	{
		$setting = module_get_setting($this->_get_mod());
		
		foreach (array(
			'amount_min', 'amount_max',
			'fee_constant', 'fee_percent', 'fee_min', 'fee_max',
		) as $p)
		{
			$setting[$p] = max(0, (float) $setting[$p]);
		}
		
		return array_get($setting, $key, $default);
	}
	
	/**
	 * Lay fee
	 * 
	 * @param float $amount
	 * @return float
	 */
	public function get_fee($amount)
	{
		return get_fee($amount, $this->setting_fee());
	}
	
	/**
	 * Lay fee setting
	 * 
	 * @return array
	 */
	public function setting_fee()
	{
		$setting = array();
		foreach (array('constant', 'percent', 'min', 'max') as $p)
		{
			$setting[$p] = $this->setting('fee_'.$p);
		}
		
		return $setting;
	}

	/**
	 * Lay fee cua order
	 * 
	 * @param float $amount
	 * @param int $bank_id
	 * @return float
	 */
	public function get_fee_order($amount, $bank_id)
	{
		$fee = $this->get_fee($amount);
		
		$fee += mod('bank')->get_fee($bank_id, $amount);
		
		return $fee;
	}
	
	/**
	 * Xac thuc rut tien
	 * 
	 * @param int $user_id
	 * @param int $bank_id
	 * @param float $amount
	 * @return array(status, result)
	 */
	public function valid($user_id, $bank_id, $amount)
	{
		// Khong ton tai user
		$user = model('user')->get_info($user_id);
		if ( ! $user)
		{
			return array(false, 'user_not_exist');
		}
		
		// Khong ton tai bank
		$bank = model('bank')->get_info($bank_id);
		if ( ! $bank)
		{
			return array(false, 'bank_not_exist');
		}
		
		// So tien khong hop le
		$amount = currency_handle_input($amount, true);
		if ( ! $this->valid_amount($amount))
		{
			return array(false, 'amount_invalid');
		}
		
		// User khong du tien thanh toan
		$user->balance = model('user')->balance_get($user->id);
		if ($user->balance < $amount)
		{
			return array(false, 'balance_not_enough');
		}
		
		// Kiem tra so tien user nhan
		$fee = $this->get_fee_order($amount, $bank_id);
		$amount_receive = $amount - $fee;
		if ($amount_receive <= 0)
		{
			return array(false, 'amount_invalid');
		}
		
		return array(true, compact('user', 'bank', 'amount', 'fee', 'amount_receive'));
	}
	
	/**
	 * Kiem tra amount
	 * 
	 * @param float $amount
	 * @return boolean
	 */
	public function valid_amount($amount)
	{
		return valid_amount($amount, $this->setting_amount());
	}
	
	/**
	 * Lay setting amount
	 * 
	 * @return array
	 */
	public function setting_amount()
	{
		$setting = array();
		foreach (array('min', 'max') as $p)
		{
			$setting[$p] = $this->setting('amount_'.$p);
		}
		
		return $setting;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Xu ly tao giao dich withdraw
	 * 
	 * @param array $input	Thong tin transfer:
	 * 	int		'user_id'
	 * 	int		'bank_id'
	 * 	int		'acc_id'
	 * 	int		'acc_name'
	 * 	float	'amount'
	 * 	float	'fee'
	 * 	string	'branch' 		= ''
	 * 	string	'desc' 			= ''
	 * @param array $output
	 * @return int $tran_id
	 */
	public function create(array $input, &$output = array())
	{
		// Lay input
		$user_id 	= $input['user_id'];
		$bank_id 	= $input['bank_id'];
		$acc_id 	= $input['acc_id'];
		$acc_name 	= $input['acc_name'];
		$amount 	= $input['amount'];
		$fee 		= $input['fee'];
		$branch 	= array_get($input, 'branch', '');
		$desc 		= array_get($input, 'desc', '');
		
		// Tao tran
		$tran = $this->create_tran($user_id, $amount);
		
		// Tao withdraw
		$withdraw = compact('user_id', 'bank_id', 'acc_id', 'acc_name', 'amount', 'fee', 'branch', 'desc');
		$withdraw = array_merge($withdraw, array(
			'id' 		=> $tran['id'],
			'bank_name' => model('bank')->get_info($bank_id, 'name')->name,
			'amount_receive' => $amount - $fee,
			'status' 	=> mod('order')->status('pending'),
		));
		
		$this->_model()->create($withdraw);
		
		// Gui email thong bao
		$this->email((object) $withdraw);
		
		// Gan output
		$output = compact('tran', 'withdraw');
		
		return $tran['id'];
	}
	
	/**
	 * Tao tran cho user
	 * 
	 * @param int $user_id
	 * @param float $amount
	 * @return array
	 */
	protected function create_tran($user_id, $amount)
	{
		$user_balance = model('user')->balance_minus($user_id, $amount);
		
		$tran = array();
		$tran_id = mod('tran')->create(array(
			'type' 			=> 'withdraw',
			'amount' 		=> $amount,
			'status' 		=> 'completed',
			'payment' 		=> 'balance',
			'user_id' 		=> $user_id,
			'user_balance' 	=> $user_balance,
		), $tran);
		
		return $tran;
	}
	
	/**
	 * Gui email thong bao
	 * 
	 * @param object $order
	 * @param string $to
	 * @param string $key
	 */
	public function email($order, $to = null, $key = 'withdraw')
	{
		if (is_numeric($order))
		{
			$order = $this->_model()->get_info($order);
		}

		$order = $this->add_info($order);

		$user = data_get($order, 'user', model('user')->get_info($order->user_id));
		
		$to = $to ?: $user->email;
		
		if ($admin_email = module_get_setting('site', 'email'))
		{
			$to = [$to, $admin_email];
		}
		
		mod('email')->send($key, $to, array(
			'id' 			=> $order->id,
			'user_email' 	=> $user->email,
			'bank' 			=> $order->bank_name,
			'acc_id' 		=> $order->acc_id,
			'acc_name' 		=> $order->acc_name,
			'amount' 		=> $order->_amount,
			'fee' 			=> $order->_fee,
			'amount_receive' => $order->_amount_receive,
			'status' 		=> lang('status_'.$order->_status),
			'url_view' 		=> site_url('tran/view/'.$order->id),
		));
	}

	// --------------------------------------------------------------------
	
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

		if (isset($row->created))
		{
			$row->_created = get_date($row->created);
		}
		
		foreach (array('amount', 'fee', 'amount_receive') as $p)
		{
			if (isset($row->$p))
			{
				$row->$p = (float) $row->$p;
				$row->{'_'.$p} = currency_convert_format_amount($row->$p);
			}
		}

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
			// Hoan thanh don hang
			case 'active':
			case 'active_hand':
			case 'completed':
			{
				$this->_model()->update_field($row->id, 'status', mod('order')->status('completed'));
				
				$this->email($row->id);
				
				break;
			}
			
			// Huy bo don hang
			case 'refund':
			case 'cancel':
			{
				$this->_model()->update_field($row->id, 'status', mod('order')->status('cancel'));
				
				$this->email($row->id);
				
				break;
			}
			
			// Xoa don hang
			case 'del':
			{
				$this->_model()->del($row->id);
				
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
	 * Tao filter tu input
	 * 
	 * @param array $fields
	 * @param array $input
	 * @return array
	 */
	public function create_filter(array $fields, &$input = array())
	{
		// Lay config
		$statuss = mod('order')->statuss();
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			if (
				($f == 'status' && ! in_array($v, $statuss))
			)
			{
				$v = '';
			}
			
			$input[$f] = $v;
		}
		
		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'status':
				{
					$v = mod('order')->status($v);
					break;
				}
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if (is_null($v)) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Luu cart
	 * 
	 * @param array $cart
	 */
	public function set_cart(array $cart)
	{
		$this->sess_set($cart);
	}
	
	/**
	 * Xoa gio hang
	 */
	public function del_cart()
	{
		$this->sess_del();
	}
	
	/**
	 * Lay thong tin cart
	 * 
	 * @return false|array
	 */
	public function get_cart()
	{
		$cart = $this->sess_get();
		
		if (empty($cart)) return false;
		
		$cart = $this->make_cart($cart);
		
		$cart = $this->format_cart($cart);
		
		return $cart;
	}
	
	/**
	 * Tao thong tin chi tiet cua cart
	 * 
	 * @param array $cart
	 * @return array
	 */
	protected function make_cart(array $cart)
	{
		$user_id 	= array_get($cart, 'user_id');
		$bank_id 	= array_get($cart, 'bank_id');
		$amount 	= array_get($cart, 'amount');
		
		$user = model('user')->get_info($user_id);
		$user->balance = model('user')->balance_get($user->id);
		
		$bank = mod('bank')->get_info($bank_id);
		
		$amount = currency_handle_input($amount, true);

		$fee = $this->get_fee_order($amount, $bank_id);
		
		$amount_receive = $amount - $fee;
		
		return array_merge($cart, compact('user', 'bank', 'amount', 'fee', 'amount_receive'));
	}
	
	/**
	 * Xu ly format cac gia tri cua cart
	 * 
	 * @param array $cart
	 * @return array
	 */
	protected function format_cart(array $cart)
	{
		$cart['user']->_balance = currency_convert_format_amount($cart['user']->balance);
		
		foreach (array('amount', 'fee', 'amount_receive') as $p)
		{
			$cart["_{$p}"] = currency_convert_format_amount($cart[$p]);
		}
		
		return $cart;
	}
	
	/**
	 * Session handle
	 */
	protected function sess_set($data)
	{
		return t('session')->set_userdata($this->sess_name(), $data);
	}
	
	protected function sess_get()
	{
		return t('session')->userdata($this->sess_name());
	}
	
	protected function sess_del()
	{
		return t('session')->unset_userdata($this->sess_name());
	}
	
	protected function sess_name()
	{
		return $this->_get_mod();
	}
	
}