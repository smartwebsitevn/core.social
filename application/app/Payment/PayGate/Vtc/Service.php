<?php namespace App\Payment\PayGate\Vtc;

use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Library\Payment\PaymentResultInputRequest;
use App\Payment\Library\Payment\PaymentResultInputResponse;
use App\Payment\Library\Payment\PaymentResultOutputRequest;
use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;

class Service extends PayGateService
{
  
	/**
	 * Thuc hien thanh toan
	 *
	 * @param PaymentPayRequest $request
	 * @return PaymentPayResponse
	 */
	public function paymentPay(PaymentPayRequest $request)
	{
	    //load file thanh phan
	    $url = $this->setting('url');
		$return_url = $request->url_result;
		$tran_id    = $request->tran->id;
		$amount     = $request->amount;
		$payment_method = $this->setting('payment_method');
		$payment_method = (!in_array($payment_method, array(1, 2))) ? 2 : $payment_method;
		
		$language = $this->setting('language');
		$language = (!in_array($language, array('vi', 'en'))) ? 'vi' : $language;
		
		// Tao cac bien gui den payment
		$params = array();
		$params['website_id'] 			= $this->setting('website_id');
		$params['payment_method'] 		= $payment_method; // 1 = VND, 2 = USD
		$params['order_code'] 			= $tran_id;
		$params['amount']		        = $amount;
		$params['receiver_acc'] 	    = $this->setting('receiver_acc');
		$params['param_extend'] 	    = '';//'PaymentType:Visa;Direct:Master';
		$params['urlreturn']            = $return_url;
	
		//new version
		$plaintext = $params['website_id'] . "-"
				   . $params['payment_method'] . "-"
				   . $params['order_code'] . "-"
				   . $params['amount'] . "-"
				   . $params['receiver_acc'] . "-"
				   . $params['param_extend'] . "-"
				   . $this->setting('secret_key'). "-"
				   . $params['urlreturn'];
		
		$customer_mobile = '';
		$sign = strtoupper(hash('sha256', $plaintext));
		$data = "?website_id=" . $params['website_id']
		. "&l=".$language
		//. "&customer_mobile=".$params['receiver_acc']
		. "&payment_method=" . $params['payment_method']
		. "&order_code=" . $params['order_code']
		. "&amount=" . $params['amount']
		. "&receiver_acc=" .  $params['receiver_acc']
		. "&urlreturn=" .  $params['urlreturn']
		. "&param_extend=" . $params['param_extend']
		. "&sign=" . $sign;
		
		// Chuyen den merchant
		$url = $url.$data;
		
        return PaymentPayResponse::redirect($url);
	}
	
	/**
	 * Lay payment result input
	 *
	 * @param PaymentResultInputRequest $request
	 * @return PaymentResultInputResponse|null
	 */
	public function paymentResultInput(PaymentResultInputRequest $request)
	{ 
	    if($request->page == 'notify')
	    {
	        $result     = $this->CI->input->post();
	        $tran_id = $this->_vtc_get_tran_id($result);
	        return new PaymentResultInputResponse([
	            'tran_id' => $tran_id,
	        ]);
	    }
	    return null;
	}
	
	/**
	 * Xu ly ket qua thanh toan tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	public function paymentResult(PaymentResultRequest $request)
	{ 
	    $result     = $this->CI->input->post();
	    write_file_log('log_result.txt', $result);
	    
	    //lay IP cau hinh va kiem tra
	    $ips = array();
	    $ip  = $this->setting('ip');
	    $ip  = explode(',', $ip) ;
	    if(is_array($ip))
	    {
	        foreach ($ip as $i)
	        {
	            $ips[] = trim($i);
	        }
	    }
	    
	    //lay cac bien request
	    $amount     = $request->amount;
	    $tran_id    = $request->tran->id;
	   
	    //mac dinh thanh cong
	    $status = PaymentStatus::SUCCESS;
	    $error  = '';
	    
	    //kiem tra IP
	    /*
	    $_ip = $this->CI->input->ip_address();
	    if ( ! in_array($_ip, $ips))
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_ip_is_not_valid');
	        return $this->_ouput($request, $status, $error);
	    }
	    */
	   
