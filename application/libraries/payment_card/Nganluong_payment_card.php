<?php
class NganLuong_payment_card extends MY_Payment_card {
	
	// Url de goi den ngan luong
	var $url = 'https://www.nganluong.vn/mobile_card.api.post.v2.php';

	var $setting 	= array(
	    'merchant_id' 			    => '',
	    'merchant_account' 			=> '',
	    'merchant_password' 		=> '',
	);
	
	public $code = 'nganluong';
	
	// --------------------------------------------------------------------
	
	/**
	 * Test ket noi
	 */
	public function test()
	{
	    $type = 'viettel';
	    $code = now();
	    $serial = now();
	    $output = array();
	    $result = $this->check($type, $code, $serial, $output);
	
	    pr($output, 0);
	}
	
/*
 * ------------------------------------------------------
 *  Card handle
 * ------------------------------------------------------
 */
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

	/**
	 * Thuc hien kiem tra the
	 * 
	 * @param string $type
	 * @param string $code
	 * @param string $serial
	 * @param array  $output
	 * @return boolean
	 */
	public function check($type, $code, $serial, &$output = array())
	{
		// Lay thong tin merchant
		$merchant_id 		= $this->setting_cur['merchant_id'];
		$merchant_account 	= $this->setting_cur['merchant_account'];
		$merchant_password 	= $this->setting_cur['merchant_password'];
		$merchant_password 	= md5($merchant_id.'|'.$merchant_password);
		
		$provider = $this->_get_provider($type);
		
		if ( ! $provider)
		{
		    $output = 'Loại thẻ không hợp lệ';
		
		    return false;
		}
		
		$ref_code = now();
		// Khai bao cac bien
		$params = array(
					'func'					=> 'CardCharge',
					'version'				=> '2.0',
					'merchant_id'			=> $merchant_id,
					'merchant_account'		=> $merchant_account,
					'merchant_password'		=> $merchant_password,
					'pin_card'				=> $code,
					'card_serial'			=> $serial,
					'type_card'				=> $provider,
					'ref_code'				=> $ref_code,
				);
				
		// Thuc hien gui yeu cau
		$this->load->library('curl_library');
		$result = $this->curl_library->post($this->url, $params);
		
		// Phan tich ket qua tra ve
		$api_data = array();
		$status = $this->_nl_check_result($result, $api_data);
		// Khai bao ket qua tra ve
		if (!$status)
		{
		    $output = $api_data;	
		    return false;
		}
		
		$request_id = now();
		$output['amount'] = $api_data['card_amount'];
		$output['data'] = array(
		    'card_type'   	=> $type,
		    'card_provider' => $provider,
		    'card_code'   	=> $code,
		    'card_serial' 	=> $serial,
		    'card_amount' 	=> $api_data['card_amount'],
		    'request_id' 	=> $request_id,
		);
		
		return true;
		
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
	 * Lay danh sach nha cung cap
	 *
	 * @return array
	 */
	protected function _get_list_provider()
	{
	    return array(
	        'viettel' 	=> 'VIETTEL',
	        'mobi' 		=> 'VMS',
	        'vina' 		=> 'VNP',
	    );
	}
	
/*
 * ------------------------------------------------------
 *  Ngan Luong function
 * ------------------------------------------------------
 */
	/**
	 * Kiem tra gia tri tra ve
	 */
	private function _nl_check_result($result, &$data = array())
	{
		// Neu khong ton tai du lieu
		if (!$result)
		{
			return FALSE;
		}
		
		// Tach chuoi
		$arr_result = explode('|', $result);
		if (count($arr_result) != 13)
		{
			return FALSE;
		}
		
		// Phan tich lay cac bien
		$error_code			= $arr_result[0];
		$merchant_id		= $arr_result[1];
		$merchant_account	= $arr_result[2];				
		$pin_card			= $arr_result[3];
		$card_serial		= $arr_result[4];
		$type_card			= $arr_result[5];
		$ref_code			= $arr_result[6];
		$client_fullname	= $arr_result[7];
		$client_email		= $arr_result[8];
		$client_mobile		= $arr_result[9];
		$card_amount		= $arr_result[10];
		$transaction_amount = $arr_result[11];
		$transaction_id		= $arr_result[12];
		
		// Neu thanh cong
		if ($error_code == '00')
		{
			$data['pin_card'] 			= $pin_card;
			$data['card_serial'] 		= $card_serial;
			$data['type_card'] 			= $type_card;
			$data['ref_code'] 			= $ref_code;
			$data['card_amount'] 		= $card_amount;
			$data['transaction_amount'] = $transaction_amount;
			$data['transaction_id'] 	= $transaction_id;
			
			return TRUE;
		}
		
		// Neu that bai
		$data = $this->_nl_get_error($error_code);
		
		return FALSE;
	}
	
	/**
	 * Lay thong bao loi
	 */
	private function _nl_get_error($error_code)
	{
		$errors = array(
					'00'=>  'Giao dịch thành công',
					'99'=>  'Lỗi, tuy nhiên lỗi chưa được định nghĩa hoặc chưa xác định được nguyên nhân',
					'01'=>  'Lỗi, địa chỉ IP truy cập API của NgânLượng.vn bị từ chối',
					'02'=>  'Lỗi, tham số gửi từ merchant tới NgânLượng.vn chưa chính xác (thường sai tên tham số hoặc thiếu tham số)',
					'03'=>  'Lỗi, Mã merchant không tồn tại hoặc merchant đang bị khóa kết nối tới NgânLượng.vn',
					'04'=>  'Lỗi, Mã checksum không chính xác (lỗi này thường xảy ra khi mật khẩu giao tiếp giữa merchant và NgânLượng.vn không chính xác, hoặc cách sắp xếp các tham số trong biến params không đúng)',
					'05'=>  'Tài khoản nhận tiền nạp của merchant không tồn tại',
					'06'=>  'Tài khoản nhận tiền nạp của merchant đang bị khóa hoặc bị phong tỏa, không thể thực hiện được giao dịch nạp tiền',
					'07'=>  'Thẻ đã được sử dụng ',
					'08'=>  'Thẻ bị khóa',
					'09'=>  'Thẻ hết hạn sử dụng',
					'10'=>  'Thẻ chưa được kích hoạt hoặc không tồn tại',
					'11'=>  'Mã thẻ sai định dạng',
					'12'=>  'Sai số serial của thẻ',
					'13'=>  'Mã thẻ và số serial không khớp',
					'14'=>  'Thẻ không tồn tại',
					'15'=>  'Thẻ không sử dụng được',
					'16'=>  'Số lần thử (nhập sai liên tiếp) của thẻ vượt quá giới hạn cho phép',
					'17'=>  'Hệ thống Telco bị lỗi hoặc quá tải, thẻ chưa bị trừ',
					'18'=>  'Hệ thống Telco bị lỗi hoặc quá tải, thẻ có thể bị trừ, cần phối hợp với NgânLượng.vn để tra soát',
					'19'=>  'Kết nối từ NgânLượng.vn tới hệ thống Telco bị lỗi, thẻ chưa bị trừ (thường do lỗi kết nối giữa NgânLượng.vn với Telco, ví dụ sai tham số kết nối, mà không liên quan đến merchant)',
					'20'=>  'Kết nối tới telco thành công, thẻ bị trừ nhưng chưa cộng tiền trên NgânLượng.vn'
				);
		
		$error = (isset($errors[$error_code])) ? $errors[$error_code] : 'Lỗi không xác định';
		
		return $error;
	}
	
}

?>