<?php

// Khoi tao obj curl
$curl = new cURL();

/**
 * Ham chay cac uri
 */
function get($uri)
{
	global $curl, $url;
	return $curl->get($url.'/cronjob/'.$uri.'.html');
}

/**
 * cURL class
 */
class cURL {
	
	var $headers = array('Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg', 'Connection: Keep-Alive', 'Content-type: application/x-www-form-urlencoded;charset=UTF-8');
	var $user_agent = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
	var $compression = 'gzip';
	var $cookie_file = 'cookie.txt';
	var $proxy = '';
	var $cookies = FALSE;
	
	function __construct($config = array())
	{
		foreach (array('headers', 'user_agent', 'compression', 'cookie_file', 'proxy', 'cookies') as $p)
		{
			if (isset($config[$p]))
			{
				$this->{$p} = $config[$p];
			}
		}
		
		if ($this->cookies)
		{
			$this->cookie($this->cookie_file);
		}
	}
	
	
	function get($url)
	{
		return $this->curl($url);
	}
	
	function post($url, $params = array())
	{
		if (is_array($params))
		{
			$params = http_build_query($params);
		}
		
		$curl_params = array();
		$curl_params[CURLOPT_POST] 			= 1;
		$curl_params[CURLOPT_POSTFIELDS] 	= $params;
		
		return $this->curl($url, $curl_params);
	}
	
	private function curl($url, $params = array())
	{
		$params[CURLOPT_HTTPHEADER] 		= $this->headers;
		$params[CURLOPT_HEADER] 			= 0;
		$params[CURLOPT_USERAGENT] 			= $this->user_agent;
		$params[CURLOPT_ENCODING ] 			= $this->compression;
		$params[CURLOPT_RETURNTRANSFER] 	= 1;
		
		if ($this->cookies)
		{
			$params[CURLOPT_COOKIEFILE] 	= $this->cookie_file;
			$params[CURLOPT_COOKIEJAR] 		= $this->cookie_file;
		}
		
		if ($this->is_https($url))
		{
			$params[CURLOPT_SSL_VERIFYPEER] = false;
			$params[CURLOPT_SSL_VERIFYHOST] = 0;
		}
		
		
		$curl = curl_init($url);
		
		foreach ($params as $f => $v)
		{
			curl_setopt($curl, $f, $v);
		}
		
		$return = curl_exec($curl);
		
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		
		curl_close($curl);
		
		// Check for 404 (file not found)
		if ($http_code < 200 || $http_code >= 300)
		{
			return FALSE;
		}
		
		return $return;
	}
	
	
	private function cookie($cookie_file)
	{
		if (file_exists($cookie_file))
		{
			$this->cookie_file=$cookie_file;
		}
		else
		{
			fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
			$this->cookie_file = $cookie_file;
			fclose($this->cookie_file);
		}
	}
	
	private function is_https($url)
	{
		if (preg_match('/^https:/i', $url))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	private function error($error)
	{
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		exit();
	}
	
}

?>