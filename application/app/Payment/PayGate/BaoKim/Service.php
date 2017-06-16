<?php namespace App\Payment\PayGate\BaoKim;

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
	   
	    $business = $this->setting('business');
		$return_url = $request->url_result;
		$tran_id    = $request->tran->id;
		$tran_info  = 'Payment for invoice '.$tran_id;
	
		$url = array();
		$url['success'] = url_add_uri($return_url, 'success');
		$url['cancel'] 	= url_add_uri($return_url, 'cancel');
		$url['detail'] 	= url_add_uri($return_url, 'detail');
		
		$url = $this->_bk_create_url($tran_id, $business, $request->amount, '', '', $tran_info, $url['success'], $url['cancel'], $url['detail']);
		
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
	    //write_file_log('log_result_input.txt', $this->CI->input->post());
	    if($request->page == 'notify')
	    {
	        $tran_id = $this->CI->input->post('order_id');
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
	    $_ip = $this->CI->input->ip_address();
	    $result =  $this->CI->input->post();
	    if(is_array($result))
	    {
	        $result['ip'] = $_ip;
	    }
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
	    if ( ! in_array($_ip, $ips))
	    {
	        /*
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_ip_is_not_valid');
	        return $this->_ouput($request, $status, $error);
	        */
	    }
	    
	    // Neu la link user chuyen ve tu baokim sau khi thanh toan xong
	    if (!$this->CI->input->post('order_id'))
	    {
	        $status = PaymentStatus::NONE;
	        return $this->_ouput($request, $status, $error);
	    }

	    // Kiem tra ma so giao dich
	    if ($tran_id != $this->CI->input->post('order_id'))
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_tran_not_exist');;
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra amount
	    $amount_pay = fv($this->CI->input->post('total_amount'));
	    $amount 	= fv($amount);
	    if ($amount_pay < $amount)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_amount_invalid');
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra trang thai giao dich
	    if ($this->CI->input->post('transaction_status') != 4)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_result_unsuccessful');
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
	 * Hàm xây dựng url chuyển đến BaoKim.vn thực hiện thanh toán, trong đó có tham số mã hóa (còn gọi là public key)
	 * @param $order_id				Mã đơn hàng
	 * @param $business 			Email tài khoản người bán
	 * @param $total_amount			Giá trị đơn hàng
	 * @param $shipping_fee			Phí vận chuyển
	 * @param $tax_fee				Thuế
	 * @param $order_description	Mô tả đơn hàng
	 * @param $url_success			Url trả về khi thanh toán thành công
	 * @param $url_cancel			Url trả về khi hủy thanh toán
	 * @param $url_detail			Url chi tiết đơn hàng
	 * @return url cần tạo
	 */
	private function _bk_create_url($order_id, $business, $total_amount, $shipping_fee, $tax_fee, $order_description, $url_success, $url_cancel, $url_detail)
	{
	    // Mảng các tham số chuyển tới baokim.vn
	    $params = array(
	        'merchant_id'		=>	strval($this->setting('merchant_id')),
	        'order_id'			=>	strval($order_id),
	        'business'			=>	strval($business),
	        'total_amount'		=>	strval($total_amount),
	        'shipping_fee'		=>  strval($shipping_fee),
	        'tax_fee'			=>  strval($tax_fee),
	        'order_description'	=>	strval($order_description),
	        'url_success'		=>	strtolower($url_success),
	        'url_cancel'		=>	strtolower($url_cancel),
	        'url_detail'		=>	strtolower($url_detail)
	    );
	    ksort($params);
	
	    $str_combined = $this->setting('secure_pass').implode('', $params);
	    $params['checksum'] = strtoupper(md5($str_combined));
	
	    //Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào
	    $redirect_url = $this->setting('url');
	    if (strpos($redirect_url, '?') === FALSE)
	    {
	        $redirect_url .= '?';
	    }
	    else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === FALSE)
	    {
	        // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
	        $redirect_url .= '&';
	    }
	
	    // Tạo đoạn url chứa tham số
	    $url_params = '';
	    foreach ($params as $key => $value)
	    {
	        if ($url_params == '')
	        {
	            $url_params .= $key . '=' . urlencode($value);
	        }
	        else
	        {
	            $url_params .= '&' . $key . '=' . urlencode($value);
	        }
	    }
	
	    return $redirect_url.$url_params;
	}
	
	/**
	 * Hàm thực hiện xác minh tính chính xác thông tin trả về từ BaoKim.vn
	 * @param $data chứa tham số trả về trên url
	 * @return true nếu thông tin là chính xác, false nếu thông tin không chính xác
	 */
	private function _bk_check_result($data = array())
	{
	    $checksum = $data['checksum'];
	    unset($data['checksum']);
	
	    ksort($data);
	    $str_combined = $this->setting('secure_pass').implode('', $data);
	
	    // Mã hóa các tham số
	    $verify_checksum = strtoupper(md5($str_combined));
	
	    // Xác thực mã của chủ web với mã trả về từ baokim.vn
	    if ($verify_checksum === $checksum)
	    {
	        return TRUE;
	    }
	
	    return FALSE;
	}
}
