<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
class UserUpgrade extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/user/common');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return ServiceType::USER_UPGRADE;
	}

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Upgrade Account'
		];
	}

	
	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
     public function getOrderDesc(InvoiceOrderModel $invoice_order)
    {
        $options = $invoice_order->order_options;
     
	    return lang('user_upgrade_desc', [
	        'user_level' => array_get($options, 'user_level'),
	    ]);
	}
	
	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */
	/*public function view(InvoiceOrderModel $invoice_order)
	{
	    $trade = @json_decode($invoice_order->trade_info);
	    $trade = mod('trade')->add_info($trade);
	    $trade->bank_input   = json_decode($trade->bank_input);

	    $user = user_get_account_info();
	    $user_id = ($user->id == $invoice_order->user_id) ? $invoice_order->user_sell_id : $invoice_order->user_id;
	    $amount_btc_show = ($invoice_order->user_id == $user->id) ? $invoice_order->additional_amount_btc : $invoice_order->amount_btc;
	    $invoice_order->amount_btc_show = $amount_btc_show;
	     
	    $user = model('user')->get_info($user_id);
	    
	    $data = array(
	        'invoice_order' => $invoice_order,
	        'trade' => $trade,
	        'bank_info' => @json_decode($invoice_order->bank_info),
	        'user' => $user,
	    );
	    
	    return view('tpl::invoice_order/trade', $data, true);
	}*/


	/*
	 * Tạo đơn hàng và cộng % hoa hồng cho thành viên
	 */
	function active($invoice_order)
	{
		if(!in_array($invoice_order->service_key, array('UserUpgrade')))
		{
			return ;
		}

		$user = model('user')->get_info($invoice_order->user_id);
		if(!$user)
			return ;

		if (!user_can_do($user, 'upgrade')) 
		{
			return ;
		}
		
		if($user->level == 0)
		{
		    $parent_user = false;
		    if($user->node_parent_temp > 0)
		    {
		        $parent_user = model('user')->get_info($user->node_parent_temp);
		    }    
			if(!$parent_user){
			    $parent_user = model('user')->get_info($user->parent_id);
			}
			if($parent_user)
			{
				model('user')->edit_downline($user, $parent_user);
			}
		}

		// nang cap level va reset han
		 $invoice_order->update(['order_status' => "completed"]);
		 model('user')->update($user->id,['level'=>$user->level +1,'expired_time' => 0]);

		// kiem tra sau update
		 mod('user')->check_upgrade_user($user);
		 
		 //xử lý tính hoa hồng cho người bảo trợ và người cắm cây
		 //cong tien cho nguoi gioi thieu
		 $invoice_order_aff = new \App\Invoice\InvoiceService\Affiliate();
		 $invoice_order_aff->active($invoice_order);

	}

}