<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Widget Library Class
 * 
 * @author		***
 * @version		2015-04-03
 */

// --------------------------------------------------------------------

/**
 * Class de goi cac widget
 */
class Widget_library
{
	/**
	 * Goi cac widget duoc yeu cau
	 */
	public function __get($key)
	{
		return t('lib')->driver('widget', $key);
	}
	
}

// --------------------------------------------------------------------

/**
 * Class xay dung cho cac widget
 */
class MY_Widget
{
	// Bien luu thong tin gui den view
	public $data = array();
	
	/**
	 * Cho phep su dung cac thuoc tinh cua controller
	 */
	public function __get($key)
	{
		return t($key);
	}
	
	/**
	 * Lay doi tuong cua mod hien tai
	 * 
	 * @return MY_Mod
	 */
	protected function _mod()
	{
		return mod($this->_get_mod());
	}
	
	/**
	 * Lay doi tuong cua model hien tai
	 * 
	 * @return MY_Model
	 */
	protected function _model()
	{
		return model($this->_get_mod());
	}
	
	/**
	 * Lay key cua mod hien tai
	 *
	 * @return string
	 */
	protected function _get_mod()
	{
		$key = strtolower(get_class($this));
	
		$key = preg_replace('#_widget$#', '', $key);
		
		return $key;
	}
	
	/**
	 * Tao path cho view hien thi
	 * 
	 * @param string $view
	 * @param string $default
	 * @return string
	 */
	protected function _make_view($view, $default)
	{
		return ($view) ? '/'.ltrim($view, '/') : $default;
	}
	
	/**
	 * Hien thi du lieu
	 * 
	 * @param string $view
	 */
	protected function _display($view ,$return = false)
	{
		$mod = $this->_get_mod();

		$view = starts_with($view, '/') ? ltrim($view, '/') : "tpl::_widget/{$mod}/$view";

		if($return)
			return view($view, $this->data,$return);
		else
			view($view, $this->data);
	}
	protected function _display_temp($temp ,$temp_options=[])
	{
		$this->data['temp_options'] = $temp_options;
		// Su ly hien thi temp hay tra ve du lieu
		$return = array_get($temp_options, 'return', false);
		if ($return)
			return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
		else
			$this->_display($this->_make_view($temp, __FUNCTION__));
	}

}
