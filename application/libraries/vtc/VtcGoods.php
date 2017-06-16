<?php

/**
 * VTC Goods Library
 *
 * @version 2015-03-25
 */
class VtcGoods
{
	/**
	 * Thong tin config
	 * 
	 * @var array
	 */
	protected $config = array();
	
	/**
	 * Doi tuong cua nusoap_client
	 * 
	 * @var nusoap_client
	 */
	protected $client;
	
	/**
	 * Url service
	 * 
	 * @var string
	 */
	//protected $url = 'http://sandbox3.vtcebank.vn/VTCAPIService/WS/GoodsPaygate.asmx?wsdl&op=RequestTransaction';
	protected $url = 'https://pay.vtc.vn/WS/GoodsPaygate.asmx?wsdl&op=RequestTransaction';
	
	// --------------------------------------------------------------------

	/**
	 * Test
	 */
	public function _test()
	{
		$this->setConfig(array(
			'partnerCode' 	=> 'mpaytest',
			'keyDecode' 	=> 'Vietenter',
		));
		
		//$v = $this->topupTelco('VTC0056', '01678476116', '10000');
		
		//$v = $this->topupPartner('VTC0115', 'luckyboy_hp', '20000');
		
		//$v = $this->buyCard('VTC0027', '10000', 2);
		//$v = $this->getCard('VTC0027', '10000', $v['VTCTransID']); // 2316
		
		//$v = $this->checkAccount('VTC0056', '01678476116');
		
		//$v = $this->getBalance();
		
		//$v = $this->getQuantityCard('VTC0027');
		
		//$v = $this->checkPartnerTransCode('1409883217931215', '1');
		
		$v = $this->getHistoryTrans('2014/09/04', '2014/09/05');
		
		pr($v);
	}
	
	/**
	 * Khoi tao doi tuong
	 * 
	 * @param array $config
	 */
	public function __construct(array $config = array())
	{
		if (count($config))
		{
			$this->setConfig($config);
		}
	}
	
	/**
	 * Gan config
	 * 
	 * @param array $config
	 */
	public function setConfig(array $config)
	{
		foreach (array('partnerCode', 'keyDecode') as $p)
		{
			if ( ! isset($config[$p]))
			{
				throw new InvalidArgumentException("The {$p} param is required");
			}
		}
		
		$this->config = $config;
	}
	
