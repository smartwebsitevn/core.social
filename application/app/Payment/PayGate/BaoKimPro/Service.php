<?php namespace App\Payment\PayGate\BaoKimPro;

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
require_once(APPPATH.'app/Payment/PayGate/BaoKimPro/Constants.php');
class Service extends PayGateService
{
	/**
	 * Payment co su dung view hien thi rieng luc hien thi cong thanh toan hay khong
	 *
	 * @return bool
	 */

	public function useView()
	{
		return true;
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

		$return_url = $request->url_result;
		$tran_id    = $request->tran->id;

		$url = $this->_bk_create_url($tran_id,  $request->amount,$return_url,$request);
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
		write_file_log('baokimpro_log_result_input.txt', current_url(1));
		write_file_log('baokimpro_log_result_input.txt', t('input')->post());
	    if($request->page == 'notify')
	    {
	        $tran_id = t('input')->post('order_id');
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
	    $_ip = t('input')->ip_address();
	    $result =  t('input')->post();
	    if(is_array($result))
	    {
	        $result['ip'] = $_ip;
	    }
		write_file_log('baokimpro_log_result.txt',current_url(1));
		write_file_log('baokimpro_log_result.txt', $result);
	  
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
	   /* if ( ! in_array($_ip, $ips))
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_ip_is_not_valid');
	        return $this->_ouput($request, $status, $error);
	    }*/
	    
	    // Neu la link user chuyen ve tu baokim sau khi thanh toan xong
	    if (!t('input')->post('order_id'))
	    {
	        $status = PaymentStatus::NONE;
	        return $this->_ouput($request, $status, $error);
	    }

	    // Kiem tra ma so giao dich
	    if ($tran_id != t('input')->post('order_id'))
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_tran_not_exist');;
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra amount
	    $amount_pay = fv(t('input')->post('total_amount'));
	    $amount 	= fv($amount);
	    if ($amount_pay < $amount)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_amount_invalid');
	        return $this->_ouput($request, $status, $error);
	    }
	    
	    // Kiem tra trang thai giao dich
	    if (t('input')->post('transaction_status') != 4)
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
		$payment_tran_id =t('input')->post('transaction_id');
	    $response = [
	        'status'  => $status,
	        'amount'  => $request->amount,
	        'tran_id' => $request->tran->id,
	        'payment_tran_id' => $payment_tran_id,
	        'payment_tran' => ['id' => $payment_tran_id],
	    ];
	     
	    //neu co loi thi them thong bao loi
	    if($error)
	    {
	        $response['error'] = $error;
	    }
	    write_file_log('baokimpro_log_response.txt', $response);
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

	function _bk_create_url($tran_id, $amount, $return_url,PaymentPayRequest $request)
	{

		$tran_info = 'Payment for invoice '.$tran_id;
		$bank_id =t('input')->get('bank_id');
		$bank_id = intval($bank_id);
		$bank_ids = array();
		$this->get_bank_list($bank_ids);
		//echo $bank_id;	pr($bank_ids);
		if (!in_array($bank_id, $bank_ids))
		{
			//$this->data['banks_local'] = $banks;
			//$this->data['url_redirect'] = site_url('payment/checkout_redirect/'.$tran_id.'/'.$request->tran->payment_key);
		//	view('tpl::payment/baokimpro/bank', $this->data);
			// neu chua co thong tin bank thi quay lai phan chon cong
			return $request->tran->user_referer;
		}else{
			$url = array();
			$url['success'] = url_add_uri($return_url, 'success');
			$url['cancel'] 	= url_add_uri($return_url, 'cancel');
			$url['detail'] 	= url_add_uri($return_url, 'detail');

			$params['business']               = strval($this->setting('email_business'));
			$params['bank_payment_method_id'] = $bank_id;
			$params['transaction_mode_id']    = '1'; // 2- trực tiếp
			$params['escrow_timeout']         = 3;

			$params['order_id']      = $tran_id;
			$params['total_amount']  = $amount;
			$params['shipping_fee']  = '0';
			$params['tax_fee']       = '0';
			$params['currency_code'] = 'VND'; // USD

			$params['url_success'] = $url['success'];
			$params['url_cancel']  = $url['cancel'];
			$params['url_detail']  = $url['detail'];

			$user=(object)$request->tran->invoice->info_contact;
			$params['order_description'] = 'Thanh toán đơn hàng từ Website '. base_url() . ' với mã đơn hàng ' . $tran_id;
			$params['payer_name']        = isset($user->name) ? $user->name : 'Customer';
			$params['payer_email']       = isset($user->email) ? $user->email : 'Email';
			$params['payer_phone_no']    = isset($user->phone) ? $user->phone : 'Phone';
			$params['payer_address']     = isset($user->address) ? $user->address : 'Address';
			//pr($params);
			$result = json_decode($this->call_API("POST", $params, BAOKIM_API_PAY_BY_CARD), true);
			if(!empty($result['error'])){
				set_message($result['error']);
				return $request->tran->user_referer;
			}
			$baokim_url = $result['redirect_url'] ? $result['redirect_url'] : $result['guide_url'];
			return $baokim_url;
		}


	}

	/**
	 * Gọi API Bảo Kim thực hiện thanh toán với thẻ ngân hàng
	 *
	 * @param $method Sử dụng phương thức GET, POST cho với từng API
	 * @param $data Dữ liệu gửi đên Bảo Kim
	 * @param $api API được gọi sang Bảo Kim
	 * @param $object WC_Gateway_Baokim_Pro
	 * @var $object WC_Gateway_Baokim_Pro
	 * @return mixed
	 */
	function call_API($method, $data, $api)
	{
		$business    = $this->setting('email_business');
		$username    = $this->setting('api_user');
		$password    = $this->setting('api_password');
		$private_key = PRIVATE_KEY_BAOKIM;
		$server      = BAOKIM_URL;

		$arrayPost = array();
		$arrayGet = array();

		ksort($data);
		if ($method == 'GET') {
			$arrayGet = $data;
		} else {
			$arrayPost = $data;
		}

		$signature = $this->makeBaoKimAPISignature($method, $api, $arrayGet, $arrayPost, $private_key);
		$url = $server . $api . '?' . 'signature=' . $signature . (($method == "GET") ? $this->createRequestUrl($data) : '');

		$curl = curl_init($url);
		//	Form
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST | CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POSTFIELDS, $this->httpBuildQuery($arrayPost));
		}

