<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_url_mod extends MY_Mod
{
	/**
	 * Them cac thong tin phu
	 *
	 * @param object $row
	 * @return object
	 */
	public function add_info($row)
	{
		$row = parent::add_info($row);
		
		foreach (array('url_original', 'url_seo', 'url_base') as $p)
		{
			if ( ! isset($row->$p)) continue;
			
			$row->{'_'.$p} = site_url($row->$p, array('seo' => false));
		}
		
		return $row;
	}
	
	/**
	 * Chuyen tu uri goc sang uri seo neu ton tai
	 * 
	 * @param string $uri
	 * @return string
	 */
	public function handle_uri($uri)
	{
		// Neu seo_url khong duoc kich hoat
		if ( ! config('seo_url', 'main'))
		{
			return $uri;
		}
		
		// Lay url_seo tuong ung
		$url_seo = $this->_model()->get_url_seo_from_original($uri);
		if ($url_seo)
		{
			return $url_seo;
		}

		// neu khong co url seo trong csdl thi su ly cung
		$uri= mod_handle_uri($uri);
		
		return $uri;
	}
	
	/**
	 * Lay route goc
	 * 
	 * @param string $uri
	 * @return string
	 */
	function get_route_base($uri)
	{
		// Lay tu router goc
		$route = t('router')->get_route_base($uri);
		
		// Neu khong ton tai thi lay tu router trong table url_seo (truong hop seo cua seo)
		if ($route == $uri)
		{
			$seo_routes = $this->_model()->get_list_route();
			$route = t('router')->get_route_base($uri, $seo_routes);
		}
		
		return $route;
	}
	
}