	/**
	 * Lay config
	 * 
	 * @return array
	 */
	public function getConfig($param = NULL)
	{
		if (is_null($param))
		{
			return $this->config;
		}
		
		return (isset($this->config[$param])) ? $this->config[$param] : NULL;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Nap tien cho dien thoai di dong
	 *
	 * @param string $request_id
	 * @param string $serviceCode
	 * @param string $account
	 * @param float  $amount
	 * @return array
	 */
	public function topupTelco($request_id, $serviceCode, $account, $amount)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Account'] 		= $account;
		$data['Amount'] 		= (float) $amount;
		$data['TransDate'] 		= $this->makeTransDate();
		$data['OrgTransID'] 	= $request_id;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'], 
			$data['Account'],
			$data['Amount'],
			$this->getPartnerCode(),
			$data['TransDate'],
			$data['OrgTransID'],
		));
		
		$result = $this->call('TopupTelco', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'OrgTransID', 'PartnerBalance'));
		
		return $result;
	}

	/**
	 * Nap tien vao tai khoan game cua khach hang
	 *
	 * @param string $request_id
	 * @param string $serviceCode
	 * @param string $account
	 * @param float  $amount
	 * @param string $description
	 * @return array|false
	 */
	public function topupPartner($request_id, $serviceCode, $account, $amount, $description = '')
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Account'] 		= $account;
		$data['Amount'] 		= (float) $amount;
		$data['Description'] 	= $description;
		$data['TransDate'] 		= $this->makeTransDate();
		$data['OrgTransID'] 	= $request_id;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['Account'],
			$data['Amount'],
			$this->getPartnerCode(),
			$data['TransDate'],
			$data['OrgTransID'],
		));
		
		$result = $this->call('TopupPartner', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'OrgTransID', 'PartnerBalance'));
	
		return $result;
	}

	/**
	 * Mua ma the
	 *
	 * @param string $request_id
	 * @param string $serviceCode
	 * @param float  $amount
	 * @param int    $quantity
	 * @return array
	 */
	public function buyCard($request_id, $serviceCode, $amount, $quantity)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Amount'] 		= (float) $amount;
		$data['Quantity'] 		= (int) $quantity;
		$data['TransDate'] 		= $this->makeTransDate();
		$data['OrgTransID'] 	= $request_id;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['Amount'],
			$data['Quantity'],
			$this->getPartnerCode(),
			$data['TransDate'],
			$data['OrgTransID'],
		));
		
		$result = $this->call('BuyCard', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'OrgTransID', 'VTCTransID', 'PartnerBalance'));
		
		return $result;
	}
	
	/**
	 * Lay ma the
	 * 
	 * @param string  $serviceCode
	 * @param float   $amount
	 * @param string  $VTCTransID
	 * @return array
	 */
	public function getCard($serviceCode, $amount, $VTCTransID)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Amount'] 		= (float) $amount;
		$data['OrgTransID'] 	= $VTCTransID;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['Amount'],
			$this->getPartnerCode(),
			$data['OrgTransID'],
		));
		
		$result = $this->call('GetCard', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'OrgTranID', 'ListCard'));
		
		return $result;
	}
	
	/**
	 * Kiem tra tai khoan co ton tai hay khong
	 * 
	 * @param string $serviceCode
	 * @param string $account
	 * @return array
	 */
	public function checkAccount($serviceCode, $account)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Account'] 		= $account;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['Account'],
			$this->getPartnerCode(),
		));
		
		$result = $this->call('CheckAccount', $data);
		$result = $this->makeResult($result, array('ResponseCode'));
		
		return $result;
	}
	
	/**
	 * Lay so du hien tai
	 *
	 * @return array
	 */
	public function getBalance()
	{
		$data = array();
		$data['DataSign'] = $this->makeDataSign(array(
			$this->getPartnerCode(),
		));
		
		$result = $this->call('GetBalance', $data);

		if (count($result) == 1)
		{
			array_unshift($result, '1', '');
		}

		return $this->makeResult($result, array('ResponseCode', 'OrgTransID', 'PartnerBalance'));
	}
	
	/**
	 * Lay so luong so trong kho
	 * 
	 * @param string $serviceCode
	 * @return array
	 */
	public function getQuantityCard($serviceCode)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['OrgTransID'] 	= $this->makeTransId();
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['OrgTransID'],
		));
		
		$result = $this->call('GetQuantityCard', $data);
		$result = $this->makeResult($result, array('ListQuantityCard'));
		
		if ( ! empty($result['ListQuantityCard']))
		{
			$result['ListQuantityCard'] = @json_decode($result['ListQuantityCard']);
		}
		
		return $result;
	}
	
	/**
	 * Kiem tra xem giao dich da duoc thuc hien hay chua
	 * 
	 * @param string $transId
	 * @param int	 $type
	 * 		1	= Check mua ma the
	 * 		2	= Check cac giao dich khac
	 * @return array
	 */
	public function checkPartnerTransCode($transId, $type)
	{
		$data = array();
		$data['OrgTransID'] 	= $transId;
		$data['CheckTypeServiceCode'] = $type;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$this->getPartnerCode(),
			$data['OrgTransID'],
			$data['CheckTypeServiceCode'],
		));
		
		$result = $this->call('CheckPartnerTransCode', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'VTCTransID', 'Orgtransid', 'Account', 'Amount', 'Date'));
		
		return $result;
	}
	
	/**
	 * Lay lich su giao dich
	 * 
	 * @param string $fromDate	yyyy/mm/dd
	 * @param string $toDate	yyyy/mm/dd
	 * @return array
	 */
	public function getHistoryTrans($fromDate, $toDate)
	{
		$data = array();
		$data['Account'] 		= $this->getPartnerCode();
		$data['FromDate'] 		= $fromDate;
		$data['ToDate'] 		= $toDate;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$this->getPartnerCode(),
			$data['FromDate'],
			$data['ToDate'],
		));
		
		$result = $this->call('GetHistoryTrans', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'Data'));

		if ( ! empty($result['Data']))
		{
			$result['Data'] = @json_decode($result['Data']);
		}
		
		return $result;
	}
	
	/**
	 * Lay so tien no cua khach ung voi hoa don tuong ung
	 * 
	 * @param string $serviceCode
	 * @param string $account		Ma hoa don can thanh toan
	 * @return array
	 */
	public function getDebitAmount($serviceCode, $account)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Account'] 		= $account;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['Account'],
			$this->getPartnerCode(),
		));
		
		$result = $this->call('GetDebitAmount', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'Account', 'AmountBill', 'Extent'));
		
		return $result;
	}
	
	/**
	 * Lay gia ban da co triet khau cua san pham
	 * 
	 * @param string $serviceCode
	 * @param float  $amount
	 * @return array
	 */
	public function getSalePrice($serviceCode, $amount)
	{
		$data = array();
		$data['ServiceCode'] 	= $serviceCode;
		$data['Amount'] 		= (float) $amount;
		$data['DataSign'] 		= $this->makeDataSign(array(
			$data['ServiceCode'],
			$data['Amount'],
			$this->getPartnerCode(),
		));
		
		$result = $this->call('GetSalePrice', $data);
		$result = $this->makeResult($result, array('ResponseCode', 'Amount', 'SalePrice', 'Extent'));
		
		return $result;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Gui yeu cau len service
	 * 
	 * @param string $commandType
	 * @param array  $requesData
	 * @param string $version
	 * @return array
	 */
	protected function call($commandType, array $requesData, $version = '1.0')
	{
		// Khoi tao nusoap_client
		if (is_null($this->client))
		{
			require_once APPPATH.'libraries/nusoap/Nusoap.php';
			
			$this->client = new nusoap_client($this->url, TRUE);
		}
		
		// Goi den service
		$result = $this->client->call('RequestTransaction', array(
			'partnerCode' 	=> $this->config['partnerCode'],
			'commandType' 	=> $commandType,
			'requesData' 	=> $this->makeRequesData($requesData),
			'version' 		=> $version,
		));

		$result = (isset($result['RequestTransactionResult'])) ? $result['RequestTransactionResult'] : '';

		// Phan tich ket qua tra ve
		if (in_array($commandType, array('GetCard')))
		{
			return $this->{'parseResult'.$commandType}($result);
		}
		elseif (in_array($commandType, array('CheckPartnerTransCode', 'GetHistoryTrans')))
		{
			return $this->parseResult($result, '|');
		}
		
		return $this->parseResult($result);
	}
	
	/**
	 * Tao xml RequesData
	 * 
	 * @param array $requesData
	 * @return string
	 */
	protected function makeRequesData(array $requesData)
	{
		$xml = '<?xml version="1.0" encoding="utf-8" ?>';
		$xml .= '<RequestData>';
		
		foreach ($requesData as $p => $v)
		{
			$xml .= "<{$p}>$v</{$p}>";
		}
		
		$xml .= '</RequestData>';
		
		return $xml;
	}
	
	/**
	 * Phan tich ket qua tra ve tu service
	 * 
	 * @param string $result
	 * @param string $delimiter		Dau phan cach giua cac gia tri khi xac thuc sign
	 * @return array
	 */
	protected function parseResult($result, $delimiter = NULL)
	{
		$data = explode('|', $result);
		$sign = array_pop($data);
		
		$data_sign = ( ! is_null($delimiter)) ? implode($delimiter, $data) : $data;

		if ( ! $this->verifyDataSign($data_sign, $sign))
		{
			$data[0] = '-sign';
		}

		return $data;
	}
	
	/**
	 * Phan tich ket qua tra ve tu service khi GetCard
	 *
	 * @param string $result
	 * @return false|array
	 */
	protected function parseResultGetCard($result)
	{
		$secret 	= $this->getConfig('keyDecode');
		$key 		= substr(md5($secret), 0, 24);
		$text 		= base64_decode($result);
		$data 		= mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $text, MCRYPT_MODE_ECB, '12345678');
		$block 		= mcrypt_get_block_size('tripledes', 'ecb');
		$packing 	= ord($data{strlen($data) - 1});
		
		if ($packing && ($packing < $block))
		{
			for ($P = strlen($data) - 1; $P >= strlen($data) - $packing; $P--)
			{
				if (ord($data{$P}) != $packing)
				{
					$packing = 0;
				}
			}
		}
		
		$my_string = strtolower(substr($data, 0, strlen($data) - $packing));
		$arrResult = explode('|', $my_string, 3);
		if (count($arrResult) >= 3)
		{
			$arrResult[2] = $this->parseListCard($arrResult[2]);
			
			return $arrResult;
		}
		
		return FALSE;
	}
	
	/**
	 * Phan tich list card
	 * 
	 * @param string $str
	 * @return array
	 */
	protected function parseListCard($str)
	{
		$list 	= array();
		$arr 	= explode('|', $str);
		
		foreach ($arr as $r)
		{
			$r = explode(':', $r);
			$r = $this->makeResult($r, array('CardCode', 'CardSerial', 'ExpriceDate'));
			
			$list[] = $r;
		}
		
		return $list;
	}
	
	/**
	 * Xu ly gia tri tra ve
	 * 
	 * @param array $result
	 * @param array $params
	 * @return array
	 */
	protected function makeResult($result, array $params)
	{
		$arr = array();
		foreach ($params as $i => $p)
		{
			$arr[$p] = (isset($result[$i])) ? $result[$i] : '';
		}
		
		if (isset($arr['ResponseCode']))
		{
			$arr['ResponseMsg'] = $this->getResponseMsg($arr['ResponseCode']);
		}
		
		return $arr;
	}
	
	/**
	 * Tao thoi gian giao dich
	 * 
	 * @return string
	 */
	protected function makeTransDate()
	{
		return date("YmdHis");
	}
	
	/**
	 * Tao ma giao dich
	 * 
	 * @return string
	 */
	protected function makeTransId()
	{
		$rand = '';
		for ($i = 1; $i <= 6; $i++)
		{
			$rand .= mt_rand(0, 9);
		}
		
		return time().$rand;
	}
	
	/**
	 * Tao data sign
	 *
	 * @param string|array $data
	 * @return string
	 */
	protected function makeDataSign($data)
	{
		$data = (is_array($data)) ? implode('-', $data) : $data;
		
		$privateKey = $this->getPrivateKey();
		$privateKeyId = openssl_get_privatekey($privateKey);
		
		openssl_sign($data, $sign, $privateKeyId);
		openssl_free_key($privateKeyId);
		
		$sign = base64_encode($sign);
		
		return $sign;
	}
	
	/**
	 * Xac thuc data sign
	 * 
	 * @param string|array 	$data
	 * @param string 		$sign
	 * @return boolean
	 */
	protected function verifyDataSign($data, $sign)
	{
		$data = (is_array($data)) ? implode('-', $data) : $data;
		$sign = base64_decode($sign);
		
		$publicKey = $this->getPublicKey();
		$publicKeyId = openssl_get_publickey($publicKey);
		
		$ok = openssl_verify($data, $sign, $publicKeyId);
		openssl_free_key($publicKeyId);
		
		return ($ok == 1) ? TRUE : FALSE;
	}
	
	/**
	 * Lay Private Key
	 * 
	 * @return string
	 */
	protected function getPrivateKey()
	{
		$fp = fopen(__DIR__ . '/keys/privateKey.pem', 'r');
		$privateKey = fread($fp, 8192);
		fclose($fp);
		
		return $privateKey;
	}
	
	/**
	 * Lay Public Key
	 * 
	 * @return string
	 */
	protected function getPublicKey()
	{
		$fp = fopen(__DIR__ . '/keys/publicKey.pem', 'r');
		$publicKey = fread($fp, 8192);
		fclose($fp);
		
		return $publicKey;
	}
	
	/**
	 * Lay partnerCode
	 * 
	 * @return string
	 */
	protected function getPartnerCode()
	{
		return $this->getConfig('partnerCode');
	}
	
	/**
	 * Lay Response Msg
	 * 
	 * @param int $code
	 * @return string
	 */
	protected function getResponseMsg($code)
	{
		$data = array();
		$data['1'] = 'Giao dịch thành công';
		$data['0'] = 'Giao dịch chưa xác định ';
		$data['-1'] = 'Lỗi hệ thống';
		$data['-55'] = 'Số dư tài khoản không đủ để thực hiện giao dịch này';
		$data['-99'] = 'Lỗi chưa xác định';
		$data['-302'] = 'Partner không tồn tại hoặc đang tạm dừng hoạt động';
		$data['-304'] = 'Dịch vụ này không tồn tại hoặc đang tạm dừng';
		$data['-305'] = 'Chữ ký không hợp lệ';
		$data['-306'] = 'Mệnh giá không hợp lệ hoặc đang tạm dừng';
		$data['-307'] = 'Tài khoản nạp tiền không tồn tại hoặc không hợp lệ';
		$data['-308'] = 'RequesData không hợp lệ';
		$data['-309'] = 'Ngày giao dịch truyền không đúng';
		$data['-310'] = 'Hết hạn mức cho phép sử dụng dịch vụ này';
		$data['-311'] = 'RequesData hoặc PartnerCode không đúng';
		$data['-315'] = 'Phải truyền CommandType';
		$data['-316'] = 'Phải truyền version';
		$data['-317'] = 'Số lượng thẻ phải lớn hơn 0';
		$data['-318'] = 'ServiceCode không đúng';
		$data['-320'] = 'Hệ thống gián đoạn';
		$data['-348'] = 'Tài khoản bị Block';
		$data['-350'] = 'Tài khoản không tồn tại';
		$data['-500'] = 'Loại thẻ này trong kho hiện đã hết hoặc tạm ngừng xuất';
		$data['-501'] = 'Giao dịch nạp tiền không thành công';

		$data['-sign'] = 'Sign Không hợp lệ';

		return (isset($data[$code])) ? $data[$code] : 'Không xác định';
	}
	
}