	    // Neu la link user chuyen ve tu VTC sau khi thanh toan xong
	    $order_id = $this->_vtc_get_tran_id($result);
	    if (!$order_id)
	    {
	        $status = PaymentStatus::NONE;
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra ma so giao dich
	    if ($tran_id != $order_id)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_tran_not_exist');;
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra amount
	    $amount_pay = fv($this->_vtc_get_amount($result));
	    $amount 	= fv($amount);
	    if ($amount_pay < $amount)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_amount_invalid');
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra trang thai giao dich
	    $status_result = $this->_vtc_check_result($result, $error);
	    if ($status_result !== true)
	    {
	        $status = ($status_result === false) ? PaymentStatus::FAILED : PaymentStatus::NONE;
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    //neu thanh cong
	    return $this->_ouput($request, $status, $error);
	}
	

	
	
	/**
	 * Du lieu tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	private function _ouput(PaymentResultRequest $request, $status, $error = '')
	{
	    $response = [
	        'status'  => $status,
	        'amount'  => $request->amount,
	        'tran_id' => $request->tran->id,
	        'payment_tran_id' => now(),
	        'payment_tran' => ['id' => now()],
	    ];
	     
	    //neu co loi thi them thong bao loi
	    if($error)
	    {
	        $response['error'] = $error;
	    }
	    
	    write_file_log('log_response.txt', $response);
	    
	    return new PaymentResultResponse($response);
	}
	
	public function paymentResultOutput(PaymentResultOutputRequest $request)
	{
	    /*
	    if (is_url_notiy)
	    {
	        return PaymentResultOutputResponse::content(json_encode([]));
	    }
	    */
	    
		return null;
	}

	/**
	 * Hien thi thong tin giao dich phat sinh ben cong thanh toan
	 *
	 * @param array $payment_tran
	 * @return string|array
	 */
	public function viewPaymentTran(array $payment_tran)
	{
		return $payment_tran;

		return __METHOD__;

		return [
			'aaa' => 'AAA',
			'bbb' => 'BBB',
		];
	}


	/**
	 * Lay tran_id tu ket qua tra ve cua vtc
	 */
	private function _vtc_get_tran_id($result)
	{
	    if(!isset($result['data'])) return false;
	    $data   = $result["data"];
	    $string = explode('|',$data);
	    $tran_id = isset($string[0]) ? $string[0] : 0;
	    return $tran_id;
	}

	/**
	 * Lay so tien tra ve tu vtc
	 */
	private function _vtc_get_amount($result)
	{
	    if(!isset($result['data'])) return 0;
	    $data   = $result["data"];
	    $string = explode('|',$data);
	    return isset($string[2]) ? floatval($string[2]) : 0;
	}
	
	/**
	 * Kiem tra ket qua tra ve
	 */
	private function _vtc_check_result($result, &$error)
	{
	    if(!$result)
	    {
	        $error = 'Không có dữ liệu trả về';
	        return false;
	    }

	    $secret_key = $this->setting('secret_key');
	   
	    $data = $result["data"];
		$sign = $result["sign"];
		$plaintext = $data . "|" . $secret_key;
		$mysign = strtoupper(hash('sha256', $plaintext));
		if($mysign != $sign)
		{
	        $error = ' Fail to validate data!';
	        return false;
	    }
	    else
	    {
	        $string = explode('|',$data);
	        $status = $string[1];
	        $amount = floatval($string[2]);
	         
	        if($status == 1)
	        { 
	            return true;
	        }
	        else if($status == 2)
	        {
	            $error = 'Payment is Successful (pay with pending)!';
	            return NULL;
	        }
	        else if($status == 0)
	        {
	            $error = 'Payment is Pending!';
	            return NULL;
	        }
	        else if($status == -1)
	        {
	            $error = 'Payment is Failed!';
	            return false;
	        }
	        else if($status == -5)
	        {
	            $error = 'OrderID is not valid!';
	            return false;
	        }
	        else if($status == -6)
	        {
	            $error = "Account's balance is insufficient!";
	            return false;
	        }
	        else
	        {
	            $error = 'Payment is Not Success!';
	            return false;
	        }
	    }
	
	    return false;
	}
}
