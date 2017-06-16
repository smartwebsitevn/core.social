<?php namespace App\Payment\PayGate\Onepay;

use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Library\Payment\PaymentResultInputRequest;
use App\Payment\Library\Payment\PaymentResultInputResponse;
//use App\Payment\Library\Payment\PaymentResultOutputRequest;
//use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;

class Service extends PayGateService
{
    public $version		= 2;

    public $url 		= array(
    'http://mtf.onepay.vn/onecomm-pay/vpc.op', // Test noi dia
    'https://mtf.onepay.vn/vpcpay/vpcpay.op', //Test quoc te
    'https://onepay.vn/onecomm-pay/vpc.op', // Live noi dia
    'https://onepay.vn/vpcpay/vpcpay.op'  // Live quoc te
    ); 
    
	/**
	
	 * Tên: NGUYEN HONG NHUNG
     * Số thẻ: 6868682607535021
     * Tháng/Năm phát hành: 12/08
     * Mã OTP: 1234567890
	 * 
	 * Thuc hien thanh toan
	 *
	 * @param PaymentPayRequest $request
	 * @return PaymentPayResponse
	 */
	public function paymentPay(PaymentPayRequest $request)
	{
	    //load file thanh phan
	   
		$data = array();
		$data['vpc_OrderInfo'] 		= $request->tran->id;
		$data['vpc_Amount'] 		= intval($request->amount) * 100;
		$data['vpc_ReturnURL'] 		= $request->url_result;
		if($this->setting('ptype')=='INTER'){
		$data['AgainLink'] 	= $request->url_result;
		}
		$url = $this->_onepay_create_url($data);
        return PaymentPayResponse::redirect($url);
	}
	
