<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_gateway_mod extends MY_Mod
{
	/**
	 * Kiem tra ton tai
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function exists($key)
	{
		if ( ! $key) return false;
		
		$url = APPPATH.'libraries/sms_gateway/'.$key.'_sms_gateway'.EXT;
		
		return file_exists($url);
	}
	
	/**
	 * Kiem tra da duoc cai dat hay chua
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function installed($key)
	{
		if ( ! $key) return FALSE;
		
		$list_installed = model('sms_gateway')->get_list_installed();
		
		return (in_array($key, $list_installed));
	}
	
	
	/**
	 * Lay config
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function config($key, $default = null)
	{
	    return config($key, 'sms_gateway', $default);
	}
	
	/**
	 * Tao sms
	 * @param 	string 	$mod		Loai sms muon tao
	 * @param 	string	$param		Bien dau vao
	 * @return	array	array(syntax, port, price)
	 */
	public function create($mod, $param = NULL)
	{
	    // Lay config
	    $sms_key 	= $this->config('key');
	    $sms_mod 	= $this->config('mods');
	    $sms_mod 	= $sms_mod[$mod];
	
	    // Tao sms
	    $syntax 	= $sms_key.' '.$sms_mod[0];
	    $syntax 	.= ( ! is_null($param)) ? ' '.$param : '';
	    $syntax		= $this->handle_syntax($syntax);
	    $port 		= $sms_mod[1];
	    $price 		= $this->get_price($port);
	
	    return array($syntax, $port, $price);
	}
	
	/**
	 * Phan tich cu phap sms va tra ve cac thanh phan
	 * @param 	string 	$syntax		Cu phap sms
	 * @param 	string 	$component	Thanh phan muon lay (Neu khong khai bao thi lay tat ca)
	 * @return	array	array(mod, param, port)
	 */
	public function parse($syntax, $component = '')
	{
	    // Lay config
	    $sms_key 	= $this->config('key');
	    $sms_mods 	= $this->config('mods');
	    $syntax		= $this->handle_syntax($syntax);
	
	    // Thuc hien phan tich
	    foreach ($sms_mods as $mod => $sms_mod)
	    {
	        $match = '';
	        $syntax_main = $sms_key.' '.$sms_mod[0];
	        $syntax_main = preg_quote($syntax_main);
	        if (
	            preg_match("#^{$syntax_main}$#is", $syntax, $match) || 		// Truong hop cu phap chi co key va mod
	            preg_match("#^{$syntax_main} (.+)$#is", $syntax, $match) 	// Truong hop cu phap co them bien di kem
	        )
	        {
	            $sms = array();
	            $sms['mod'] 	= $mod;
	            $sms['param'] 	= trim(preg_replace("#^{$syntax_main}#is", '', $syntax));
	            $sms['port'] 	= $sms_mod[1];
	
	            return ($component) ? $sms[$component] : $sms;
	        }
	    }
	
	    return FALSE;
	}
	
	/**
	 * Lay phi cua sms
	 * @param 	string 	$port	Cong nhan tin
	 * @return	mixed	Phi tin nhan (VND) || FALSE (Neu port khong hop le)
	 */
	public function get_price($port)
	{
	    // Neu input dang danh sach
	    if (is_array($port))
	    {
	        $list = array();
	        foreach ($port as $v)
	        {
	            $list[$v] = $this->get_price($v);
	        }
	
	        return $list;
	    }
	
	    // Phi cua cac cong
	    $prices = array();
	    $prices['0'] = 500;
	    $prices['1'] = 1000;
	    $prices['2'] = 2000;
	    $prices['3'] = 3000;
	    $prices['4'] = 4000;
	    $prices['5'] = 5000;
	    $prices['6'] = 10000;
	    $prices['7'] = 15000;
	
	    // Lay phi cua cong hien tai
	    $n = substr($port, 1, 1);
	    $price = (isset($prices[$n])) ? $prices[$n] : FALSE;
	
	    return $price;
	}
	
	/**
	 * Xu ly syntax
	 *
	 * @param string $syntax
	 * @return string
	 */
	protected function handle_syntax($syntax)
	{
	    $syntax = strtolower(trim($syntax));
	
	    $syntax = preg_replace('#\s+#', ' ', $syntax);
	
	    $syntax = $this->getRoute($syntax);
	
	    return $syntax;
	}
	
	/**
	 * Lay route tuong ung voi uri
	 *
	 * @param string $uri
	 * @return string
	 */
	public function getRoute($uri)
	{
	    $routes = $this->getRoutes();
	
	    if (isset($routes[$uri]))
	    {
	        return $routes[$uri];
	    }
	
	    foreach ($routes as $pattern => $replacement)
	    {
	        if ($this->matchRouteRegex($uri, $pattern, $replacement))
	        {
	            return $replacement;
	        }
	    }
	
	    return $uri;
	}
	
	/**
	 * Lay routes
	 *
	 * @return array
	 */
	protected function getRoutes()
	{
	    return $this->config('routes', []);
	}
	
	/**
	 * Kiem tra su phu hop cua route regex
	 *
	 * @param string $subject
	 * @param string $pattern
	 * @param string $replacement
	 * @return bool
	 */
	protected function matchRouteRegex($subject, $pattern, &$replacement = null)
	{
	    if (preg_match('#^'.$pattern.'$#is', $subject))
	    {
	        if (str_contains($replacement, '$') && str_contains($pattern, '(') && str_contains($pattern, ')'))
	        {
	            $replacement = preg_replace('#^'.$pattern.'$#is', $replacement, $subject);
	        }
	
	        return true;
	    }
	
	    return false;
	}
	
}