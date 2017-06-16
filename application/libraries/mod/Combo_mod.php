<?php

class Combo_mod extends MY_Mod
{
    /**
     * Them cac thong tin phu
     *
     * @param object $row
     * @return object
     */
    public function add_info($row)
    {
        $row = parent::add_info($row);

		if(isset($row->services) && $row->services)
			$row->services = json_decode($row->services);


        foreach (array('name') as $p)
        {
            if (isset($row->$p))
            {
                $row->$p = html_escape($row->$p);
            }
        }
    
        if (isset($row->created))
        {
            $row->_created = get_date($row->created);
            $row->_created_time = get_date($row->created, 'time');
        }
        
        if (isset($row->expire_from))
        {
            $row->_expire_from = get_date($row->expire_from);
            $row->_expire_from_time = get_date($row->expire_from, 'time');
        }
        
        if (isset($row->expire_to))
        {
            $row->_expire_to = get_date($row->expire_to);
            $row->_expire_to_time = get_date($row->expire_to, 'time');
        }
        
        $row->expire = 'expired';
        if($row->expire_to > now())
        {
            $row->expire = 'unexpire';
        }
        
        if (isset($row->description))
        {
            $row->description = handle_content($row->description, 'output');
        }
        
        if(isset($row->desc))
        {
            if (!$row->desc)
            {
                $row->desc = substr(strip_tags($row->description), 0, 100);
            }
            $row->_desc = explode("\n", $row->desc); 
        }
        
        $row->price_total = 0;
        if (isset($row->price))
        {
            $row->_price = currency_format_amount($row->price);
            $row->price_total = $row->price;
        }
       /* if (isset($row->price_setup))
        {
            $row->_price_setup = currency_format_amount($row->price_setup);
            $row->price_total += $row->price_setup;
        }*/
		if( $row->price_total ==0)
			$row->_price_total = lang('free');
		else
			$row->_price_total = currency_format_amount($row->price_total);

        if (isset($row->image_name))
        {
            t('load')->helper('file');
            $row->image = file_get_image_from_name($row->image_name, public_url('img/no_image.png'));
        }

		$row = $this->url($row);
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
        $name = url_title(convert_vi_to_en($row->name));
    
        $row->_url_view = site_url("{$name}-cb{$row->id}");
        $row->_url_buy = site_url("checkout").'?combo_id='.$row->id;
        return $row;
    }
    
	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
	{
		
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));
			
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
				
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
				case 'expire_to':
				    {
				        $created_to = $input['expire_to_to'];
				        $v = (strlen($created_to)) ? array($v, $created_to) : $v;
				        $v = get_time_between($v);
				        $v = ( ! $v) ? NULL : $v;
				        break;
				    }
			}
			
			if ($v === NULL) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
	

	/**
	 * Tao order
	 *
	 * @param array $input
	 * 	array 		'cart'			Thong tin cart (la ket qua tra ve cua fun mod('topup_offline')->get_cart())
	 * 	string		'tran_status' 	= 'pending'
	 * 	string		'tran_payment' 	= ''
	 * 	string		'order_status' 	= 'pending'
	 * 	int			'user_id' 		= 0
	 * 	int			'user_balance' 	= 0
	 * 	string		'user_ip' 		= ''
	 * 	array		'contact' 		= array()
	 * @param array $output
	 * @return int
	 */
	public function create(array $input, &$output = array())
	{
	    // Xu ly input
	    $pservices   = array_get($input, 'pservices', array());
	    $user		 = array_get($input, 'user');
	    $customer	 = array_get($input, 'customer'); 
	    $amount      = array_get($input, 'amount', 0);
	    $combo       = array_get($input, 'combo', array());
	    $time        = array_get($input, 'price_time', 0);
	    $order_options = array_get($input, 'order_options', array());
	    
	    //tao Invoice
	    $invoice = $this->createInvoice($amount, $user, $customer);
	
	    //tao Invoice_order
	    $invoice_order = $this->createInvoiceOrder($invoice, $order_options);
	
	
	    //them vao bang plan_order
	    // Cap nhat vao data
	   
	    foreach ($pservices as $pservice)
	    {
    	    if(isset($pservice->description))
    		{
    		    unset($pservice->description);
    		}
    		if(isset($pservice->desc))
    		{
    		    unset($pservice->desc);
    		}
	        $data = array();
	        $data['type']             = 'new'; //loai mua moi
	        $data['type_cur']         = 'new';
	        $data['amount']           = $pservice->total_amount_pservice;
	        $data['amount_renew']     = $pservice->total_amount_pservice;
		    $data['amount_order']     = $pservice->amount_pservice;
	        $data['user_id']          = $user->id;
	        $data['customer_id']      = $customer->id;
	        $data['invoice_id']       = $invoice->id;
	        $data['invoice_order_id'] = $invoice_order->id;
	        $data['pservice_id']      = $pservice->id;
	        $data['pservice_info']    = @serialize($pservice);
	        $data['product_type']     = $pservice->product_type;
	        $data['input_required']   = @serialize($pservice->input_required);
	        $data['time']             = $time;
	        
	        //kiem tra loai thanh toan cua pservice
	        /*
	        if($combo->payment_type == 'recurring')
	        {
	            //neu la loai thanh toan theo han thi them han cua dich vu do 
	            $expire_from = get_time_from_month($time);
	            $data['expire_to']          = now();
	            $data['expire_from']        = $expire_from;
	        }
	        */
	        $data['keywords']          = implode(',', $pservice->keywords);
	        $data['status']            = 'none';
	        $data['last_update_status'] = now();
	        $data['created']		   = now();
	        $service_order_id = 0;
	        $service_order = model('service_order')->create($data, $service_order_id); 
	        
	        //cap nhat service_order_id cho invoice
	        model('invoice')->update($invoice->id, array('service_order_id' => $service_order_id));
	        model('invoice_order')->update($invoice_order->id, array('service_order_id' => $service_order_id));  
	    }

	    $output = compact('invoice', 'invoice_order');
	}
	
	/**
	 * Tao invoice
	 *
	 * @return InvoiceModel
	 */
	protected function createInvoice($amount, $user, $customer)
	{
	    $options = new \App\Invoice\Library\CreateInvoiceOptions([
	        'amount'      => $amount, // tinh theo tien te mac dinh
	        'user_id'     => $user->id, // Ma thanh vien
	        'customer_id' => $customer->id,
	        'status'      => 'unpaid', // unpaid or paid
	    ]);
	
	    $invoice = \App\Invoice\InvoiceFactory::invoice()->create($options);
	
	    return $invoice;
	}
	
	/**
	 * Tao invoice order
	 *
	 * @param InvoiceModel $invoice
	 * @return InvoiceOrderModel
	 */
	protected function createInvoiceOrder($invoice, $order_options)
	{
	    $options = new \App\Invoice\Library\CreateInvoiceOrderOptions([
	        'invoice'       => $invoice,
	        'customer_id'   => $invoice->customer_id,
	        'service_key'   => 'ServiceOrder',
	        'amount'        => $invoice->amount,
	        'order_status'  => 'pending',
	        'order_options' => $order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
	    ]);
	
	    $invoice_order = \App\Invoice\InvoiceFactory::invoiceOrder()->create($options);
	    return $invoice_order;
	}
	
}