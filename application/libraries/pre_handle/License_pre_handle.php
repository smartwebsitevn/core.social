<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class License_pre_handle extends MY_Pre_handle
{
	/**
	 * Boot
	 */
	public function boot()
	{
	   
	    //$this->_get_key();
	    return;
	    
		$this->check_domain();
		
		$this->check_ip();
		
		//$this->check_key();
	}
	
	/**
	 * Lay thong tin license
	 * 
	 * @param null|string $key
	 * @return mixed
	 */
	protected function license($key = null)
	{
		$license = array(
			'domain' 	=> array('phim3hd.net','www.phim3hd.net'),
			'ip' 		=> '',
		);
		
		return is_null($key) ? $license : $license[$key];
	}
	
	/**
	 * Kiem tra domain
	 */
	protected function check_domain()
	{
		$license_domain = $this->license('domain');
		
		if ( ! $license_domain) return;
		
		$license_domain = (array) $license_domain;
		$license_domain = array_map('strtolower', $license_domain);
		
		$domain = $this->input->server('SERVER_NAME');
		$domain = strtolower($domain);
		if ( ! in_array($domain, $license_domain))
		{
			$this->error('domain', $domain, $license_domain);
		}
	}
	
	/*
	 * Lay key config
	 */
	function _get_key()
	{
	    $license_domain = $this->license('domain');
	    $domain = strtolower($license_domain);
	     
	    $y = '2016';
	    $key = $domain."+nencer+2010"."+".$y;
	    $license_key = md5($key);
	    pr($license_key);
	}

	/**
	 * Kiem tra key
	 */
	protected function check_key()
	{
	    $domain = $this->input->server('SERVER_NAME');
	    $domain = strtolower($domain);
	   
	    $y = '2016';
	    $key = $domain."+nencer+2010"."+".$y;
	    $license_key = md5($key);
	    if ( $license_key != config('encryption_key', 'main'))
	    {
	        $this->error('key', $domain, $license_key);
	    }
	}
	
	
	/**
	 * Kiem tra ip
	 */
	protected function check_ip()
	{
		$license_ip = $this->license('ip');
		
		if ( ! $license_ip) return;
		
		$license_ip = (array) $license_ip;
		
		$ip = $this->input->server('SERVER_ADDR');
		
		if ( ! in_array($ip, $license_ip))
		{
			$this->error('ip', $ip, $license_ip);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @param string|array $license
	 */
	protected function error($key, $value, $license)
	{
		$license = is_array($license) ? implode(', ', $license) : $license;
		
		show_error("Invalid license {$key}. {$key}: [{$value}]. License {$key}: [{$license}]");
	}
	
}