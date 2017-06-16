<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Tao cac lien ket trong site
	 */
	function site_create_url($mod, $row = '')
	{
		$CI =& get_instance();
		
		$name = (isset($row->name)) ? url_title(convert_vi_to_en($row->name)) : '';
		$name = (!$name && isset($row->title)) ? url_title(convert_vi_to_en($row->title)) : $name;
		
		switch ($mod)
		{
			// Mods
			case 'tran':
			case 'product':
			case 'user':
			case 'news':
			case 'cat':
			{
				$row = mod($mod)->url($row);
				
				break;
			}
			case 'product_cat':
			{
				$row = mod('cat')->url($row);
				
				break;
			}
			
			// Account - User
			case 'account':
			{
				$row = mod('user')->url($row);
				
				break;
			}
			
			case 'user_page':
			{
				$url = '';
				if (in_array($row, array('login', 'logout', 'register', 'forgot')))
				{
					$url = site_url($row);
				}
				else
				{
					$url = ($row == '') ? site_url('user') : site_url('user/'.$row);
				}
				
				return $url;
			}
			
			// Lang
			case 'lang':
			{
				$row->_url_change = site_url('home/lang/'.$row->id);
				break;
			}
			
			// Currency
			case 'currency':
			{
				$row->_url_change = site_url('home/currency/'.$row->id);
				break;
			}
			
			// News
			case 'news_page':
			{
				$rows = array();
				$rows['search'] = 'tim-kiem';
				$row = (isset($rows[$row])) ? $rows[$row] : $row;
				
				$url = ($row == '') ? site_url('tin-tuc') : site_url('tin-tuc/'.$row);
				return $url;
			}
			
			// Payment
			case 'payment':
			{
				$module = mod('tran')->type_name($row->type);
				
				return mod('tran')->module($module)->url_payment($row->id);
				
				break;
			}
			
			// Default
			default:
			{
				// Tao url cho cac page cua mod
				$match = '';
				if (preg_match('#^(.+)_page$#is', $mod, $match))
				{
					$mod = $match[1];
					$url = ($row == '') ? site_url($mod) : site_url($mod.'/'.$row);
					return $url;
				}
			}
		}
		
		return $row;
	}
	
	/**
	 * Tao cac link option cho 1 danh sach
	 */
	function site_url_create_option($list, $uri, $key, $options)
	{
		$is_array = TRUE;
		if (!is_array($list))
		{
			$is_array = FALSE;
			$list = array($list);
		}
		
		$uri = strtolower($uri);
		$uri = trim($uri, '/');
		
		foreach ($list as $row)
		{
			foreach ($options as $option)
			{
				$row->{'_url_'.$option} = site_url($uri.'/'.$option.'/'.$row->{$key});
			}
		}
		
		return ($is_array) ? $list : $list[0];
	}
	
	/**
	 * Kiem tra muc của module da duoc xem hay chua
	 */
	function site_mod_is_viewed($mod, $id, $update = TRUE)
	{
		$CI =& get_instance();
		$mod_viewed = $CI->session->userdata('mod_viewed');
		
		if (isset($mod_viewed[$mod][$id]))
		{
			return TRUE;
		}
		elseif ($update)
		{
			$mod_viewed[$mod][$id] = TRUE;
			$CI->session->set_userdata('mod_viewed', $mod_viewed);
		}
		
		return FALSE;
	}
	
	/**
	 * Lay thong tin cua site
	 */
	function site_get_info($key)
	{
		$CI =& get_instance();
		$CI->load->model('site_model');
		
		return $CI->site_model->get($key);
	}
	