<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Base Class
 * 
 * Class xay dung cua cac module
 * 
 * @author		***
 * @version		2014-01-02
 */
class MY_Module {
	
	// Key cua module
	var $key = '';
	
	// Doi tuong cua CI
	var $CI;
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		// Lay doi tuong cua CI
		$this->CI =& get_instance();
		
		// Goi module config
		$this->config = new Module_config($this);
		
		// Goi module lang
		$this->lang = new Module_lang($this);
		
		// Goi module load
		$this->load = new Module_load($this);
		
		// Goi module url
		$this->url = new Module_url($this);
		
		// Khoi tao loader
		$this->load->init();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Xu ly action voi cac class thanh phan
	 */
	public function __get($key)
	{
		// Goi cac class thanh phan
		if (in_array($key, array('library', 'model')))
		{
			return $this->load->_call($key);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham duoc goi truoc khi cai dat module
	 */
	public function install_pre(){}
	
	/**
	 * Ham duoc goi sau khi cai dat module
	 * @param object $module	Thong tin module
	 */
	public function install($module){}
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham duoc goi truoc khi go bo module
	 * @param object $module	Thong tin module
	 */
	public function uninstall_pre($module){}
	
	/**
	 * Ham duoc goi sau khi go bo module
	 * @param object $module	Thong tin module
	 */
	public function uninstall($module){}
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham duoc goi truoc khi setting module
	 * @param object $module	Thong tin module
	 */
	public function setting_pre($module){}
	
	/**
	 * Ham duoc goi khi setting module
	 * @param object $module	Thong tin module
	 */
	public function setting($module){}
	
	/**
	 * Ham duoc goi de kiem tra gia tri setting cua module
	 * @param object $module	Thong tin module
	 * @param array  $setting	Gia tri setting
	 * @param string $error		Loi tra ve neu co
	 */
	public function setting_check($module, $setting, &$error)
	{
		return TRUE;
	}
	
	/**
	 * Ham duoc goi truoc khi luu thong tin module
	 * @param object $module	Thong tin module
	 */
	public function setting_save_pre($module){}
	
	/**
	 * Ham duoc goi sau khi luu thong tin module
	 * @param object $module	Thong tin module
	 */
	public function setting_save($module){}
	
	/**
	 * Lay setting cua module
	 */
	public function setting_get_config()
	{
		return $this->_get_config('setting');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham duoc goi truoc khi setting widget
	 * @param object $widget	Thong tin widget
	 */
	public function widget_setting_pre($widget){}
	
	/**
	 * Ham duoc goi khi setting widget
	 * @param object $widget	Thong tin widget
	 */
	public function widget_setting($widget){}
	
	/**
	 * Ham duoc goi de kiem tra gia tri setting cua widget
	 * @param object $widget	Thong tin widget
	 * @param array  $setting	Gia tri setting
	 * @param string $error		Loi tra ve neu co
	 */
	public function widget_setting_check($widget, $setting, &$error)
	{
		return TRUE;
	}
	
	/**
	 * Ham duoc goi truoc khi luu thong tin widget
	 * @param object $widget	Thong tin widget
	 */
	public function widget_setting_save_pre($widget){}
	
	/**
	 * Ham duoc goi sau khi luu thong tin widget
	 * @param object $widget	Thong tin widget
	 */
	public function widget_setting_save($widget){}
	
	/**
	 * Lay config cua widget
	 */
	public function widget_get_config()
	{
		return $this->_get_config('widget');
	}
	
	/**
	 * Hien thi widget
	 * @param object $widget	Thong tin widget
	 */
	public function widget_display($widget)
	{
		$CI =& get_instance();
		
		// Lay config
		$widget_cache = $this->config->item('widget_cache');
		
		// Lay html trong cache
		$html = FALSE;
		if ($widget_cache)
		{
			$cache = 'widget_'.$widget->id;
			$cache .= '_'.md5($cache);
			
			$CI->load->driver('cache');
			$html = $CI->cache->file->get($cache);
		}
		
		// Neu khong su dung cache hoac cache khong ton tai
		if ( ! $widget_cache || $html === FALSE)
		{
			// Lay du lieu tu widget
			$data = array();
			$data['widget'] = $widget;
			$data = array_merge($data, $this->widget_run($widget));
			$html = $this->load->view('widget/'.$widget->widget, $data, TRUE);
			/*if($widget->region =='banner_player_top'){
				pr($html);
			}*/
			// Cap nhat html vao cache
			if ($widget_cache)
			{
				$cache_expire = $this->config->item('widget_cache_expire');
				$cache_expire = ( ! $cache_expire) ? config('widget_cache_expire', 'module') : $cache_expire;
				$CI->cache->file->save($cache, $html, $cache_expire);
			}
		}

		return $html;
	}
	
	/**
	 * Lay thong tin de hien thi widget
	 * @param object $widget	Thong tin widget
	 */
	public function widget_run($widget)
	{
		$data = array();

		$method = "widget_run_{$widget->widget}";
		
		if (method_exists($this, $method))
		{
			$data = $this->{$method}($widget);
		}
		
		return $data;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay config cua table
	 */
	public function table_get_config()
	{
		return $this->_get_config('table');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay config
	 * @param string $_file		File config
	 */
	protected function _get_config($_file)
	{
		// Include file
		$_path = APPPATH.'modules/'.$this->key.'/config/'.$_file.EXT;
		if (file_exists($_path))
		{
			include $_path;
		}
		
		// Ham xu ly thuoc tinh cua bien
		$_handle_param_option = function($option)
		{
			$option = set_default_value($option, array('file_server'), TRUE);
			$option = set_default_value($option, array('type', 'name', 'value', 'values', 'file_allowed', 'file_private', 'file_thumb'));
			if($option['values'])
			$option['values'] = ( ! is_array($option['values'])) ? array($option['values']) : $option['values'];
			
			return $option;
		};
		
		// Xu ly config
		$_config = (isset($$_file)) ? $$_file : array();
		switch ($_file)
		{
			case 'setting':
			{
				foreach ($_config as $p => $o)
				{
					$_config[$p] = $_handle_param_option($o);
				}
				break;
			}
			
			case 'widget':
			{
				foreach ($_config as $w => $w_o)
				{
					foreach ($w_o['setting'] as $p => $o)
					{
						$_config[$w]['setting'][$p] = $_handle_param_option($o);
					}
				}
				break;
			}
			
			case 'table':
			{
				foreach ($_config as $t => $t_o)
				{
					foreach ($t_o['cols'] as $p => $o)
					{
						$o = set_default_value($o, array('show'), TRUE);
						$_config[$t]['cols'][$p] = $_handle_param_option($o);
					}
				}
				break;
			}
		}
		
		return $_config;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Tao path den module hien tai
	 *
	 * @param string $path
	 * @return string
	 */
	public function path($path = '')
	{
		$path = $this->key . ($path ? '/'.$path : $path);
		
		return t('module')->path($path);
	}
	
}
