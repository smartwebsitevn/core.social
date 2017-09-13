<?php namespace App\Payment\PayGate\Vnpay;

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
	 * Payment co su dung view hien thi rieng luc hien thi cong thanh toan hay khong
	 *
	 * @return bool
	 */
	
	public function useView()
	{
		$this->get_bank_list();
		return true;
	}
	
	public function get_bank_list()
	{
		// Luu vao cache
		t('load')->driver('cache');
		// Lay danh sach trong cache
		$banks = t('cache')->file->get('vnpay_banks_list');
		if(!$banks)
		{
			$banks = array(
					"VIETCOMBANK" => "VIETCOMBANK",
					"NCB"         => "NCB",
					"SACOMBANK"   => "SacomBank  ",
					"EXIMBANK"    => "EximBank ",
					"MSBANK"      =>  "MSBANK ",
					"NAMABANK"    =>  "NamABank ",
					"VISA"        =>   	"VISA/MASTER",
					"VNMART"      =>   "Vi dien tu VnMart",
					"VIETINBANK"  => "Vietinbank  ",
					"VIETCOMBANK" =>  "VCB ",
					"HDBANK"      => "HDBank",
					"DONGABANK"   =>   "Dong A",
					"TPBANK"      =>  "TPBank",
					"OJB"         => "OceanBank",
					"BIDV"        =>  "BIDV ",
					"TECHCOMBANK" =>  "Techcombank ",
					"VPBANK"      =>  "VPBank ",
					"AGRIBANK"    =>  "Agribank ",
					"MBBANK"      =>  "MBBank ",
					"ACB"         =>  "ACB ",
					"OCB"         =>  "OCB ",
			);
			t('cache')->file->save('vnpay_banks_list', $banks, config('cache_expire_long', 'main'));
		}
		return $banks;
	}
	
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
		$amount    = $request->amount;
		$tran_info  = 'Payment for invoice '.$tran_id;
	
		$date     = date('Y-m-d H:i:s');
		$terminal = $this->setting('terminal');
		$key      = $this->setting('key');
		
		$vnp_Url       = $this->setting('url');
		$vnp_Returnurl = $return_url;
		$vnp_Merchant  = $this->setting('merchant');
		$vnp_AccessCode = $terminal;
		$hashSecret     = $key;
		$vnp_TxnRef     = $tran_id;
		$vnp_OrderInfo  = $tran_info;
		$vnp_OrderType  = $this->setting('ordertype');
		$vnp_Amount     = $amount * 100;
		$vnp_Locale     = $this->setting('language');
		
		$vnp_IpAddr    = t('input')->ip_address();
		$vnp_UserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$newtimestamp  = strtotime($date . '+ 15 minute');
		$ExpiredDate   = date('Y-m-d H:i:s', $newtimestamp);

		$inputData = array(
				"vnp_TmnCode"   => $vnp_AccessCode,
				"vnp_Amount"    => $vnp_Amount,
				"vnp_Command"   => "pay",
				"vnp_CreateDate" => date('YmdHis'),
				"vnp_CurrCode"  => "VND",
				"vnp_IpAddr"    => $vnp_IpAddr,
				"vnp_Locale"    => $vnp_Locale,
				"vnp_Merchant"  => $vnp_Merchant,
				"vnp_OrderInfo" => $vnp_OrderInfo,
				"vnp_OrderType" => $vnp_OrderType,
				"vnp_ReturnUrl" => $vnp_Returnurl,
				"vnp_TxnRef"    => $vnp_TxnRef,
				"vnp_Version"   => "2.0.0",
		);
		
		$get_bank_list = $this->get_bank_list();
		$bank_id   = t('input')->get('bank_id');
		$bank_id   = strval($bank_id);
		if (isset($get_bank_list[$bank_id])) {
			$inputData['vnp_BankCode'] = $bank_id;
		}
		
		ksort($inputData);
		$query = "";
		$i = 0;
		$hashdata = "";
		foreach ($inputData as $key => $value) {
			if ($i == 1) {
				$hashdata .= '&' . $key . "=" . $value;
			} else {
				$hashdata .= $key . "=" . $value;
				$i = 1;
			}
			$query .= urlencode($key) . "=" . urlencode($value) . '&';
		}
		
		$vnp_Url = $vnp_Url . "?" . $query;
		if (isset($hashSecret)) {
			$vnpSecureHash = md5($hashSecret . $hashdata);
			$vnp_Url .= 'vnp_SecureHashType=MD5&vnp_SecureHash=' . $vnpSecureHash;
		}
		
        return PaymentPayResponse::redirect($vnp_Url);
	}
	
	/**
	 * Lay payment result input
	 *
	 * @param PaymentResultInputRequest $request
	 * @return PaymentResultInputResponse|null
	 */
	public function paymentResultInput(PaymentResultInputRequest $request)
	{
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
	    $_ip    = t('input')->ip_address();
	    $result =  t('input')->get();
	    if(is_array($result))
	    {
	        $result['ip'] = $_ip;
	    }
	    $this->save_payment_result($tran_id, $result);
       
	    
	    //lay cac bien request
	    $amount     = $request->amount;
	    $tran_id    = $request->tran->id;
	    
	    //mac dinh NONE
	    $status = PaymentStatus::NONE;
	    $error  = '';
	    
	    //process return data
	    $hashSecret     = $this->setting('key');
	    $vnp_SecureHash = t('input')->get('vnp_SecureHash');
	   
	    unset($result['vnp_SecureHashType']);
	    unset($result['vnp_SecureHash']);
	    ksort($result);
	    $i = 0;
	    $hashData = "";
	    foreach ($result as $key => $value) {
	    	if ($i == 1) {
	    		$hashData = $hashData . '&' . $key . "=" . $value;
	    	} else {
	    		$hashData = $hashData . $key . "=" . $value;
	    		$i = 1;
	    	}
	    }
	    $secureHash = md5($hashSecret . $hashData);
	
	    // Kiem tra ma so giao dich
	    if ($tran_id != $result['vnp_TxnRef'])
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_tran_not_exist');;
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra amount
	    $amount_pay = fv($result['vnp_Amount']);
	    $amount 	= fv($amount);
	    if ($amount_pay < $amount)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_amount_invalid');
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    if ($secureHash == $vnp_SecureHash) {
	    	if ($result['vnp_ResponseCode'] == '00') {
	    		 //neu thanh cong
				 $status = PaymentStatus::SUCCESS;  
	    	} else {
	    		 $status = PaymentStatus::FAILED;
	             $error  = lang('error_payment_result_unsuccessful');
	    	}
	    } else {
	    	$status = PaymentStatus::FAILED;
	    	$error  = "Chu ky khong hop le";
	    }
	    
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
	    
	    if (is_url_notiy)
	    {
	        return PaymentResultOutputResponse::content(json_encode([]));
	    }
		return null;
	}

}
