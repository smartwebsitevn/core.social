<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
class UserUnlock extends InvoiceService
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
		return ServiceType::USER_UNLOCK;
	}

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Unlock Account'
		];
	}

	
	/**
	 * Lay mo ta cua order
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return null|string|array
	 */
	/*	public function getOrderDesc(InvoiceOrderModel $invoice_order)
       {
          $options = $invoice_order->order_options;
           $user = user_get_account_info();
           $amount_btc_show = ($invoice_order->user_id == $user->id) ? $invoice_order->additional_amount_btc : $invoice_order->amount_btc;
           $options['amount'] = currency_format_amount($amount_btc_show, config('currency_btc_id'));

           return lang('order_desc_trade', $options);
	}
	*/
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
		if(!in_array($invoice_order->service_key, array('UserUnlock')))
		{
			return ;
		}

		$user = model('user')->get_info($invoice_order->user_id);
		if(!$user)
			return ;

		if (!user_can_do($user, 'unlock')) {
			return ;
		}
		$invoice_order->update(['order_status' => "completed"]);
		model('user')->update($user->id,['status'=>config('user_status_active', 'main')]);
		
		//nếu đang trong thời gian tăng level thì gia hạn tiếp cho tăng level
		if($user->expired_time > 0 && $user->expired_time < now())
		{
			$expired_time = now() + 24*60*60;
		    model('user')->update($user->id,['expired_time' => $expired_time]);
		}
	}

}