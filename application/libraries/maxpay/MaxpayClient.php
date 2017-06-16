<?php

/**
 * Thư viện tích hợp thẻ cào maxpay.vn
 * 
 * @version 1.0
 */
class MaxpayClient
{
	protected $merchant_id;

	protected $secret_key;

	const SERVICE_URL = "https://maxpay.vn/apis/card/charge?";

	/**
	 * Khoi tao doi tuong
	 * 
	 * @param array $config        	
	 */
	public function __construct(array $config)
	{
		$this->merchant_id = $config['merchant_id'];
		
		$this->secret_key = $config['secret_key'];
	}

	/**
	 * Hàm thực hiện gọi sang maxpay.vn để gạch thẻ
	 * 
	 * @param $merchant_txn_id mã giao dịch duy nhất của merchant
	 * @param $cardType loại thẻ
	 * @param $pin mã thẻ (pin)
	 * @param $serial số seri
	 * @return mixed
	 */
	public function charge($merchant_txn_id, $cardType, $pin, $serial)
	{
		$args = array(
			'merchant_id' => $this->merchant_id,
			'pin' => $pin,
			'seri' => $serial,
			'card_type' => $cardType,
			'merchant_txn_id' => $merchant_txn_id
		);
		
		// Create checksum security code
		$args['checksum'] = $this->_createChecksum($args);
		
		// Build request url
		$requestUrl = self::SERVICE_URL . http_build_query($args);
		
		// Call maxpay.vn's web service
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $requestUrl);
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/ca.crt");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		$output = curl_exec($ch);
		
		// If curl error?
		if ($output === false)
		{
			$response = array(
				'code' => 99,
				'message' => 'Your curl error: ' . curl_error($ch)
			);
			curl_close($ch);
			return $response;
		}
		
		curl_close($ch);
		
		$response = json_decode($output, true);
		// If json format error?
		if ($response === false)
		{
			return array(
				'code' => 99,
				'message' => $output
			);
		}
		
		return $response;
	}

	/**
	 * Hàm thực hiện tạo mã bảo mật checksum
	 * 
	 * @param
	 *        	$args
	 * @return string
	 */
	private function _createChecksum($args)
	{
		ksort($args);
		return hash_hmac('SHA1', implode('|', $args), $this->secret_key);
	}
	
}