	/*
	 * Lay payment result input
	 *
	 * @param PaymentResultInputRequest $request
	 * @return PaymentResultInputResponse|null
	 */
	public function paymentResultInput(PaymentResultInputRequest $request)
	{
	    write_file_log('log_result_input.txt', $this->CI->input->get());
	    if($request->page == 'notify')
	    {
	        $tran_id = $this->CI->input->get('vpc_OrderInfo');
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

	    $result =  $this->CI->input->get();
	    write_file_log('log_result.txt', $result);
	    
	    //lay cac bien trong invoice
	    $amount     = intval($request->amount) * 100;
	    $tran_id    = $request->tran->id;
	    
	    
	   
	    $payment_tran_id = $result['vpc_TransactionNo'];  /// So giao dich ben cong thanh toan onepay
	    $error  = '';
	    $status = PaymentStatus::NONE;
	    //Kiem tra du lieu
	    if(!isset($result['vpc_OrderInfo']) || $tran_id != $result['vpc_OrderInfo'] ) {
	        $error ='Hacker';
	        $status = PaymentStatus::NONE;
	        return $this->_ouput($request, $status, $error, $payment_tran_id);
	    }
        //Ma so giao dich cua Onepay, su dung de doi soat giao dich
	    
	    // Kiem tra amount
	    $amount_return_onepay = intval($result['vpc_Amount']);
		if ($amount != $amount_return_onepay)
		{
		    $status = PaymentStatus::FAILED;
		    
		    $error = $this->onepay_get_response($result['vpc_TxnResponseCode']);
		    
		    return $this->_ouput($request, $status, $error, $payment_tran_id);
		}
	    
	    // Kiem tra trang thai giao dich
	    if ($result['vpc_TxnResponseCode'] != 0)
	    {
	        $status = PaymentStatus::FAILED;
	        $error = $this->onepay_get_response($result['vpc_TxnResponseCode']);
	        return $this->_ouput($request, $status, $error, $payment_tran_id);
	    }
	    
	    //neu thanh cong
	    $status = PaymentStatus::SUCCESS;
	    $error = $this->onepay_get_response($result['vpc_TxnResponseCode']);
	    return $this->_ouput($request, $status, $error, $payment_tran_id);
	    
	}
	
	/**
	 * Du lieu tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	private function _ouput(PaymentResultRequest $request, $status, $error = '', $payment_tran_id)
	{
	    $response = [
	        'status'  => $status,
	        'amount'  => $request->amount,
	        'tran_id' => $request->tran->id,
	        'payment_tran_id' => $payment_tran_id,           ///DU LIEU NAY LA BAT BUOC DE PHUC VU DOI SOAT.
	        'payment_tran' => ['id' => $payment_tran_id],
	    ];
	     
	    //neu co loi thi them thong bao loi
	    if($error)
	    {
	        $response['error'] = $error;
	    }
	    write_file_log('log_response.txt', $response);
	    return new PaymentResultResponse($response);
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

	
	//Tao url de gui sang onepay - Create url posting to onepay
	private function _onepay_create_url($data)
	{
		// Thêm các biến mặc định của onepay
        $data['vpc_Merchant'] 	= $this->setting('merchant_id');
		$data['vpc_AccessCode'] = $this->setting('access_code');
		$data['vpc_Version'] 	= $this->version;
		$data['vpc_Command'] 	= 'pay';
		
		if($this->setting('ptype')=='LOCAL'){
		    $data['vpc_Currency'] 	= 'VND';
		    $data['vpc_Locale'] 	= 'vn';
		} else {
		    $data['vpc_Locale'] 	= 'en';
		    $data['Title']=$this->setting('shopname');
		}
		
		$data['vpc_MerchTxnRef']= date('YmdHis').rand();
		
		// Lấy giá trị url cổng thanh toán
		if($this->setting('ptype')=='LOCAL' && $this->setting('sandbox')=='1'){
		    $vpcURL = $this->url['0'] . "?";
		}elseif ($this->setting('ptype')=='LOCAL' && $this->setting('sandbox')=='0'){
		    $vpcURL = $this->url['2'] . "?";
		}elseif ($this->setting('ptype')=='INTER' && $this->setting('sandbox')=='1') {
		    $vpcURL = $this->url['1'] . "?";	    
		}else {
		    $vpcURL = $this->url['3'] . "?";
		}
		
		// Khởi tạo chuỗi dữ liệu mã hóa trống
		$stringHashData = "";
		
		// Sắp xếp dữ liệu theo thứ tự a-z trước khi nối lại
		ksort($data);
		
		// Đặt tham số đếm = 0
		$appendAmp = 0;
		
		foreach($data as $key => $value)
		{
		    if (strlen($value) > 0)
		    {
		        // Tạo chuỗi đầu dữ liệu những tham số có dữ liệu
		        if ($appendAmp == 0)
		        {
		            $vpcURL .= urlencode($key) . '=' . urlencode($value);
		            $appendAmp = 1;
		        }
		        else
		        {
		            $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
		        }
		        
		        // Sử dụng cả tên và giá trị tham số để mã hóa
		        if ((strlen($value) > 0) && (substr($key, 0,4) == "vpc_"))
		        {
				    $stringHashData .= $key . "=" . $value . "&";
				}
		    }
		}
		
		// Xóa ký tự & ở thừa ở cuối chuỗi dữ liệu mã hóa
		$stringHashData = rtrim($stringHashData, "&");
		
		// Thêm giá trị chuỗi mã hóa dữ liệu được tạo ra ở trên vào cuối url
		if (strlen($this->setting('secure_secret')) > 0)
		{
		    // Mã hóa dữ liệu
		    $vpcURL .= "&vpc_SecureHash=" . strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*', $this->setting('secure_secret'))));
		}

		return $vpcURL;
		

		
	}

	
	
	private function _onepay_check_result($data)
	{
	    /*
	     * Get and remove the vpc_TxnResponseCode code from the response fields as we
	     * do not want to include this field in the hash calculation
	     */
	    if (!isset($data["vpc_SecureHash"]))
	    {
	        return FALSE;
	    }
	    
	    $vpc_Txn_Secure_Hash = $data["vpc_SecureHash"];
	    unset($data["vpc_SecureHash"]);
	    
	    // Set a flag to indicate if hash has been validated
	    $errorExists = false;
	    
	    if (strlen($this->setting['secure_secret']) > 0 && $data["vpc_TxnResponseCode"] != "7" && $data["vpc_TxnResponseCode"] != "No Value Returned")
	    {
	        // Khởi tạo chuỗi mã hóa rỗng
	        $stringHashData = "";
	        	
	        // Sort all the incoming vpc response fields and leave out any with no value
	        foreach ($_GET as $key => $value)
	        {
	            // Chỉ lấy các tham số bắt đầu bằng "vpc_" và khác trống và không phải chuỗi hash code trả về
	            if ($key != "vpc_SecureHash" && (strlen($value) > 0) && (substr($key, 0, 4) == "vpc_"))
	            {
	                $stringHashData .= $key . "=" . $value . "&";
	            }
	        }
	        	
	        // Xóa dấu & thừa cuối chuỗi dữ liệu
	        $stringHashData = rtrim($stringHashData, "&");
	        	
	        	
	        // Tạo chuỗi mã hóa
	        if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(hash_hmac('SHA256', $stringHashData, pack('H*',$this->setting['secure_secret']))))
	        {
	            if ($data["vpc_TxnResponseCode"] == '0')
	            {
	                // Secure Hash validation succeeded, add a data field to be displayed later
	                return TRUE;
	            }
	        }
	    }
	    
