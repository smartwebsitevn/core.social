<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Combo extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();
		// Tai cac file thanh phan
		$this->lang->load('site/combo');
	}
	
	/**
	 * Danh sach
	 */
	public function index()
	{
	    //lay setting cua combo
	    $this->data['desc'] = module_get_setting('combo', 'desc');
	    $images = module_get_setting('combo', 'image');
	    $slides = array();
	    foreach ($images as $img)
	    {
	        $file = model('file')->get_info_rule(array('file_name' => $img));
	        if($file)
	        {
	            $file = file_add_info($file);
	            $slides[] = $file;
	        }
	    }
	    
	    $this->data['slides'] = $slides;
	    $filter = array();
	    $input  = array();
	   // $input['select'] = 'id,name,expire_to,expire_from,price';
		$this->_create_list($filter, $input);
		
		$this->_display();
	}
	
	/**
	 * Tao danh sach hien thi
	 */
	protected function _create_list($filter = array(), $input = array(), $base_url = '')
	{
		// Lay tong so
		$total = $this->_model()->filter_get_total($filter);

		$page_size = module_get_setting('combo', 'list_limit');
	
		$limit=0;
		if($total>0){
    		$limit = $this->input->get('per_page');
    		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
		}
		
		// Lay danh sach
		$input['limit'] = array($limit, $page_size);
		$filter['unexpire'] = true;
		$list = $this->_model()->filter_get_list($filter, $input);
		//pr_db();
		//pr(get_date('1481509114'),0);
		//pr(get_date('1481509114'),1);
		$list = (array) $list;
		
		// Xu ly list
		foreach ($list as $row)
		{    
			$row = $this->_mod()->add_info($row);
		}
		//pr($list);
		
		$this->data['list'] = $list;
	
		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= ( ! $base_url) ? current_url().'?' : $base_url;
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;
	}
	
	/*
	 * Xem chi tiet
	 */
	function view()
	{
	    $this->_order_view();
	    
	}
	
	/**
	 * Xem chi tiet
	 */
	public function order()
	{
	    if(!user_is_login())
	    {
	        redirect_login_return();
	    }
	    
	    $form = array();
	    
	    $this->_order_view();
	     
	    $form['form'] = function () {
	         
	    };
	     
	    $form['validation']['params'] = $this->_order_params();
	     
	    $form['submit'] = function ($params) {
	        return $this->_order_submit();
	    };
	    
	    $this->_form($form);
	    
	}


	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
	    // Main
	    $rules = array();
	    $rules['price_time'] = array('price_time', 'required|trim|xss_clean|callback__check_price_time');
	    
	    $pservices = $this->data['pservices'];
	    foreach ($pservices as $row)
	    {
	        foreach ($row->product_type_requireds as $req)
	        {
	            $rules['input_required_'.$req.'_'.$row->id] = array('input_required_'.$req, 'required|trim|xss_clean');
	        }
	    }
	    
	    //truong hop can dang ky khach hang
	    // Lay config
	    $length = model('customer')->_password_lenght;
	    $rules['email'] = array('email', 'required|trim|xss_clean|callback__check_email');
	    $rules['email_valid'] = array('email', 'required|trim|xss_clean|valid_email|callback__check_email_valid');
	    $rules['email_activation'] = array('email', 'required|trim|xss_clean|valid_email|callback__check_email_activation');
	    $rules['username'] = array('username', 'required|trim|xss_clean|alpha_dash|min_length[' . $length . ']|callback__check_username');
	    $rules['name'] = array('full_name', 'required|trim|xss_clean');
	    $rules['phone'] = array('phone', 'required|trim|xss_clean|callback__check_phone');
	    $rules['address'] = array('address', 'required|trim|xss_clean');
	    $rules['security_code'] = array('security_code', 'required|captcha[four]');
	    $rules['rule'] = array('', 'callback__check_rule');
	    $rules['type'] 	= array('user_type', 'required|trim|callback__check_type');
	     
	    $rules['tax_code']               = array('tax_code', 'required|trim|xss_clean');
	    $rules['company_name'] 		     = array('company_name', 'required|trim|xss_clean|max_length[128]');
	    $rules['company_representative'] = array('company_representative', 'required|trim|xss_clean|max_length[128]');
	    
	    // Verify
	    $rules['id_number'] = array('id_number', 'required|trim|xss_clean');
	    $rules['id_place'] = array('id_place', 'required|trim|xss_clean');
	    $rules['id_date'] = array('id_date', 'required|trim|xss_clean');
	     
	    $this->form_validation->set_rules_params($params, $rules);
	}
	
	
	
	/**
	 * Remap method
	 */
	private function _get_user_info()
	{ 
	    if(!user_is_login())
	    {
	        redirect_login_return('user');
	    }
	        
	    return user_get_account_info();
	}
	

	/**
	 * Kiem tra username nay da duoc su dung chua
	 */
	public function _check_username($value)
	{
	    $user = $this->_get_user_info();
	    if (model('customer')->get_info_rule(array('username' => $value, 'user_id' => $user->id))) {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
	        return FALSE;
	    }
	
	    return TRUE;
	}
	
	/**
	 * Kiem tra phone
	 */
	public function _check_phone($value)
	{
	    $phone = handle_phone($value);
	
	    if (!valid_phone($phone)) {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
	        return FALSE;
	    }
	
	    $user = $this->_get_user_info();
	    if (model('customer')->get_info_rule(array('phone' => $phone, 'user_id' => $user->id))) {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
	        return FALSE;
	    }
	
	    return TRUE;
	}

	/**
	 * Kiem tra su hop le cua type
	 */
	function _check_type($value)
	{
	    $user_types = $this->config->item('user_types', 'main');
	
	    if (!isset($user_types[$value]))
	    {
	        $this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_value_not_exist'));
	        return FALSE;
	    }
	
	    return TRUE;
	}
	
	/**
	 * Kiem tra rule
	 */
	public function _check_rule($value)
	{
	    if (!$value) {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_not_agree_rule'));
	        return FALSE;
	    }
	
	    return TRUE;
	}
	
	/**
	 * Kiem tra ngay thang
	 */
	function _check_date($value)
	{
	    if (!get_time_from_date($value)) {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
	        return FALSE;
	    }
	
	    return TRUE;
	}
	
	
	/**
	 * Kiem tra email khach hang nay co ton tai hay khong
	 */
	public function _check_email($value, $msg = true)
	{ 
	    $user = $this->_get_user_info();
        if (model('customer')->get_info_rule(array('email' => $value, 'user_id' => $user->id))) {
            if($msg)
            {
                $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            }
            return FALSE;
        }
        return TRUE;
	}
	
	
	/**
	 * Kiem tra thời hạn
	 */
	public function _check_price_time($value)
	{ 
	    $combo = $this->data['combo'];
	    if(!isset($combo->price_times[$value]) || $combo->price_times[$value] == config('invalid_amount', 'pservice'))
	    {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
	        return FALSE;
	    } 
	}
	

	/**
	 * Lay cac bien register
	 *
	 * @return array
	 */
	protected function _customer_register_params()
	{
	    $params = array(
	        'email' ,'username', 'address',  'phone', 'type', 'id_number', 'id_place', 'id_date'
	    );
	
	    $type = $this->input->post('type');
	    if ($type == $this->config->item('user_type_personal', 'main'))
	    {
	        array_push($params, 'name');
	    }
	    elseif ($type == $this->config->item('user_type_company', 'main'))
	    {
	        array_push($params, 'company_name', 'company_representative', 'tax_code');
	    }
	
	    return $params;
	}
	
	
	/**
	 * Register submit
	 *
	 * @return string
	 */
	protected function _customer_register_submit()
	{
	    $input = $this->_customer_register_input();
	
	    //tao ma pin neu chua co ma pin
	    $input['pin'] = mod('customer')->random_password();
	    $input['password'] = mod('customer')->random_password();
	     
	    $input['security_method'] = 'pin';
	    $input = array_filter($input);
	
	    //gan dai ly mac dinh
	    $user = $this->_get_user_info();
	    $input['user_id'] = $user->id;
	     
	    $customer = '';
	    mod('customer')->create($input, $customer);
	    return (object) $customer;
	}
	
	/**
	 * Lay input register
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function _customer_register_input()
	{
	    $input = array();
	    foreach (array(
	        'email', 'username', 'phone', 'address', 'type', 'id_number', 'id_place', 'id_date',
	    ) as $p) {
	        $input[$p] = $this->input->post($p);
	    }
	
	    $type = $this->input->post('type');
	    if ($type == $this->config->item('user_type_personal', 'main'))
	    {
	        $input['name'] = $this->input->post('name', true);
	    }
	    elseif ($type == $this->config->item('user_type_company', 'main'))
	    {
	        $input['name'] = $this->input->post('company_name', true);
	        $input['representative'] = $this->input->post('company_representative', true);
	        $input['tax_code'] = $this->input->post('tax_code', true);
	    }
	
	    return $input;
	}
	
	
	/**
	 * Lay cac bien input
	 *
	 * @return array
	 */
	protected function _order_params()
	{
	    $params = array();
	
	    $combo = $this->data['combo'];
	     
	    //kiem tra loai thanh toan
	    if($combo->payment_type == 'recurring')
	    {
	        array_push($params, 'price_time');
	    }

	    $pservices = $this->data['pservices'];
	    foreach ($pservices as $row)
	    {
	        foreach ($row->product_type_requireds as $req)
	        {
	            array_push($params, 'input_required_'.$req.'_'.$row->id);
	        }
	    }
	    
	    //neu la dai ly dang nhap thi can nhap email cua khach hang
	    if(user_is_login())
	    {
	        $email  = $this->input->post('email');
	        //neu khong ton tai khach hang nay thi them cac params nhap thong tin khach hang
	        if($this->_check_email($email, false))
	        {
	            $customer_params = $this->_customer_register_params();
	            $params = array_merge($params, $customer_params);
	        }
	    }
	    
	    return $params;
	}
	
	/**
	 * Lay cac bien input
	 *
	 * @return array
	 */
	protected function _order_input()
	{
	    $input  = array();
	    $params = $this->_order_params();
	     
	    foreach ($params as $p) {
	        $input[$p] = $this->input->post($p);
	    }
	
	    return $input;
	}
	
	
	/**
	 * Register submit
	 *
	 * @return string
	 */
	protected function _order_submit()
	{
	    //lay cac thong tin post len
	    $input = $this->_order_input();
	
	    //lay thong tin combo
	    $combo = $this->data['combo'];
	    //thong tin order_options
	    $order_options = array();
	    
	    //tinh tong so tien can thanh toan
	    $amount = 0;
	    $price_time = '';
	    if($combo->payment_type == 'onetime'){
	        $amount = $combo->price_total;
	    }elseif($combo->payment_type == 'recurring'){
	        $price_time = $this->input->post('price_time');
	        
	        $amount_time = isset($combo->price_times[$price_time]) ? floatval($combo->price_times[$price_time]) : 0;
	        $amount_setup_time = isset($combo->price_setup_times[$price_time]) ? floatval($combo->price_setup_times[$price_time]) : 0;
	        $amount = $amount_time + $amount_setup_time;
	    }
	    
	    $order_options['combo'] = $combo->name;
	    if($price_time)
	    {
	        $order_options['price_time'] = $price_time.' '.lang('month'); 
	    }
	    
	    //key tim kiem
	    
	    $pservices = $this->data['pservices'];
	    $pservice_options = '';
	    foreach ($pservices as $row)
	    {
	        $input_required = array();
	        $pkeywords   = array();
	        $pkeywords[] = $combo->name;
	        $pservice_option = $row->name;
	        foreach ($row->product_type_requireds as $req)
	        {
	            $input_required[$req] = $this->input->post('input_required_'.$req.'_'.$row->id);
	            $pkeywords[] = $input_required[$req];
	            $pservice_option .= ' - '.lang('input_required_'.$req) .': ' . $input_required[$req];
	        }
	        $row->input_required  = $input_required;
	        $row->keywords        = $pkeywords;   
	        
	        $pservice_options .=  $pservice_option.'<br/>';
	        
	        $row->amount_pservice = 0;
	        $amount = 0;
	        if($row->payment_type == 'onetime'){
	            $amount = $row->price_total;
	            $row->amount_pservice = $amount;//don gia     
	        }elseif($row->payment_type == 'recurring'){
	            $amount_time = isset($row->price_times[$price_time]) ? floatval($row->price_times[$price_time]) : 0;
	            $amount_setup_time = isset($row->price_setup_times[$price_time]) ? floatval($row->price_setup_times[$price_time]) : 0;
	            $amount = $amount_time + $amount_setup_time;
	            $row->amount_pservice = $amount;//don gia
	            $amount = $amount*$price_time; //so tien tren 1 thang * so thang
	        }
	        $row->total_amount_pservice = $amount; //tong so tien cua pservice	  
	    }
	    $order_options['pservice'] = $pservice_options;
	    
	    //lay order_options  
	    $input['combo']          = $combo;
	    $input['price_time']     = $price_time;
	    $input['amount']         = $amount;
	    $input['pservices']      = $pservices;
	    $input['order_options']  = $order_options;
	   
	    //lay thong tin khach hang va dai ly
	    if(user_is_login())
	    {
	     $user           = user_get_account_info();
	        $email          = $this->input->post('email');
	        if(!$this->_check_email($email, false))
	        {
	            //lay thong tin tai khoan khach hang
	            $customer       = model('customer')->get_info_rule(array('email' => $email, 'user_id' => $user->id));
	        }else{
	            //Tao tai khoan khach hang moi
	            $customer = $this->_customer_register_submit();
	        }
	    }else{
	        $customer     = customer_get_account_info();
	        $user = model('user')->get_info($customer->user_id);
	    }
	    if(!$user || !$customer)
	    {
	        return false;
	    }
	    $input['user']     = $user;
	    $input['customer'] = $customer;
	
	    $output = '';
	    $this->_mod()->create($input, $output);
	     
	    $invoice = $output['invoice'];
	     
	    $url_payment = $invoice->url('payment');
	    set_message(lang('notice_add_success'));
	
	    return $url_payment;
	}
	
	
	/**
	 * Tao view register
	 */
	protected function _order_view()
	{
		// Lay thong tin
		$id = $this->uri->rsegment(3);
		$id = ( ! is_numeric($id)) ? 0 : $id;
		$combo = $this->_model()->get_info_rule(array('id'=>$id,'status'=>1));
		if ( ! $combo)
		{
			redirect();
		}
		
		// Xu ly thong tin cua combo
		$combo = $this->_mod()->add_info($combo);
		//$services = $this->_model()->get_services($combo->id);
		$services = $this->_model()->get_services($combo);
		$combo->meta_desc 	= ( ! $combo->meta_desc) ? $combo->name : $combo->meta_desc;
		$combo->meta_key 	= ( ! $combo->meta_key) ? $combo->name : $combo->meta_key;
		$combo->meta_title 	= ( ! $combo->meta_title) ? $combo->name : $combo->meta_title;


		$this->data['combo'] = $combo;
		$this->data['services'] = $services;

		page_info('breadcrumbs', array($combo->_url_view, word_limiter($combo->name, 10), $combo->name));
		page_info('title', $combo->meta_title);
		page_info('description', $combo->meta_desc);
		page_info('keywords', $combo->meta_key);
		
		$this->_display();
	    
	}
	
}