		$result = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$error  = curl_error($curl);
		//echo '<br>$status:';var_dump($status);
		//echo '<br>$error:';var_dump($error);
		//echo '<br>$result:';var_dump($result);
		if (empty($result)) {
			return array(
				'status' => $status,
				'error' => $error
			);
		}

		return $result;
	}

	/**
	 * Hàm thực hiện việc tạo chữ ký với dữ liệu gửi đến Bảo Kim
	 *
	 * @param $method
	 * @param $url
	 * @param array $getArgs
	 * @param array $postArgs
	 * @param $priKeyFile
	 * @return string
	 */
	private function makeBaoKimAPISignature($method, $url, $getArgs = array(), $postArgs = array(), $priKeyFile)
	{
		if (strpos($url, '?') !== false) {
			list($url, $get) = explode('?', $url);
			parse_str($get, $get);
			$getArgs = array_merge($get, $getArgs);
		}
		ksort($getArgs);
		ksort($postArgs);
		$method = strtoupper($method);

		$data = $method . '&' . urlencode($url) . '&' . urlencode(http_build_query($getArgs)) . '&' . urlencode(http_build_query($postArgs));
		$priKey = openssl_get_privatekey($priKeyFile);
		assert('$priKey !== false');

		$x = openssl_sign($data, $signature, $priKey, OPENSSL_ALGO_SHA1);
		assert('$x !== false');
		return urlencode(base64_encode($signature));
	}

	private function httpBuildQuery($formData, $numericPrefix = '', $argSeparator = '&', $arrName = '')
	{
		$query = array();
		foreach ($formData as $k => $v) {
			if (is_int($k)) $k = $numericPrefix . $k;
			if (is_array($v)) $query[] = httpBuildQuery($v, $numericPrefix, $argSeparator, $k);
			else $query[] = rawurlencode(empty($arrName) ? $k : ($arrName . '[' . $k . ']')) . '=' . rawurlencode($v);
		}
		return implode($argSeparator, $query);
	}

	private function createRequestUrl($data)
	{
		$params = $data;
		ksort($params);
		$url_params = '';
		foreach ($params as $key => $value) {
			if ($url_params == '')
				$url_params .= $key . '=' . urlencode($value);
			else
				$url_params .= '&' . $key . '=' . urlencode($value);
		}
		return "&" . $url_params;
	}

	/*
	 * Lấy danh sách ngân hàng
	 */

	function get_bank_list(&$banks_id = array())
	{
		// Luu vao cache
		t('load')->driver('cache');
		// Lay danh sach trong cache
		$banks_local = t('cache')->file->get('baokimpro_banks_list');
		$banks_id = t('cache')->file->get('baokimpro_banks_id');


		// Neu khong ton tai thi cap nhat cache tu data va get lai
		if ($banks_local === FALSE)
		{
			$banks = $this->get_seller_info();

			$banks_local = array();
			foreach ($banks as $bank)
			{
				if($bank['payment_method_type'] == PAYMENT_METHOD_TYPE_LOCAL_CARD)
				{
					$banks_local[] = $bank;
					$banks_id[] = $bank['id'];
				}
			}
			t('cache')->file->save('baokimpro_banks_id', $banks_id, config('cache_expire_long', 'main'));
			t('cache')->file->save('baokimpro_banks_list', $banks_local, config('cache_expire_long', 'main'));
		}
		return $banks_local;
	}


	/**
	 * Call API GET_SELLER_INFO
	 *  + Create bank list show to frontend
	 *
	 * @internal param $method_code
	 * @return string
	 */
	public function get_seller_info()
	{
		$param = array(
			'business' => $this->setting('email_business'),
		);
		$call_API = $this->call_API("GET", $param, BAOKIM_API_SELLER_INFO );
		if (is_array($call_API)) {
			if (isset($call_API['error'])) {
				pr("<strong style='color:red'>call_API" . json_encode($call_API['error']) . "- code:" . $call_API['status'] . "</strong> - " . "System error. Please contact to administrator");
				return array();
			}
		}

		$seller_info = json_decode($call_API, true);
		if (!empty($seller_info['error'])) {
			pr("<strong style='color:red'>eller_info" . json_encode($seller_info['error']) . "</strong> - " . "System error. Please contact to administrator");
			return array();
		}
		$banks = $seller_info['bank_payment_methods'];
		return $banks;
	}
}
