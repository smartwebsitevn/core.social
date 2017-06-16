<?php
class Pay1_sms_gateway extends MY_Sms_gateway
{
	
	public $setting = array(
	    'access_key' => '',
	    'secret'     => '',
	    'service_ip' => '',
	);
	
	public $code = 'pay1';
    
	/**
	 * Lay danh sach ip cua service
	 *
	 * @see MY_Sms::get_service_ip()
	 */
	public function get_service_ip()
	{
		return $this->setting['service_ip'];
	}

	/**
	 * Kiem tra ket noi xem co hop le hay khong
	 *
	 * @return bool
	 */
	public function check_request()
	{
	    //return true;
		return (
			t('input')->get('access_key') === $this->setting['access_key']
			&& t('input')->get('signature') === $this->_make_signature(t('input')->get())
		);
	}

	/**
	 * Lay input khi nhan thong tin tu service
	 *
	 * @see MY_Sms::get_input_receive()
	 */
	public function get_input_receive($param = null)
	{
		$data = array();
		$data['sms_id'] 	= t('input')->get('request_id');
		$data['message'] 	= t('input')->get('mo_message');
		$data['port'] 		= t('input')->get('short_code');
		$data['phone'] 		= t('input')->get('msisdn');

		return is_null($param) ? $data : $data[$param];
	}

	/**
	 * Gui phan hoi sau khi nhan yeu cau tu service
	 *
	 * @see MY_Sms::make_feedback()
	 */
	public function make_feedback($content)
	{
		$res = [
			'status' => '1',
			'sms'    => $content,
			'type'   => 'text',
		];

		return json_encode($res);
	}

	/**
	 * Tao signature
	 *
	 * @param array $input
	 * @return string
	 */
	protected function _make_signature(array $input)
	{
		$params = [
			'access_key', 'command', 'mo_message', 'msisdn',
			'request_id', 'request_time', 'short_code',
		];

		$data = [];

		foreach ($params as $param)
		{
			$value = array_get($input, $param, 'no_'.$param);

			$data[] = $param.'='.$value;
		}

		$data = implode('&', $data);

		$secret = $this->setting['secret'];

		return hash_hmac('sha256', $data, $secret);
	}
}
