<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'libraries/paycard365/VDCO_SOAPClient.class.php';

class Paycard365_payment_card extends MY_Payment_card
{
	public $code = 'paycard365';

	public $setting = array(
		'username' 	=> '',
		'password' 	=> '',
		'partnerId' 	=> '',
		'mpin' 	=> '',
		'target_name' 	=> '',
		'target_email' 	=> '',
		'target_phone' 	=> '',
	);

	//protected $_url = 'http://119.81.166.195:8080/webservice/TelcoAPI?wsdl';
	protected $_url = 'http://telco.paycard999.com:8080/webservice/TelcoAPI?wsdl';


	// --------------------------------------------------------------------

	/**
	 * Test ket noi
	 */
	public function test()
	{
		$provider = 'viettel';//$this->_get_provider('viettel');
		$code = now();
		$serial = now();

		$result = $this->check($provider, $code, $serial);

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
			return false;
		}
		$request_id =now();

		$username=$this->setting_cur['username'];
		$password=$this->setting_cur['password'];
		$partnerId = $this->setting_cur['partnerId'];
		$mpin=$this->setting_cur['mpin'];

		$Client = new VMS_Soap_Client($this->_url, $username, $password, $partnerId, $mpin);
		//==thong tin khach hang (khong quan trong)
		//$target ten member nap card cua doi tac
		$target = $this->setting_cur['target_name'];
		//$email cua member cua doi tac
		$email = $this->setting_cur['target_email'];
		//phone
		$phone = $this->setting_cur['target_phone'];

		// serial:mathe:menhgia:nhamang  // chu y khong co menh gi thi de trong
		$dataCard = $serial.":".$code."::".$provider;
		$return = $Client->doCardCharge($target, $dataCard, $email, $phone);

		//Khi nap the that bai
		//$return =Array ( [DRemainAmount] => [message] => CardID khong hop le [SSerialNumber] => [status] => -10 [transid] => 2940343 )
		if ($return['status'] != 1)
		{
			//$output = $return['message'];
			$output = 'Số serial hoặc mã thẻ không chính xác, vui lòng kiểm tra lại';//$return['message'];

			return false;
		}

		$amount = (int) $return['DRemainAmount'];
		$output['amount'] = $amount;
		$output['data'] = array(
			'card_type'   	=> $type,
			'card_provider' => $provider,
			'card_code'   	=> $code,
			'card_serial' 	=> $serial,
			'card_amount' 	=> $amount,
			'request_id' 	=> $return['transid'],
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
			'vnmobile'  => 'VNM'
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

}