<?php namespace App\Invoice\InvoiceService;
use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel;
use App\ServiceOrder\Model\ServiceOrderModel;



class ServiceOrder extends InvoiceService
{
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		t('lang')->load('modules/service_order/common');
	}

	/**
	 * Lay loai dich vu (lay theo ServiceType::***)
	 *
	 * @return string
	 */
	public function type()
	{
		return ServiceType::ORDER;
	}

	/**
	 * Lay thong tin
	 *
	 * @return array
	 */
	public function info()
	{
		return [
			'name' => 'Gia hạn VIP'
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
		$order_options = '';
		foreach ($options as $key => $value)
		{
		    $order_options .= '<p><b>'.lang($key).'</b> : '.$value.'</p>';
		}
		return $order_options;
	}
	
	/**
	 * Kich hoat invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 */
	public function active(InvoiceOrderModel $invoice_order)
	{
		try
		{
		    //cap nhat trang thai cua invoice_order
		    model('invoice_order')->update($invoice_order->id, array('order_status' => 'completed'));
		    
		    $filter = array();
		    $filter['invoice_order'] = $invoice_order->id;
		    $input = array();
		    $input['select'] = 'id, type_cur, time, expire_from, expire_to, status';
		    $service_orders = model('service_order')->filter_get_list($filter, $input);
		   
		    foreach ($service_orders as $row)
		    {
		        $data = array();
		        if($row->type_cur == 'new')
		        {
		            $data['status']  = 'inactive';
		        }else if($row->type_cur == 'renew')
		        {
		            //neu la loai thanh toan theo han thi them han cua dich vu do
		            $expire_to = get_time_from_month($row->time, max($row->expire_to, now()));
		            $data['expire_to']          = $expire_to;
		            $data['expire_from']        = now();
		            if(in_array($row->status, array('expired', 'suspended')))
		            {
		                $data['status'] = 'processing';
		            }
		            $data['type'] = 'renew';
		        }
		    
		        //$data['tran_id'] = $tran->id;
		        model('service_order')->update($row->id, $data);
		    }
		}
		catch (\Exception $e){}
	}

	/**
	 * View invoice
	 *
	 * @param InvoiceOrderModel $invoice_order
	 * @return string|null
	 */
	public function _view(InvoiceOrderModel $invoice_order)
	{
	  //  t('lang')->load('site/pservice');
		$service_order = ServiceOrderModel::findByInvoiceOrder($invoice_order->id);
		
		if ( ! $service_order) return;
		
		$service_order->invoice_order = $invoice_order;
		$service_order = mod('service_order')->add_info($service_order);
		$data = compact('service_order', 'invoice_order');
		
		return view('tpl::service_order/view', $data, true);
	}

}

