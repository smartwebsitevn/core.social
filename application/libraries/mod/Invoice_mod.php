<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_mod extends MY_Mod
{
	/**
	 * Lay payments
	 *
	 * @return array
	 */
	public function payment_methods()
	{
		return config('payment_methods', 'main');
	}
	/**
	 * Lay statuss
	 *
	 * @return array
	 */
	public function statuss()
	{
		return config('invoice_statuss', 'main');
	}


	/**
	 * Lay types
	 *
	 * @return array
	 */
	public function order_types()
	{
		return config('order_types', 'main');
	}

	// --------------------------------------------------------------------

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
		$types 		= $this->order_types();
		$statuss 	= $this->statuss();

		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			if (
				($f == 'type' && ! in_array($v, $types))
			)
			{
				$v = '';
			}
			elseif ($f == 'status' && ! in_array($v, $statuss))
			{
				$v = ($v != 'all') ? 'completed' : $v;
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
				case 'type':
				{
					$v = array_search($v, $types);
					break;
				}
				case 'status':
				{
					$v = array_search($v, $statuss);
					$v = ($v === FALSE) ? NULL : $v;
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
	
	/**
	 * Tao tran moi
	 * 
	 * @param array $input
	 * 	string 		'type'
	 * 	float		'amount'
	 * 	string		'status' 		= 'pending'
	 * 	string		'payment' 		= ''
	 * 	int			'user_id' 		= 0
	 * 	int			'user_balance' 	= 0
	 * 	string		'user_ip' 		= ''
	 * @param array $output
	 * @return int
	 */
	public function create(array $input,array $orders, &$output = array())
	{
		// Xu ly input
		$now = now();
		$info_contact = array_get($input, 'info_contact', '');
		$info_pay_to = array_get($input, 'info_pay_to', '');
		$info_system = array_get($input, 'info_system', array());
		if (!$info_system)
		{
			$contact_system = array('name', 'phone', 'fax', 'address');
			foreach ($contact_system as $k)
			{
				$info_system[$k] = setting_get('config-' . $k);
			}
		}
		$payment_due = array_get($input, 'payment_due', $now);

		$fee_shipping = array_get($input, 'fee_shipping', 0);
		$fee_tax = array_get($input, 'fee_tax', 0);
		$amount = $input['amount'];
		$status = array_get($input, 'status', 'pending');
		$user_id = array_get($input, 'user_id', 0);
		$receiver_id = array_get($input, 'receiver_id', 0);
		$admin_id = array_get($input, 'admin_id', 0);
		$tran_id = array_get($input, 'tran_id', 0);

		//$tran_status	 		=  array_get($input, 'tran_status', 'pending');

		// Them vao table invoice
		$data = array();

		if ($info_contact)
		{
			$data['info_contact'] = serialize($info_contact);
		}
		if ($info_pay_to)
		{
			$data['info_pay_to'] = serialize($info_pay_to);
		}
		if ($info_system)
		{
			$data['info_system'] = serialize($info_system);
		}
		$data['payment_due'] = $payment_due;
		$data['fee_shipping'] = $fee_shipping;
		$data['fee_tax'] = $fee_tax;
		$data['amount'] = $amount;
		$data['status'] = $status;

		// luu cau hinh tien to invoice tai thoi diem tao
		$params = array();
		$params['pre_key'] = setting_get('config-invoice_pre_key');
		$params['pre_number'] = setting_get('config-invoice_pre_number');
		$data['params'] = serialize($params);


		$data['user_id'] = $user_id;
		$data['receiver_id'] = $receiver_id;
		$data['admin_id'] = $admin_id;

		$data['tran_id'] = $tran_id;
		//$data['tran_status'] 	= $tran_status;
		$data['created'] = $now;
		//pr($data);
		// Lay ma so cua giao dich vua them
		$id = 0;
		$this->_model()->create($data, $id);
		if($id>0){
			foreach($orders as $order){
				$type	 		= $order['type'];
				$title	 		=  array_get($order, 'title', lang('order_type_'.$type) );
				$desc	 		=  array_get($order, 'desc', '');

				$data = array();
				$data['invoice_id'] 	= $id;
				$data['type'] 			= $type;
				$data['title'] 			= $title;
				$data['desc'] 			= $desc;
				$data['amount'] 		= $amount;
				$data['created'] 		= $now;
				$this->set_order($data);
			}
		}
		// Gan output
		$output = $data;
		$output['id'] = $id;
		
		return $id;
	}

	public function get_order($invoice_id)
	{
		return model('invoice_order')->get_list_rule(array('invoice_id'=>$invoice_id));
	}
	public function set_order($data)
	{
		model('invoice_order')->create($data);
	}
	public function del_order($invoice_id)
	{
		model('invoice_order')->del_rule(array('invoice_id'=>$invoice_id));
	}

	public function update_tran_tmp($tran_id_tmp,$tran_id)
	{
		$this->_model()->update_rule(array('tran_id'=>$tran_id_tmp),array('tran_id'=>$tran_id));
	}
	
	/**
	 * Kiem tra quyen cua user hien tai voi ivoice
	 * 
	 * @param object $tran
	 */
	public function user_access($tran)
	{
		if ( ! $tran->user_id) return;
		
		// Neu chua dang nhap thi chuyen den trang login
		if ( ! user_is_login())
		{
			redirect_login_return();
		}
		
		// Neu dang nhap roi
		else
		{
			$user = user_get_account_info();
			
			if ($user->id != $tran->user_id)
			{
				redirect();
			}
		}
	}
	
	/**
	 * Luu log
	 * 
	 * @param object $tran
	 * @param string $action
	 */
	public function log($info, $action)
	{
		$detail= lang('button_update').' '.lang('status').' '. t('html')->a(admin_url('invoice/view/'.$info->id),lang('invoice').' '.$info->id). ' -> '.lang('invoice_status_'.$action);
		$log_info = array();
		$log_info ['detail'] = ucfirst($detail);
		mod('log')->log('invoice',$info->id,$action,$log_info);

	}

	/**
	 * Tao url
	 *
	 * @param object $row
	 * @return object
	 */
	public function url($row)
	{
		$row->_url_view = site_url('tran-'.$row->id);
		
		if (isset($row->security))
		{
			$row->_url_view_client = site_url('tran-'.$row->id.'/'.$row->security);
		}
		
		return $row;
	}
	
	/**
	 * Them cac thong tin phu
	 * 
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);

		$total_amount = 0;
		foreach (array( 'fee_shipping', 'fee_tax', 'amount') as $p)
		{
			if ( isset($row->$p) )
			{
				$row->$p = (float) $row->$p;
				$row->{'_'.$p} = currency_format_amount_default($row->$p);
				$total_amount += $row->$p;
			}
		}
		$row->_total_amount = currency_format_amount_default($total_amount);

		foreach ( array( 'info_contact', 'info_shipping', 'info_pay_to', 'info_system', 'params' ) as $p )
		{
			if (isset($row->$p) && $row->$p)
			{
				if(! is_object($row->$p) )
					$row->$p = json_decode($row->$p) ;
			}
		}

		$row->_shipping_name = '';
		$row->_payment_name = '';
		if( isset($row->params) && $row->params )
		{
			if( ! is_object($row->params) )
				$row->params = json_decode($row->params);

			$params = $row->params;
			// su ly format id hoa don
			$invoice_pre_key = $params->pre_key;
			$invoice_pre_number = $params->pre_number;

			if(! $invoice_pre_number ) $invoice_pre_number=0;

			$row->_id = $invoice_pre_key . sprintf( "%0" . $invoice_pre_number . "d", $row->id );

			if( isset($params->payment_method_name) )
				//$row->_payment_name = lang($params->payment_method);
			$row->_payment_name = $params->payment_method_name;

			if( isset($params->shipping_method_name) )
				$row->_shipping_name = $params->shipping_method_name;
		}

		if (isset($row->payment_due))
		{
			$row->_payment_due=get_date($row->payment_due);
		}
		if (isset($row->user_id))
		{
			$row->_user_name = model('user')->get_name($row->user_id);
		}
		if (isset($row->admin_id))
		{
			$row->_admin_name = model('admin')->get_name($row->admin_id);
		}



		// lay thong tin giao dich cua invoice
		// $row->_tran= mod('tran')->get_info($row->tran_id);

		// lay thong tin chi tiet don hang cua invoice
		$row->_orders= $this->get_order($row->id);

		$titles=array();

		foreach($row->_orders as $it){
			$titles[]= $it->title;
		}
		$row->_title =implode(',',$titles);
		return $row;
	}

	public function get_title($invoice_id){
		$orders= $this->get_order($invoice_id);

		$titles=array();

		foreach($orders as $it){
			$titles[]= $it->title;
		}
		if($titles)
		$title =implode(',',$titles);
		else
			$title ='[Deleted]';
		return $title;
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
		if ( ! $row) return false;
		
		$status =$row->status;
		
		switch ($action)
		{
			case 'view':
			case 'get':
			{
				return true;
			}
			
           /*	case 'client_view':
			{
				$type =$this->type_name($row->type);

				return ($type == 'order');
			}

			case 'payment':
			{
				$expire = add_time($row->created, array('d' => 1));

				return ($status == 'pending' && $expire >= now());
			}
*/
			case 'paid':
			{
				return ($status == 'unpaid');
			}
			
            /*case 'active':
			case 'active_hand': // Kich hoat bang tay
			case 'accept':
			{
				return ($status == 'pending' || $status == 'verify');
			}*/
			
			case 'canceled':
			{
				return ($status == 'unpaid' || $status == 'paid');
			}
			
			case 'del':
			{
				return true;

			}
			

		}
		
		return false;
	}
	
	/**
	 * Thuc hien hanh dong
	 * 
	 * @param object|int $row
	 * @param string $action
	 * @return boolean
	 */
	public function action($row, $action,$note='')
	{
		// Lay thong tin
		if (is_numeric($row))
		{
			$row = $this->_model()->get_info($row);
		}
		
		// Xu ly action voi tran
		if ($this->can_do($row, $action))
		{
			$row = $this->add_info($row);
			
			switch ($action)
			{
				// Huy bo don hang
				case 'canceled':
				{
					$this->_model()->update_field($row->id, 'status', 'canceled');
					
					break;
				}

				case 'del':
				{

					$this->_model()->del($row->id);
					model('invoice_order')->del_rule(array('invoice_id'=>$row->id));
					break;
				}
			}
			if($note)
				$this->_model()->update_field($row->id, 'note', $note);
			// Luu log
			$this->log($row, $action);
		}
		

	}
	
	/**
	 * Hoan tien
	 * 
	 * @param object $tran
	 * @param string $note
	 * @return false|int
	 */
	/*public function refund($tran, $note = '')
	{
		if (
			! $this->can_do($tran, 'refund')
			|| ! ($user = model('user')->get_info($tran->user_id))
		)
		{
			return false;
		}
		
		$tran = $this->url($tran);
		
		// Hoan lai tien cho user
		$user_balance = model('user')->balance_plus($user->id, $tran->amount);
		
		// Cap nhat trang thai giao dich
		$this->_model()->update($tran->id, array(
			'status' => 'refund',
		));
		
		// Tao giao dich refund
		$refund_id = $this->create(array(
			'type' 			=> 'refund',
			'amount' 		=> $tran->amount,
			'status' 		=> 'completed',
			'payment' 		=> 'balance',
			'user_id' 		=> $user->id,
			'user_balance' 	=> $user_balance,
		));
		
		model('refund')->create(array(
			'id' 			=> $refund_id,
			'for_tran_id' 	=> $tran->id,
			'note' 			=> $note,
		));
		
		mod('email')->send('refund', $user->email, array(
			'tran_id' 		=> $tran->id,
			'tran_url' 		=> $tran->_url_view_client,
			'user_email' 	=> $user->email,
		));
		
		return $refund_id;
	}*/
	
	/**
	 * Lay thong tin client
	 * 
	 * @param unknown $id
	 * @return boolean|mixed
	 */
	public function get_client($id)
	{
		$row = $this->_model()->get_info($id);
		
		if ( ! $row) return FALSE;
		
		if ($row->user_id)
		{
			return model('user')->get_info($row->user_id);
		}
		else 
		{
			return $this->call_module($row, 'get_client');
		}
	}
	
	/**
	 * Goi ham xu ly cua module tuong ung
	 */
	public function call_module($row, $act, $use_http = false)
	{
		// Lay thong tin
		$module = $row->type;
		
		// Neu su dung http request de goi module
		if (
			$use_http
			&& in_array($module, array('order'))
			&& $act == 'active'
		)
		{
			$url = site_url('cronjob/invoice_call_module').'?'.security_create_query(array('id' => $row->id, 'act' => $act));
			
			$result = lib('curl')->get($url);
			
			return @unserialize($result);
		}
		
		// Goi truc tiep module
		else
		{

			return $this->module($module)->action($row->id, $act);
		}
	}
	
	/**
	 * Lay doi tuong xu ly cua invoice module
	 * 
	 * @param string $module
	 */
	public function module($module)
	{
		return lib('order_module')->$module;
	}
	
	/**
	 * Lay thong tin thanh toan
	 * 
	 * @param unknown $tran_id
	 * @param unknown $payment
	 */
	public function get_tran_payment($tran_id, $payment)
	{
		$data = model('tran_payment')->get($tran_id);
		
		$data = $data ?: [];
		
		return t('payment')->$payment->make_tran_info($data);
	}



	/*
     * ------------------------------------------------------
     *  Xu ly Invoice Cart
     * ------------------------------------------------------
     */
	/**
	 * Lay thong tin gio hang cho invoice
	 */
	public function cart_invoices()
	{
		$mods=$this->cart_get();
		$data=array();
		foreach($mods as $mod){
			$data[$mod] =mod($mod)->cart_invoice();
		}
		return $data;
	}
	/**
	 * Lay thong tin gio hang
	 */
	public function cart_get()
	{
		$data=$this->session->userdata('cart_invoice');
		if(!$data)
			$data=array();
		return $data;
	}

	/**
	 * Gan gia tri gio hang
	 */
	public function cart_add($module)
	{
		$data = $this->cart_get();

		if(!in_array($module,$data)) {
			$data[] = $module;
			$this->session->set_userdata('cart_invoice', $data);
		}
	}

	/**
	 * Xoa module khoi invoice gio hang
	 */
	public function cart_del($module)
	{
		$data = $this->cart_get();

		if(in_array($module,$data)) {
			unset($data[$module]) ;
			$this->session->set_userdata('cart_invoice', $data);
		}
		//$this->session->unset_userdata('cart_invoice');
	}

	/**
	 * Xoa empty Invoice
	 */
	public function cart_empty()
	{
		$this->session->unset_userdata('cart_invoice');
	}


}