<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vnpt_payment_card extends MY_Payment_card
{
	public $code = 'vnpt';
	
	public $setting = array(
		'PartnerID' 	=> '',
		'PartnerCode'	=> '',
		'UserName' 		=> '',
		'Pass' 			=> '',
		'MPIN' 			=> '',
		'url' 			=> 'http://103.68.243.233/ChargingGW/services/Services?wsdl',
		'uri' 			=> 'http://113.161.78.134/',
	);

	/*protected $_url = 'http://cttcorp.net/ChargingGW/services/Services?wsdl'; // Url that
	//protected $_url = 'http://115.78.133.42:9090/CardChargingGW/services/Services?wsdl'; // Url test

	protected $_uri = 'http://113.161.78.134/';*/
	
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

		$result = $this->_request($provider, $code, $serial, $request_id);

		
		if ( ! $this->_check_result($result, $response))
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
	 * Tao doi tuong soap client
	 * 
	 * @return nusoap_client
	 */
	protected function _soap()
	{
		require_once APPPATH.'libraries/nusoap/Nusoap.php';
		
		$soap = new SoapClient(null, array(
			'location' 	=> $this->setting_cur['url'],
			'uri'		=> $this->setting_cur['uri'],
		));
		
		return $soap;
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
	protected function _request($provider, $code, $serial, &$request_id = null)
	{
		require_once APPPATH."libraries/vnpt_card/Entries.php";
		
		$request_id = $this->setting_cur['PartnerCode'].'_'.random_string('unique');
		
		$CardCharging = new CardCharging();
		$CardCharging->m_UserName  = $this->setting_cur['UserName'];
		$CardCharging->m_PartnerID = $this->setting_cur['PartnerID'];
		$CardCharging->m_MPIN      = $this->setting_cur['MPIN'];
		$CardCharging->m_Target    = $this->_create_pincode();
		$CardCharging->m_Card_DATA = $serial.":".$code.":"."0".":".$provider;
		$CardCharging->m_SessionID = "";
		$CardCharging->m_Pass      = $this->setting_cur['Pass'];
		$CardCharging->soapClient  = $this->_soap();
		$CardCharging->m_TransID   = $request_id;
		
		return $CardCharging->CardCharging_();
	}

	/**
	 * Kiem tra ket qua tra ve tu api
	 * 
	 * @param object $result
	 * @param array  $ouput
	 * @return boolean
	 */
	protected function _check_result($result, &$ouput)
	{
		// Khong xac dinh
		if ( ! isset($result->m_Status))
		{
			$ouput['error'] = 'Lỗi không xác định';
			
			return false;
		}
		
		$status = (int) $result->m_Status;
		
		// Thanh cong
		if ($status == 1)
		{
			$ouput['amount'] = intval($result->m_RESPONSEAMOUNT);
			
			return true;
		}
		
		// That bai
	    switch($status)
		{
			case -1:
				$ouput['error'] = 'Thẻ đã sử dụng';
				return false;
				break;
			break;
			case -2:
				$ouput['error'] = 'Thẻ đã bị khóa';
				return false;
				break;
			break;
			case -3:
				$ouput['error'] = 'Thẻ hết hạn sử dụng';
				return false;
				break;
			break;
			case -4:
				$ouput['error'] = 'Thẻ chưa kích hoạt';
				return false;
				break;
			break;
			case -10:
				$ouput['error'] = 'Mã thẻ sai định dạng';
				return false;
				break;
			break;
			case -12:
				$ouput['error'] = 'Thẻ không tồn tại';
				return false;
				break;
			break;
			case -99:
				$ouput['error'] = 'Mã thẻ VMS không đúng định dạng';
				return false;
				break;
			break;
			case 5:
				$ouput['error'] = 'Partner Nhập sai mã thẻ quá 5 lần';
				return false;
				break;
			break;
			case 6:
				$ouput['error'] = 'Sai thông tin Partner';
				return false;
				break;
			break;
			case 99:
				$ouput['error'] = 'Chưa nhận được kết quả từ nhà cung cấp';
				return false;
				break;
			break;
			case -24:
				$ouput['error'] = 'Dữ liệu Card Data không đúng';
				return false;
				break;
			break;
			case -11:
				$ouput['error'] = 'Nhà cung cấp không tồn tại';
				return false;
				break;
			break;
			case 8:
				$ouput['error'] = 'Sai IP';
				return false;
				break;
			break;
			case 3:
				$ouput['error'] = 'Sai Session';
				return false;
				break;
			break;
			case 7:
				$ouput['error'] = 'Session hết hạn';
				return false;
				break;
			break;
			case 4:
				$ouput['error'] = 'Thẻ không dùng được';
				return false;
				break;
			break;
			case 13:
				$ouput['error'] = 'Hệ thống tạm thời bận';
				return false;
				break;
			break;
			case 0:
				$ouput['error'] = 'Giao dịch bị lỗi';
				return false;
				break;
			break;
			case 9:
				$ouput['error'] = 'Tạm thời khóa VMS quá tải';
				return false;
				break;
			break;
			case 10:
				$ouput['error'] = 'Hệ thống nhà cung cấp lỗi';
				return false;
				break;
			break;
			case 11:
				$ouput['error'] = 'Nhà cung cấp tạm thời khóa Partner do lỗi hệ thống';
				return false;
				break;
			break;
			case 12:
				$ouput['error'] = 'Trùng mã giao dịch';
				return false;
				break;
			break;
			case 50:
				$ouput['error'] = 'Thẻ đã được sử dụng hoặc không tồn tại';
				return false;
				break;
			break;
			case 51:
				$ouput['error'] = 'Seri không đúng';
				return false;
				break;
			break;
			case 52:
				$ouput['error'] = 'Mã thẻ và seri không khớp';
				return false;
				break;
			break;
			case 53:
				$ouput['error'] = 'Mã thẻ hoặc seri không đúng';
				return false;
				break;
			break;
			case 54:
				$ouput['error'] = 'Card chưa được kích hoạt';
				return false;
				break;
			break;
			case 55:
				$ouput['error'] = 'Card tạm thời bị block 24h';
				return false;
				break;
			break;
		}

		$ouput['error'] = 'Lỗi không xác định';

		return false;
	}
	
	/**
	 * Tao pincode ngau nhien
	 */
	protected function _create_pincode()
	{
		$pincode = "";
		for ($i = 1; $i <= 15; $i++)
		{
			$pincode .= rand(0, 9);
		}
		
		return $pincode;
	}

}