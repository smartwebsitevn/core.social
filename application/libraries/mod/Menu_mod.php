<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_mod extends MY_Mod
{
	/**
	 * Lay items cua menu
	 * 
	 * @param string $menu
	 * @return array
	 */
	public function get($menu, &$parents = array())
	{
		$parents[] = $menu;
		
		$items = model('menu_item')->cache_get($menu, 'tree');
		$url_parent = url_get_parent(array_pluck($items, 'url'));
		foreach ($items as $item)
		{
		    $item->url = handle_content($item->url, 'output');
			$item->_is_active = ($item->url == current_url()) ? true : false;
			if(!empty($item->_sub))
			{
			    foreach ($item->_sub as $sub)
			    {
			        $sub->url = handle_content($sub->url, 'output');
			        $sub->_is_active = ($sub->url == $url_parent) ? true : false;
			    }
			}
		}

		return $items;
	}
	

	/**
	 * Lay items cua menu
	 *
	 * @param string $menu
	 * @return array
	 */
	public function get_items($menu, &$parents = array())
	{
	    $parents[] = $menu;
	
	    $items = model('menu_item')->get_items($menu);
	    $items = collect($items)->whereLoose('status', 1)->all();
	
	    $url_parent = url_get_parent(array_pluck($items, 'url'));
	
	    $has_actived = false;
	
	    foreach ($items as $i => $item)
	    {
	        $item->_is_active = ($item->url == $url_parent && ! $has_actived);
	
	        if ($item->_is_active)
	        {
	            $has_actived = true;
	        }
	
	        $item->sub = array();
	
	        if ($this->is_sub($item->url))
	        {
	            $sub = $this->get_sub($item->url);
	
	            if ( ! in_array($sub, $parents))
	            {
	                $item->sub = $this->get($sub, $parents);
	            }
	        }
	    }
	
	    return $items;
	}
	
	/**
	 * Kiem tra item url co phai la sub hay khong
	 * 
	 * @param string $url
	 * @return boolean
	 */
	public function is_sub($url)
	{
		return starts_with($url, 'menu.');
	}
	
	/**
	 * Lay menu sub tu item url
	 * 
	 * @param string $url
	 * @return string
	 */
	public function get_sub($url)
	{
		return preg_replace('#^menu\.#', '', $url);
	}
	
}