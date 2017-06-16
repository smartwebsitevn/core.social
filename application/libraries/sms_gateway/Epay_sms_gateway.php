<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Epay_sms_gateway extends MY_Sms_gateway
{
	public $setting	= array(
	    'partner_id' 		=> '',
		'partner_password' 	=> '',
	    'url_feedback'      => 'http://sms.megapayment.net.vn:9099/smsApi',
	    'service_ip'        => '123.29.67.137,123.29.67.138,101.99.16.238,101.99.16.247',
	);

	public $code = 'epay';

	// --------------------------------------------------------------------
	
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
	 * @return boolean
	 */
	public function check_request()
	{
		return (
			t('input')->get('partnerid') == $this->setting['partner_id']
			&& t('input')->get('checksum') == $this->_make_checksum(t('input')->get())
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
		$data['sms_id'] 	= t('input')->get('moid');
		$data['message'] 	= t('input')->get('content');
		$data['port'] 		= t('input')->get('shortcode');
		$data['phone'] 		= t('input')->get('userid');
		
		return is_null($param) ? $data : $data[$param];
	}
	
	/**
	 * Gui phan hoi sau khi nhan yeu cau tu service
	 *
	 * @see MY_Sms::make_feedback()
	 */
	public function make_feedback($content)
	{
		$input = t('input')->get();
		$input['content'] = $content;

		//pr($input);
		$url = $this->_make_url_feedback($input);
		
		$this->_curl($url);
		
		return 'requeststatus=200';
	}
	
	/**
	 * Tao checksum
	 * 
	 * @param array  $input
	 * @param string $type	mo|mt
	 * @return string
	 */
	protected function _make_checksum(array $input, $type = 'mo')
	{
		$input['content'] = str_replace(' ', '+', array_get($input, 'content'));
		
		$result = '';
		
		foreach ($this->_get_checksum_params($type) as $p)
		{
			$result .= array_get($input, $p);
		}
		
		//$result .= md5($this->setting['partner_password']);
		$result .= $this->setting['partner_password'];
		
		return md5($result);
	}
	
	/**
	 * Lay danh sach bien checksum
	 * 
	 * @param string $type	mo|mt
	 * @return array
	 */
	protected function _get_checksum_params($type)
	{
		$params = array('moid', 'shortcode', 'keyword', 'content', 'transdate');
		
		if ($type == 'mt')
		{
			array_unshift($params, 'mtid');
		}
		
		return $params;
	}
	
	/**
	 * Tao url feedback
	 * 
	 * @param array $input
	 * @return string
	 */
	protected function _make_url_feedback(array $input)
	{
		$data = array(
			'partnerid' 		=> $this->setting['partner_id'],
			'moid' 				=> array_get($input, 'moid'),
			'mtid' 				=> $this->_make_mtid(),
			'userid' 			=> array_get($input, 'userid'),
			'receivernumber' 	=> array_get($input, 'userid'),
			'shortcode' 		=> array_get($input, 'shortcode'),
			'keyword' 			=> array_get($input, 'keyword'),
			'content' 			=> str_replace(',', '.', array_get($input, 'content')),
			'messagetype' 		=> 1,
			'totalmessage' 		=> 1,
			'messageindex' 		=> 1,
			'ismore' 			=> 0,
			'contenttype' 		=> 0,
			'transdate' 		=> date('YmdHis'),
			'checksum' 			=> '',
			'amount' 			=> array_get($input, 'amount'),
		);
		
		$data['checksum'] = $this->_make_checksum($data, 'mt');
		
		return $this->setting['url_feedback'].'?'.http_build_query($data);
	}
	
	/**
	 * Tao mtid
	 * 
	 * @return string
	 */
	protected function _make_mtid()
	{
		return $this->setting['partner_id'] . date('YmdHi') . rand(0, 99999);
	}
	
	/**
	 * Thuc hien curl
	 * 
	 * @param string $url
	 * @return string
	 */
	protected function _curl($url)
	{
		$this->_log_access(' - send_feedback:'.$url,false);
		$result = $this->_get_curl($url);
		$this->_log_access(' - receive_feedback:'.$result,false);
		log_message('error', 'epay_sms: '.$result);
		
		return $result;
	}
	
	/*
	 * function get curl
	 * author: Nguyen Tat Loi
	 * date: 26/03/2014
	 */
	protected function _get_curl($url)
	{
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		
		$str = curl_exec($curl);
			if (empty($str)) $str = $this->_curl_exec_follow($curl);
		curl_close($curl);
		
		return $str;
	}
	/*
	 * function dùng curl g�?i đến link
	 * author: Nguyen Tat Loi
	 * date: 26/03/2014
	 */
	protected function _curl_exec_follow($ch, &$maxredirect = null)
	{
		$user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)" . " Gecko/20041107 Firefox/1.0";
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		
		$mr = $maxredirect === null ? 5 : intval($maxredirect);
		
		if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off'))
		{
			
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
			curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		else
		{
			
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
					if (curl_errno($rch))
					{
						$code = 0;
					}
					else
					{
						$code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
						if ($code == 301 || $code == 302)
						{
							preg_match('/Location:(.*?)\n/', $header, $matches);
							$newurl = trim(array_pop($matches));
							
							if (! preg_match("/^https?:/i", $newurl))
							{
								$newurl = $original_url . $newurl;
							}
						}
						else
						{
							$code = 0;
						}
					}
				}
				while ($code && -- $mr);
				
				curl_close($rch);
				
				if (! $mr)
				{
					if ($maxredirect === null) trigger_error('Too many redirects.', E_USER_WARNING);
					else $maxredirect = 0;
					
					return false;
				}
				curl_setopt($ch, CURLOPT_URL, $newurl);
			}
		}
		return curl_exec($ch);
	}

	protected function _log_access($message,$time=true)
	{

		$sms_log = '';
		if($time)
			$sms_log= $this->input->ip_address () .': ' .get_date(now(),"full").' => ';
		$sms_log .= $message;
		file_put_contents('sms_log.txt',$sms_log.PHP_EOL,FILE_APPEND );

	}
}