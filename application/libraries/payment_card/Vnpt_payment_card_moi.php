<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vnpt_payment_card extends MY_Payment_card
{
	public $code = 'vnpt';
	
	public $setting = array(
		'project_id' 	=> '',
		'user_name'	=> '',
		'url' 			=> 'http://megapay.com.vn:8080/megapay_server?',
	);

	/**
	 * Test ket noi
	 */
	public function test()
	{
		$provider = $this->_get_provider('viettel');
		$code = now();
		$serial = now();

		$result = $this->_request($provider, $code, $serial);
		
		pr($result, 0);
	}
	
	/**
	 * Thuc hien kiem tra the
	 * 
	 * @param string $type
	 * @param string $code
	 * @param string $serial
	 * @param array  $output
	 * @return boolean
	 */
	public function check($type, $code, $serial, &$output = array(), &$request_id = '')
	{
		$provider = $this->_get_provider($type);
		
		if ( ! $provider)
		{
			$output = 'Loại thẻ không hợp lệ';

			return false;
		}

		$result = $this->_request($provider, $code, $serial);

		//pr($result);
		if ( ! $this->_check_result($result, $response, $request_id))
		{
			$output = $response['error'];

			return false;
		}

		$output['amount'] = $response['amount'];
		$output['data'] = array(
			'card_type'   	=> $type,
			'card_provider' => $provider,
			'card_code'   	=> $code,
			'card_serial' 	=> $serial,
			'card_amount' 	=> $response['amount'],
			'request_id' 	=> $request_id,
		);

		return true;

	}
	/**
	 * Lay cac loai the ho tro
	 * 
	 * @return array
	 */
	public function get_types()
	{
		$list = $this->_get_list_provider();
		
		return array_keys($list);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay danh sach nha cung cap
	 * 
	 * @return array
	 */
	protected function _get_list_provider()
	{
		return array(
			'viettel' 	=> 'VTT',
			'mobi' 		=> 'VMS',
			'vina' 		=> 'VNP',
			'megacard' 	=> 'MGC',
			'gate' 		=> 'FPT',
			'oncash' 	=> 'ONC',
			'zingcard' 	=> 'ZING',
			//'vnmobile' 	=> 'VNM',
		);
	}
	
	/**
	 * Lay provider tuong ung voi type cua he thong
	 * 
	 * @param string $type
	 * @return string
	 */
	protected function _get_provider($type)
	{
		$data = $this->_get_list_provider();
		
		return isset($data[$type]) ? $data[$type] : false;
	}
	

	
	/**
	 * Request data
	 * 
	 * @param string $provider
	 * @param string $code
	 * @param string $serial
	 * @param string $request_id
	 * @return mixed
	 */
	protected function _request($provider, $code, $serial)
	{

		$config = array(
			'PROJECT_ID' => $this->setting_cur['project_id'], //Tên Merchant Epay cung cấp cho khách hàng
			'USER_NAME' => $this->setting_cur['user_name'],
			'ACCOUNT' => $this->setting_cur['user_name'],// ten nguoi gach the (lay luon tk merchant)
			'URLPAYMENT' =>  $this->setting_cur['url'],   //link webservice của Epay
			'PROCESSING_CODE' => '10002', //Mã Merchant, sau khi đăng ký dịch vụ, Epay sẽ cung cấp cho khách hàng
			'PAYMENT_CHANNEL' => '1'
		);
		$info=[];
		$info['cardSerial']=$serial;
		$info['cardPin']=$code;
		$info['telcoCode']=$provider;

		$service = new ChargingAPIServices($config);
		$response = $service->charging($info);
		$json = json_decode($response, true);
		return $json;
		//pr($json);

	}

	/**
	 * Kiem tra ket qua tra ve tu api
	 * 
	 * @param object $result
	 * @param array  $ouput
	 * @return boolean
	 */
	protected function _check_result($result, &$ouput, &$request_id = null)
	{
		$data = $result['data'];
		// Khong xac dinh
		if(empty($data))
		{
			$ouput['error'] = 'Lỗi không xác định';
			return false;
		}
		$datajson = json_decode($data, true);
		$request_id =$datajson['transid'];


		$status = (int) $result['status'];
		
		// Thanh cong
		if ($status == 0)
		{
			$ouput['amount'] = intval($datajson['payment_amount']);
			
			return true;
		}
		
		// That bai
	    switch($status)
		{

			case 14:
				$ouput['error'] = 'Mã thẻ không đúng';
				return false;
			case 15:
				$ouput['error'] = 'Format thẻ sai';
				return false;
			case 16:
				$ouput['error'] = 'Provider tạm thời lỗi';
				return false;
		}

		$ouput['error'] = 'Lỗi không xác định, chưa cập nhập thông báo';

		return false;
	}
	


}

Class ChargingAPIServices
{
	private $config;

	/*
     * hàm khởi tạo, truyền tham số config vào
     * biến config lấy từ file config.php
     * author: Vu Dinh Phuong
     * date: 13/12/2016
     */
	public function ChargingAPIServices($config)
	{
		$this->config = $config;
	}

	/*
     * gọi sang bên gạch thẻ
     * author: Vu Dinh Phuong
     * date: 13/12/2016
     */
	public function charging($info)
	{
		$project_id = $this->config['PROJECT_ID'];
		$trans_id = $project_id . date("YmdHis") . rand(1, 99999);
		$payment_data = array(
			'serial' => $info['cardSerial'],
			'mpin' => $info['cardPin'],
			'transid' => $trans_id,
			'telcocode' => $info['telcoCode'],
			'username' => $this->config['USER_NAME'],
			'account' => $this->config['ACCOUNT'],
			'payment_channel' => $this->config['PAYMENT_CHANNEL']
		);
		$send_payment_info = array(
			'processing_code' => $this->config['PROCESSING_CODE'],
			'project_id' => $this->config['PROJECT_ID'],
			'data' => json_encode($payment_data));
		$url = $this->config['URLPAYMENT'];
		$url = $url . urlencode('request=' . json_encode($send_payment_info));


		//$url="http://dantri.com.vn";
		$response = $this->get_curl($url);
		//$response = file_get_contents($json_url);
		//print_r($response);die;
		//$data = $json['data'];
		if($response){
			$json = json_decode($response, true);
			$status = $json['status'];
			//$datajson = json_decode($data, true);
			// echo $datajson['status'];die;
			if($status){
				return $response;
			}else{
				echo 'Tham số truyền về không đúng định dạng. Mời bạn liên hệ với nhà cung cấp dịch vụ để biết thêm chi tiết'; die;
			}
		}else{
			//print_r($response);
			echo 'Gạch thẻ không thành công. Mời bạn kiểm tra lại đường truyền và bật các extendsion cần thiết.'; die;
		}
	}

	/*
     * function mã hóa chữ ký
     * author: Vu Dinh Phuong
     * date: 13/12/2016
     */
	private function signature_hash($transId, $config, $data)
	{
		return md5($config['partnerId'].'&'.$data['cardSerial'].'&'.$data['cardPin'].'&'.$transId.'&'.$data['telcoCode'].'&'.md5($config['password']));
	}

	/*
     * function tạo mã giao dịch (transid) theo partner
     * author: Vu Dinh Phuong
     * date: 13/12/2016
     */
	private function get_transid($config)
	{
		return $config['partnerId'].'_'.date('YmdHis').'_'.rand(0, 999);
	}

	/*
     * function parse string response to Array
     * it make developer to easy to process
     * author: Vu Dinh Phuong
     * date: 27/03/2014
     */
	private function parseArray($response)
	{
		$return = array();
		$response = explode('&', $response);
		if(!empty($response)){
			foreach($response as $key => $value){
				$data = explode('=', $value);
				if(!empty($data[1])){
					$return[$data[0]] = $data[1];
				}
			}
			return $return;
		}else{
			return array();
		}
	}

	/*
     * function get curl
     * author: Vu Dinh Phuong
     * date: 13/12/2016
     */
	private function get_curl($url)
	{
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);

		$str = curl_exec($curl);
		if(empty($str)) $str = $this->curl_exec_follow($curl);
		curl_close($curl);

		return $str;
	}
	/*
     * function dùng curl gọi đến link
     * author: Vu Dinh Phuong
     * date: 13/12/2016
     */
	private function curl_exec_follow($ch, &$maxredirect = null)
	{
		$user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)".
			" Gecko/20041107 Firefox/1.0";
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent );

		$mr = $maxredirect === null ? 5 : intval($maxredirect);

		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) {

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		} else {

			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

			if ($mr > 0)
			{
				$original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
				$newurl = $original_url;

				$rch = curl_copy_handle($ch);

				curl_setopt($rch, CURLOPT_HEADER, true);
				curl_setopt($rch, CURLOPT_NOBODY, true);
				curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
				do
				{
					curl_setopt($rch, CURLOPT_URL, $newurl);
					$header = curl_exec($rch);
					if (curl_errno($rch)) {
						$code = 0;
					} else {
						$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
						if ($code == 301 || $code == 302) {
							preg_match('/Location:(.*?)\n/', $header, $matches);
							$newurl = trim(array_pop($matches));

							if(!preg_match("/^https?:/i", $newurl)){
								$newurl = $original_url . $newurl;
							}
						} else {
							$code = 0;
						}
					}
				} while ($code && --$mr);

				curl_close($rch);

				if (!$mr)
				{
					if ($maxredirect === null)
						trigger_error('Too many redirects.', E_USER_WARNING);
					else
						$maxredirect = 0;

					return false;
				}
				curl_setopt($ch, CURLOPT_URL, $newurl);
			}
		}
		return curl_exec($ch);
	}

}