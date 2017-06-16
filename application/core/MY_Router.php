<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Router Core Class
 * 
 * @author		***
 * @version		2014-07-19
 */
class MY_Router extends CI_Router
{
	/**
	 * Phan tich router
	 */
	public function _parse_routes_()
	{
		// Xu ly routes seo
		$this->_set_routes_seo();
		
		// Xu ly routes mobile
		$this->_set_routes_mobile();
		
		return parent::_parse_routes();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly routes seo
	 */
	public function _set_routes_seo()
	{
		// Neu seo_url khong duoc kich hoat
		if ( ! $this->config->item('seo_url', 'main'))
		{
			return;
		}
		
		// Neu uri da duoc gan trong routes cua he thong
		$uri = implode('/', $this->uri->segments);
		if (isset($this->routes[$uri]))
		{
			return;
		}
		
		// Kiem tra uri co trong table seo_url hay khong
		require_once(BASEPATH.'database/DB.php');
		
		$db =& DB();
		$db->where('url_seo', $uri);
		$db->select('url_base');
		$seo_url = $db->get('seo_url')->row();
		$db->close();
		
		if ( ! isset($seo_url->url_base))
		{
			return;
		}
		
		// Gan routes
		$this->routes[$uri] = $seo_url->url_base;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly routes mobile
	 */
	public function _set_routes_mobile()
	{
		// Neu uri khong phai la trang cua mobile
		$mobile = $this->uri->segments[1];

		if ($mobile != 'mobile')
		{
			return;
		}
		
		// Gan bien luu trang hien tai la mobile
		$this->uri->mobile = TRUE;
		
		// Lay uri sau khi da loai bo mobile
		$uri = $this->uri->segments;
		unset($uri[0]);
		$uri = implode('/', $uri);
		
		// Neu khong ton tai uri
		if ($uri == '')
		{
			$this->routes[$mobile] = '';
		}
		// Neu uri la cua routes tinh
		elseif (isset($this->routes[$uri]))
		{
			$this->routes[$mobile.'/'.$uri] = $this->routes[$uri];
		}
		// Them city vao cac routes dong
		else
		{
			foreach ($this->routes as $k => $v)
			{
				$this->routes[$mobile.'/'.$k] = $v;
			}
			
			$this->routes[$mobile.'/(:any)'] = '$1';
		}

		//print_r($this->routes); ;exit;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay rounte goc tu uri
	 * @param string 	$uri		URI can phan tich
	 * @param array 	$routes		Danh sach routes (mac dinh lay theo routes trong config)
	 */
	public function get_route_base($uri, array $routes = array())
 	{
 		// Xu ly input
 		$routes = ( ! count($routes)) ? $this->routes : $routes;
 		
		// Is there a literal match?  If so we're done
		if (isset($routes[$uri]))
		{
			return $routes[$uri];
		}
		
		// Loop through the route array looking for wild-cards
		foreach ($routes as $key => $val)
		{
			// Convert wild-cards to RegEx
			$key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

			// Does the RegEx match?
			if (preg_match('#^'.$key.'$#', $uri))
			{
				// Do we have a back-reference?
				if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
				{
					$val = preg_replace('#^'.$key.'$#', $val, $uri);
				}

				return $val;
			}
		}
		
		// If we got this far it means we didn't encounter a
		// matching route so we'll set the site default route
		return $uri;
	}
	
}