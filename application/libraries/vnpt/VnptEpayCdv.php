<?php

/**
 * VNPT Epay CDV Class
 * 
 * Class gui yeu cau len webservice cua VNPT Epay CDV de su dung dich vu
 * 
 * @author		***
 * @version		2014-04-18
 */
class VnptEpayCdv
{
	/**
	 * Thong tin ket noi
	 *
	 * @var array
	 */
	protected $_config = array();

	/**
	 * Doi tuong SoapClient
	 *
	 * @var SoapClient
	 */
	protected $_client = null;
	
	
	/**
	 * Test
	 */
	function a()
	{
		/*
		$request_id = '';
		$api = $this->downloadSoftpin('VTT', 10000, 3, $request_id);
		pr($request_id, FALSE);
		pr($api, FALSE);
		
		$api = $this->reDownloadSoftpin($request_id);
		pr($api, FALSE);
		
		$api = $this->checkTrans($request_id, 2);
		pr($api, FALSE);
		*/
		/*
		$request_id = '';
		$api = $this->topup('VTT', '01676696055', '10000', $request_id);
		pr($request_id, FALSE);
		pr($api, FALSE);
		
		$api = $this->checkTrans($request_id.'_', 1);
		pr($api, FALSE);
		*/
		
		//pr($this->reDownloadSoftpin($this->_create_request_id()));
		
		//pr($this->downloadSoftpin('VTT', 10000, 3));
		
		//pr($this->checkTrans($this->_create_request_id(), 1));
		
		//pr($this->checkOrdersCDV($this->_create_request_id()));
		
		//pr($this->queryBalance());
		
		//pr($this->paymentCDV('VTT', 1, '01676696055', 10000));
		
		//pr( $this->_sign(time()) );
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham khoi dong
	 */
	public function __construct(array $config)
	{
		foreach (array('url', 'partner_name', 'key_sofpin') as $p)
		{
			$this->_config[$p] = $config[$p];
		}
		
		$this->_config['time_out'] = 5*60;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Thanh toan tra sau qua CDV
	 */
	public function paymentCDV($request_id, $provider, $type, $account, $amount)
	{
		$params = array();
		$params['requestId'] 	= $request_id;
		$params['partnerName'] 	= $this->_config['partner_name'];
		$params['provider'] 	= $provider;
		$params['type'] 		= $type;
		$params['account'] 		= $account;
		$params['amount'] 		= (float)$amount;
		$params['timeOut'] 		= $this->_config['time_out'];
		
		return $this->_exec(__FUNCTION__, $params);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay so du
	 */
	public function queryBalance()
	{
		$params = array();
		$params['partnerName'] 	= $this->_config['partner_name'];
		
		return $this->_exec(__FUNCTION__, $params);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Nap tien
	 */
	public function topup($request_id, $provider, $target, $amount)
	{
		$params = array();
		$params['requestId'] 	= $request_id;
		$params['partnerName'] 	= $this->_config['partner_name'];
		$params['provider'] 	= $provider;
		$params['target'] 		= $target;
		$params['amount'] 		= (float)$amount;
		
		return $this->_exec(__FUNCTION__, $params);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra ket qua giao dich paymentCDV()
	 */
	public function checkOrdersCDV($request_id)
	{
		$params = array();
		$params['requestId'] 	= $request_id;
		$params['partnerName'] 	= $this->_config['partner_name'];
		
		return $this->_exec(__FUNCTION__, $params);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra ket qua giao dich topup() va downloadSoftpin()
	 */
	public function checkTrans($request_id, $type)
	{
		$params = array();
		$params['requestId'] 	= $request_id;
		$params['partnerName'] 	= $this->_config['partner_name'];
		$params['type'] 		= $type;
		
		return $this->_exec(__FUNCTION__, $params);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Download ma the
	 */
	public function downloadSoftpin($request_id, $provider, $amount, $quantity)
	{
		$params = array();
		$params['requestId'] 	= $request_id;
		$params['partnerName'] 	= $this->_config['partner_name'];
		$params['provider'] 	= $provider;
		$params['amount'] 		= (float)$amount;
		$params['quantity'] 	= (float)$quantity;
		
		$result = $this->_exec(__FUNCTION__, $params);
		if (isset($result->listCards))
		{
			$result->listCards = $this->_decode_list_cards($result->listCards);
		}
		
		return $result;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra ket qua giao dich paymentCDV()
	 */
	public function reDownloadSoftpin($request_id)
	{
		$params = array();
		$params['requestId'] 	= $request_id;
		$params['partnerName'] 	= $this->_config['partner_name'];
		
		$result = $this->_exec(__FUNCTION__, $params);
		if (isset($result->listCards))
		{
			$result->listCards = $this->_decode_list_cards($result->listCards);
		}
		
		return $result;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Gui yeu cau len webservice
	 */
	protected function _exec($act, array $params)
	{
	    if(isset($params['requestId']))
	    {
	        $params['requestId'] = $this->_config['partner_name'].'_'.$params['requestId'];
	    }
	    
		// Tao sign
		if ( ! isset($params['sign']))
		{
			$params['sign'] = $this->_sign(implode('', $params));
		}
		
		// Call SoapClient
		if ($this->_client === null)
		{
			$this->_client = new SoapClient($this->_config['url']);
		}
		
		// Call to webservice
		try {
			return $this->_client->__soapCall($act, $params);
		}
		catch (Exception $e)
		{
			return false;
		}
		
		return false;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tao request_id
	 */
	protected function _create_request_id()
	{
		$rand = '';
		for ($i = 1; $i <= 6; $i++)
		{
			$rand .= mt_rand(0, 9);
		}
		
		return $this->_config['partner_name'].'_'.time().$rand;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Tao sign ket noi
	 */
	protected function _sign($data)
	{
		$private_key = file_get_contents(__DIR__.'/keys/private_key.pem');
		
		$sign = '';
		openssl_sign($data, $sign, $private_key, OPENSSL_ALGO_SHA1);
		$sign = base64_encode($sign);
		
        return $sign;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Giai ma list cards
	 */
	protected function _decode_list_cards($data)
	{
		$key = substr(md5($this->_config['key_sofpin']), 0, 24);
		
		$list = mcrypt_decrypt("tripledes", $key, base64_decode($data), "ecb", "\0");
		$list = substr($list, 0, (strrpos($list, '}')+1));
		$list = @json_decode($list);
		
		return $list;
	}
	
}