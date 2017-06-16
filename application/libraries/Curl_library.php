<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * cURL Library
 *
 * @author		***
 * @version		2014-02-24
 */
class Curl_library {
	
	/**
	 * cURL GET method
	 * @param 	string 	$url		Dia chi url
	 * @param 	array 	$params		cURL params
	 * @return	mixed	FALSE (404) || html cua url
	 */
	function get($url, array $params = array(), &$result = '')
	{
		// Get curl result
		$params['url'] 		= $url;
		$params['method'] 	= 'GET';
		$result = $this->curl($params);
		
		// Check not found
		if ($this->_is_not_found($result['http_code']))
		{
			return FALSE;
		}
		
		return $result['body'];
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * cURL POST method
	 * @param 	string 	$url		Dia chi url
	 * @param 	mixed 	$data		Du lieu post len url
	 * @param 	array 	$params		cURL params
	 * @return	mixed	FALSE (404) || html cua url
	 */
	function post($url, $data, array $params = array(), &$result = '')
	{
		// Get curl result
		$params['url'] 			= $url;
		$params['method'] 		= 'POST';
		$params['post_fields'] 	= $data;
		$result = $this->curl($params);
		
		// Check not found
		if ($this->_is_not_found($result['http_code']))
		{
			return FALSE;
		}
		
		return $result['body'];
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Chay cURL
	 * @param array $params = array(
	 * 		'url' => '',
     *  	'host' => '',
     *  	'header' => '',
     * 		'method' => '',
     *		'referer' => '',
     *   	'cookie' => '',
     *		'post_fields' => '',
     * 		'user_pass' => '',
     *  	'timeout' => 0,
     *  	'curl_opt' => array(),
     * 	);
     * @return array 'header', 'body', 'http_code'
	 */
	function curl(array $params)
    {
    	// Khoi tao curl
   		$curl = $this->_init($params);
        
   		// Thuc hien curl
        return $this->_exec($curl);
    }
    
	// --------------------------------------------------------------------
	
    /**
     * Lay gia tri cua bien trong header
     * @param string $header	Gia tri cua header
     * @param string $param		Bien muon lay gia tri
     */
    function get_header($header, $param = '')
    {
    	$values = array();
    	$arr = explode("\n", $header);
    	foreach ($arr as $row)
    	{
    		$row = explode(': ', $row);
    		
    		$p = $row[0];
    		$p = trim($p);
    		if ($p == '') continue;
    		
    		unset($row[0]);
    		$v = implode(': ', $row);
    		$v = trim($v);
    		
    		$values[$p] = $v;
    	}
    	
    	if ($param != '')
    	{
    		return (isset($values[$param])) ? $values[$param] : FALSE;
    	}
    	
    	return $values;
    }
    
	// --------------------------------------------------------------------
	
	/**
	 * Khoi tao cURL
	 */
	protected function _init($params)
    {
       	// Tao config mac dinh
        $params = set_default_value($params, array(
        	'url', 'host', 'header', 'method', 'referer', 
        	'cookie', 'post_fields', 'user_pass', 'timeout', 'curl_opt',
        ));
        
        $header = array(
			'Accept: text/html, application/xhtml+xml, application/xml, image/gif, image/x-bitmap, image/jpeg, image/pjpeg', 
			'Connection: Keep-Alive', 
			'Content-type: application/x-www-form-urlencoded;charset=UTF-8',
        );
        
        $user_agent = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
        $encoding 	= 'gzip';
        
        
        // Tao option cho curl
        $curl_opt = array();
        $curl_opt[CURLOPT_HEADER] = TRUE;
        $curl_opt[CURLOPT_VERBOSE] = TRUE;
        $curl_opt[CURLOPT_RETURNTRANSFER] = TRUE;
        $curl_opt[CURLOPT_URL] = $params['url'];
		$curl_opt[CURLOPT_USERAGENT] = $user_agent;
        $curl_opt[CURLOPT_ENCODING] = $encoding;
        
        // Header
        if ($params['host'])
        {
        	$header[] = 'Host: '.$params['host'];
        }
        
        if ($params['header'])
        {
        	$params['header'] = ( ! is_array($params['header'])) ? array($params['header']) : $params['header'];
        	$header = array_merge($header, $params['header']);
        }
		$curl_opt[CURLOPT_HTTPHEADER] = $header;
        
        // Method
        $params['method'] = strtoupper($params['method']);
    	if ($params['method'] == 'POST')
        {
        	if (is_array($params['post_fields']))
        	{
        		$params['post_fields'] = http_build_query($params['post_fields']);
        	}
        	
			$curl_opt[CURLOPT_POST] = TRUE;
			$curl_opt[CURLOPT_POSTFIELDS] = $params['post_fields'];
        }
        
        elseif ($params['method'] == 'HEAD')
        {
			$curl_opt[CURLOPT_NOBODY] = TRUE;
        }
        
        
        // Referer
        if ($params['referer'])
        {
        	$curl_opt[CURLOPT_REFERER] = $params['referer'];
        }
        
        // Cookie
        if ($params['cookie'] !== FALSE)
        {
			$_cookie_file = $this->_create_cookie_file($params['cookie']);
			$curl_opt[CURLOPT_COOKIEFILE] 	= $_cookie_file;
			$curl_opt[CURLOPT_COOKIEJAR] 	= $_cookie_file;
        }
        
        // Login
        if ($params['user_pass'])
        {
        	if (is_array($params['user_pass']))
        	{
        		$params['user_pass'] = implode(':', $params['user_pass']);
        	}
        	
    		$curl_opt[CURLOPT_USERPWD] = $params['user_pass'];
        }
        
        // Timeout
		if ($params['timeout'])
		{
			$curl_opt[CURLOPT_TIMEOUT] = $params['timeout'];
		}
    	
        // SSL
        if (preg_match('#^https:#i', $params['url']))
        {
     		$curl_opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
        	$curl_opt[CURLOPT_SSL_VERIFYHOST] = 0;
        	//$curl_opt[CURLOPT_SSLVERSION] = 3;
        }
        
        // Cap nhat curl_opt
        if (is_array($params['curl_opt']))
        {
        	foreach ($params['curl_opt'] as $p => $v)
        	{
        		$curl_opt[$p] = $v;
        	}
        }
        
        // Gan option cho curl
        $curl = curl_init();
        foreach ($curl_opt as $p => $v)
    	{
    		@curl_setopt($curl, $p, $v);
    	}
    	
    	return $curl;
    }
    
	// --------------------------------------------------------------------
	
    /**
     * Thuc hien cURL
     */
	protected function _exec($curl)
    {
    	// Thuc hien curl va lay error
        $res 	= curl_exec($curl);
        $error 	= curl_error($curl);
        
        // Khoi tao bien tra ve
        $result = array(
			'header' 	=> '',
			'body' 		=> '',
			'http_code' => '',
			'error' 	=> '',
        );
        
        // Xu ly gia tri tra ve
        if ($error)
        {
            $result['error'] = $error;
        }
        else 
        {
	        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	        $result['header'] 		= substr($res, 0, $header_size);
	        $result['body'] 		= substr($res, $header_size );
	        $result['http_code']	= curl_getinfo($curl, CURLINFO_HTTP_CODE);
        }
        
        return $result;
    }
    
	// --------------------------------------------------------------------
	
	/**
	 * Tao file cookie
	 */
    protected function _create_cookie_file($filename)
    {
    	$filename = ( ! $filename) ? 'cookie' : $filename;
   		$cookie_file = __DIR__.'/../cookie/'.$filename;
		
		if ( ! file_exists($cookie_file))
		{
			$CI =& get_instance();
			$CI->load->helper('file');
			
			if (write_file($cookie_file, ''))
			{
				@chmod($cookie_file, DIR_WRITE_MODE);
			}
		}
		
		return $cookie_file;
    }
    
	// --------------------------------------------------------------------
	
    /**
     * Kiem tra http_code co phai la not found hay khong
     */
     protected function _is_not_found($http_code)
    {
    	return ($http_code < 200 || $http_code >= 300) ? TRUE : FALSE;
    }
    
}