	    // Secure Hash validation failed, add a data field to be displayed later
	    return FALSE;
	    
	}
	
	
	private function onepay_get_response($response_code)
	{
	    switch ($response_code)
	    {
	        case "0" :
	            {
	                $result = "Giao dịch thành công - Approved";
	                break;
	            }
	        case "1" :
	            {
	                $result = "Ngân hàng từ chối giao dịch - Bank Declined";
	                break;
	            }
	        case "3" :
	            {
	                $result = "Mã đơn vị không tồn tại - Merchant not exist";
	                break;
	            }
	        case "4" :
	            {
	                $result = "Không đúng access code - Invalid access code";
	                break;
	            }
	        case "5" :
	            {
	                $result = "Số tiền không hợp lệ - Invalid amount";
	                break;
	            }
	        case "6" :
	            {
	                $result = "Mã tiền tệ không tồn tại - Invalid currency code";
	                break;
	            }
	        case "7" :
	            {
	                $result = "Lỗi không xác định - Unspecified Failure ";
	                break;
	            }
	        case "8" :
	            {
	                $result = "Số thẻ không đúng - Invalid card Number";
	                break;
	            }
	        case "9" :
	            {
	                $result = "Tên chủ thẻ không đúng - Invalid card name";
	                break;
	            }
	        case "10" :
	            {
	                $result = "Thẻ hết hạn/Thẻ bị khóa - Expired Card";
	                break;
	            }
	        case "11" :
	            {
	                $result = "Thẻ chưa đăng ký sử dụng dịch vụ - Card Not Registed Service(internet banking)";
	                break;
	            }
	        case "12" :
	            {
	                $result = "Ngày phát hành/Hết hạn không đúng - Invalid card date";
	                break;
	            }
	        case "13" :
	            {
	                $result = "Vượt quá hạn mức thanh toán - Exist Amount";
	                break;
	            }
	        case "21" :
	            {
	                $result = "Số tiền không đủ để thanh toán - Insufficient fund";
	                break;
	            }
	        case "99" :
	            {
	                $result = "Người sủ dụng hủy giao dịch - User cancel";
	                break;
	            }
	        default :
	            {
	                $result = "Giao dịch thất bại - Failured";
	                break;
	            }
	    }
	
	    return $result;
	}
	
	
	
	
}
