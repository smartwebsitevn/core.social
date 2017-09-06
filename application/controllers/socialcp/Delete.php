<?php
use App\Invoice\InvoiceFactory as InvoiceFactory;
use App\Invoice\Library\OrderStatus;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\User\UserFactory;

class Delete extends MY_Controller
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
	    $rules = array();
	
	    $rules['day'] = array('day', 'required|trim|xss_clean|is_natural_no_zero|greater_than[30]');
	    $rules['password'] = array('password', 'required|trim|xss_clean|callback__check_password');
	    
	    $services = $this->data['services'];
	    foreach ($services as $service)
	    {
	        $rules[$service['key']] = array($service['name'], 'trim|xss_clean');    
	    }
	    $this->form_validation->set_rules_params($params, $rules);
	}
	
	/**
	 * Kiem tra username nay da duoc su dung chua
	 */
	function _check_password($value)
	{
	    $admin = admin_get_account_info();
	    $password = security_encode($value, strtolower($admin->username));
	    
	    //neu ko phai la root thi kiem tra  username
	    if($password != $admin->password)
	    {
	        $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
	        return FALSE;
	    }
	    
	    return TRUE;
	}
	
	
	/**
	 * Xóa các dữ liệu
	 */
	function index()
	{
	    $this->load->model('invoice_order_model');
	    $this->load->model('invoice_model');
	    $this->load->model('tran_model');
	    $this->load->model('deposit_card_log_model');
	    $this->load->model('deposit_card_model');
	   
	    $this->load->model('withdraw_model');
	    $this->load->model('transfer_model');
	    $this->load->model('deposit_model');
	    $this->load->model('product_order_model');
	
	    $this->data['services'] = InvoiceFactory::invoiceServiceManager()->listInfo();
	    
	    t('lang')->load('modules/invoice/invoice');
	    t('lang')->load('admin/delete');
	    
	    $form = array();
	    
	    $params_valid = array('password');
	    foreach ($this->data['services'] as $service)
	    {
	        $params_valid[] = $service['key'];
	    }
	    $form['validation']['params'] = $params_valid;
	    
	    $form['submit'] = function($params)
	    {
	    	$username = t('input')->post('username');
	    	$user = model('user')->find_user($username);
	    	
	        foreach ($this->data['services'] as $service)
	        {
	        	$this->db->query('SET FOREIGN_KEY_CHECKS = 0');
	        	
	            $service_key = $service['key'];
	            $day = $this->input->post($service_key);
	            $day = intval($day);
	            
	            if($day > 0)
	            {
	                $day_time = now() -  $day*24*60*60;
                   
	                //xoa deposit_card_log 
	                if($service_key == 'DepositCard')
	                {
	                    $this->deposit_card_log_model->del_rule(array('created <=' => $day_time));
	                }
	                
	                //xoa cac invoice , invoice_order, tran
	                $where = array();
	                $where['invoice_order.service_key'] = $service_key;
	                $where['invoice_order.created <=']  = $day_time;  
	                if($user)
	                {
	                	$where['invoice_order.user_id'] = $user->id;
	                }
	                
	                $input = array(
	                    'where' => $where, 'select' => 'id, invoice_id',
	                ) ;
	                $list = $this->invoice_order_model->get_list($input);
	               
	                foreach ($list as $row)
	                {
	                   ////xóa các lịch sử kèm theo
	                   if ($service_key == 'ProductOrderShip' || $service_key == 'ProductOrderCard'
	                       || $service_key == 'ProductOrderTopupGame' || $service_key == 'ProductOrderTopupMobile'
	                       || $service_key == 'ProductOrderTopupMobilePost' 
	                       )
	                   {
	                        $this->product_order_model->del_rule(array('invoice_order_id' => $row->id));
	                   }elseif($service_key == 'DepositCard')
    	               {  
    	                    $this->deposit_card_model->del_rule(array('invoice_order_id' => $row->id));
    	               }elseif ($service_key == 'DepositBank')
    	               {
    	                    model('deposit_bank')->del_rule(array('invoice_order_id' => $row->id));
    	               }elseif ($service_key == 'Topup_offline')
    	               {
    	                    model('topup_offline')->del_rule(array('invoice_order_id' => $row->id));
    	                    model('topup_offline_order')->del_rule(array('invoice_order_id' => $row->id));
    	               }elseif ($service_key == 'TransferSend')
    	               {
    	                    $this->transfer_model->del_rule(array('send_invoice_order_id' => $row->id));
    	               }elseif ($service_key == 'TransferReceive')
    	               {
    	                   $this->transfer_model->del_rule(array('receive_invoice_order_id' => $row->id));
    	               }
    	               elseif ($service_key == 'WithdrawPayment')
    	               {
    	                    $this->withdraw_model->del_rule(array('invoice_order_id' => $row->id));
    	               }elseif ($service_key == 'DepositPayment')
    	               {
    	                    $this->deposit_model->del_rule(array('invoice_order_id' => $row->id));
    	               }
	                    
	                   $this->invoice_order_model->del($row->id);
	                   $this->invoice_model->del($row->invoice_id);
	                   $this->tran_model->del_rule(array('invoice_id' => $row->invoice_id));
	                }
	            }
	        }
	        
	        set_message(lang('notice_del_success'));
	        	
	        return admin_url('delete');
	    };
	    
	    $form['form'] = function()
	    {
	        // Other
		    $this->data['action']   = current_url();	
		    
	        $this->_display();
	    };
	    
	    $this->_form($form);
	}
}

