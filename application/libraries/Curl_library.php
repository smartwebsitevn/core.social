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
	/**
	 * Down Load
	 *
	 * Down file v? m�y
	 *
	 * @param	string	url file c?n down
	 * @param	string	thu m?c ch?a file khi down v?
	 * @param	bool	replace file cu hay kh�ng?
	 */
	public function download($from, $to , $replace = FALSE)
	{
		// L?y url chu?n c?a $to
		$arr = explode('\?', $to);
		$to = $arr[0];

		$local = $to;
		if ($replace === FALSE && file_exists($local))
			return TRUE;
		$content = $this->read($from, FALSE);
		if ($content)
		{
			$this->write($to, $content);
			return TRUE;
		}
		return FALSE;
	}
	/**
	 * Read File
	 *
	 * L?y n?i dung c?a m?t file
	 *
	 * @param string du?ng d?n d?n file
	 * @param string c� gi?i m� code html kh�ng?
	 */
	function read($url, $decode = TRUE)
	{
		$content = '';

		// N?u l� file t? trang web kh�c
		if ($this->is_http($url))
		{
			$url = preg_replace('/^https/is', 'http', $url);
			$url = str_replace(' ', '%20', $url);
		}
		// N?u l� file tr�n m�y
		else
		{
			// L?y url chu?n c?a file tr�n m�y
			$arr = explode('\?', $url);
			$url = $arr[0];
			$url = $url;
		}
		$content = $this->get($url);
		// Gi?i m� html
		if ($decode === TRUE)
		{
			$content = htmlspecialchars_decode($content);
		}

		return $content;
	}

	/**
	 * Write File
	 *
	 * Ghi n?i dung v�o m?t file
	 *
	 * @param string url file
	 * @param string n?i dung file
	 */
	function write($url, $data)
	{
		$arr = explode('\?', $url);
		$url = $arr[0];
		$url = $url;
		$info = $this->get_info($url);
		// T?o thu m?c ch?a file
		$this->create_dir($info['path']);

		// Luu file
		$fp = fopen($url, "w");
		flock($fp, 2);
		fwrite($fp, $data);
		flock($fp, 1);
		fclose($fp);
	}
	/**
	 * Create Dir
	 *
	 * T?o thu m?c
	 *
	 * @param	string	t�n c�y thu m?c
	 */
	function create_dir($path)
	{
		if (!$path) return FALSE;

		$path = str_replace('\\', '/', $path);
		$path = trim($path, '/');
		$arr = explode('/', $path);
		$dir = '';

		foreach ($arr as $folder)
		{
			$dir .= $folder.'/';
			if (!file_exists($dir))
			{
				mkdir($dir);
			}
		}
	}
	/**
	 * Is http
	 *
	 * Ki?m tra li�n k?t c� ph?i d?ng http hay kh�ng?
	 *
	 * @param string li�n k?t c?n ki?m tra
	 */
	function is_http($url)
	{
		if (preg_match('/^([\w\d]+?):\/\//is', $url)) return TRUE;
		else return FALSE;
	}

	/**
	 * Get Url Info
	 *
	 * L?y th�ng tin file t? url
	 *
	 * @param	string	url file
	 */
	function get_info($url)
	{
		// Neu url c� dang http
		if ($this->is_http($url))
		{

			// Th�m k� t? / v�o sau url n?u url ch? l� domain (http://domain.ext)
			if (!preg_match('/:\/\/([^\/]+?)\//is', $url))
			{
				$url .= '/';
			}

			$match = array();
			preg_match('/^([\w\d]+?):\/\/([^\/]+?)\/(.*?)$/is', $url, $match);
			$info['http'] = $match[1];
			$info['domain'] = $match[2];
			$pathinfo = pathinfo($match[3]);
		}
		// N?u l� li�n k?t thu?ng
		else
		{
			$info['http'] = '';
			$info['domain'] = '';
			$pathinfo = pathinfo($url);
		}
		//echo '<br>url='.$url;
		//echo '<br>';pr($pathinfo);
		$info['path'] = ($pathinfo['dirname'] != '.') ? $pathinfo['dirname'] : '';
		$info['basename'] = $pathinfo['basename'];
		$info['filename'] = $pathinfo['filename'];
		$info['ext'] = $pathinfo['extension'];
		$info['url'] = ($info['path']) ? $info['path'].'/'.$pathinfo['basename'] : $pathinfo['basename'];
		$info['namelocal'] = ($info['path']) ? str_replace('/', '_', $info['path']).'_' : '';
		$info['namelocal'] .= ($info['filename']) ? $info['filename'].'.html' : 'index.html';

		return $info;
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