<?php

/**
 * SMS NHANH
 *
 * @author		hoangvantuyencnt@gmail.com
 * @version		2016-04-02
 */
class Smsnhanh_sms_otp extends MY_Sms_otp
{
	public $code = 'smsnhanh';

	public $setting = [
		'access_key' => '',
		'url'        => 'http://api.smsnhanh.com/v2',
	];


	/**
	 * Gui sms
	 *
	 * @param string $phone
	 * @param string $message
	 * @param string $result
	 * @return bool
	 */
	function send($phone, $message, &$result = null)
	{
		$url = rtrim($this->setting['url'], '/?') . '/?';

		$url .= http_build_query([
			'Accesskey'   => $this->setting['access_key'],
			'PhoneNumber' => $phone,
			'Text'        => $message,
			'Type'        => 'VIP', // Gửi tin ngay lập tức
		]);

		$res = $this->_request($url);

		$result = json_encode($res);

		return $res && $res['Status'] != 'Error';
	}

	/**
	 * Thuc hien gui request
	 *
	 * @param string $url
	 * @return array|null
	 */
	protected function _request($url)
	{
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);

		return json_decode($result, true) ?: null;
	}

}