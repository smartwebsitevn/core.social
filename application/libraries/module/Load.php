<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Load Class
 * 
 * Class load, call cac class thanh phan cua module
 * 
 * @author		***
 * @version		2013-12-31
 */
class Module_load {
	
	// Doi tuong cua module hien tai
	var $MD = '';
	
	// Danh sach cac file helper da load
	protected $_helpers = array();
	
	// Loai class thanh phan muon goi hien tai
	protected $_call;
	
	// Danh sach cac bien luu tru
	protected $_cached_vars = array();
	
	// Nesting level of the output buffering mechanism
	protected $_ob_level;
	
	// Doi tuong cua CI
	var $CI;
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham khoi dong
	 */
	public function __construct($MD)
	{
		// Luu doi tuong cua module hien tai
		$this->MD = $MD;
		
		// Get ob level
		$this->_ob_level = ob_get_level();
		
		// Lay doi tuong cua CI
		$this->CI = get_instance();
		
		// Dang ki namespace
		t('view')->prependNamespace($this->MD->key, array(
			t('tpl')->tpl_path("_modules/{$this->MD->key}"),
			$this->MD->path('views'),
		));
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Getter
	 */
	public function __get($key)
	{
		// Xu ly goi cac class thanh phan
		if ($this->_call)
		{
			$_call = $this->_call;
			
			$this->_call = null;
			
			switch ($_call)
			{
				// Goi library, model
				case 'library':
				case 'model':
				{
					$class = $this->CI->module->create_class_name($_call, $this->MD->key, $key);
					
					return $this->CI->load->get_class($class);
				}
			}
		}
		
		// Cho phep su dung cac thuoc tinh cua module
		return $this->MD->{$key};
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Goi cac class thanh phan
	 * @param string $type	Loai class muon goi
	 */
	public function _call($type)
	{
		$this->_call = $type;
		
		return $this;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Khoi tao loader
	 */
	public function init()
	{
		// Load file config
		$this->config('config');
		
		// Autoload
		$this->_autoload();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Config loader
	 * @param 	string 		$file				File config
	 * @param 	boolean 	$use_sections		Gia tri duoc luu rieng theo file
	 * @param 	boolean 	$fail_gracefully	Khong hien thi thong bao loi neu co
	 * @return	boolean		Config duoc tai thanh cong hay khong
	 */
	public function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		return $this->MD->config->load($file, $use_sections, $fail_gracefully);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Helper loader
	 * @param string	$helper		Ten helper
	 */
	public function helper($helper)
	{
		// Neu load danh sach helper
		if (is_array($helper))
		{
			foreach ($helper as $babe)
			{
				$this->helper($babe);
			}
			return;
		}
		
		// Xu ly helper
		if ( ! $helper)
		{
			return;
		}
		
		// Neu helper da duoc load truoc do
		if (isset($this->_helpers[$helper]))
		{
			return;
		}
		
		// Neu khong ton tai file
		$file = APPPATH.'modules/'.$this->MD->key.'/helpers/'.$helper.EXT;
		if ( ! file_exists($file))
		{
			show_error('Unable to load the requested file: '.$file);
		}
		
		// Load file
		include_once($file);
		
		// Luu helper vao danh sach da load
		$this->_helpers[$helper] = TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lang loader
	 * @param string 	$file		Ten file lang
	 * @param string 	$idiom		Ten thu muc lang
	 */
	public function lang($file, $idiom = '')
	{
		return $this->MD->lang->load($file, $idiom);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Library loader
	 * @param string	$library	Ten library
	 * @param mixed		$params		Tham so truyen vao library
	 * @param string	$name		Ten doi tuong gan cho library
	 */
	public function library($library = '', $params = NULL, $name = NULL)
	{
		return $this->_load_class('library', $library, $params, $name);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Model loader
	 * @param string 	$model		Ten model
	 * @param string 	$name		Ten doi tuong gan cho model
	 */
	public function model($model, $name = NULL)
	{

		return $this->_load_class('model', $model, NULL, $name);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * View loader
	 * @param string 	$view		Ten file view
	 * @param array 	$vars		Bien truyen vao view
	 * @param boolean 	$return		Tra ve du lieu hoac load view
	 */
	public function view($view, array $vars = array(), $return = FALSE)
	{
		return view("{$this->MD->key}::{$view}", $vars, $return);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Load class
	 * @param string	$type		Loai class (library || model)
	 * @param string 	$class		Ten class
	 * @param mixed		$params		Tham so truyen vao class
	 * @param string	$name		Ten doi tuong gan cho class
	 */
	protected function _load_class($type, $class, $params = NULL, $name = NULL)
	{
		// Neu load danh sach class
		if (is_array($class))
		{
			foreach ($class as $babe)
			{
				$this->_load_class($type, $babe);
			}
			return;
		}
		
		// Xu ly class
		if ( ! $class)
		{
			return;
		}
		
		// Lay name
		if ( ! $name)
		{
			$name = $class;
		}
		
		// Load class
		$class 	= $this->CI->module->create_class_name($type, $this->MD->key, $class);
		$name 	= $this->CI->module->create_class_name($type, $this->MD->key, $name);
        // echo '<br>name:';	  pr($name);
        // echo '<br>1class:';	  pr($class,false);
		$this->CI->load->_class($class, $params, $name);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Load file
	 * @param string 	$_md_file		File can load
	 * @param array 	$_md_vars		Bien truyen vao file
	 * @param boolean 	$_md_return		Tra ve du lieu hoac load file
	 */
	protected function _load_file($_md_file, array $_md_vars, $_md_return)
	{
		// Neu khong ton tai file
		if ( ! file_exists($_md_file))
		{
			show_error('Unable to load the requested file: '.$_md_file);
		}
		
		// Trich xuat cac bien
		$this->_cached_vars = array_merge($this->_cached_vars, $_md_vars);
		
		extract($this->_cached_vars);
		
		
		// Xu ly du lieu
		ob_start();
		
		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_md_file))));
		}
		else
		{
			include($_md_file);
		}
		
		// Tra ve du lieu neu yeu cau
		if ($_md_return)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			
			return $buffer;
		}
		
		// Xu ly du lieu dau ra
		if (ob_get_level() > $this->_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$this->CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Load cac file thanh phan duoc khai bao trong autoload
	 */
	protected function _autoload()
	{
		// Include file
		$file = APPPATH.'modules/'.$this->MD->key.'/config/autoload'.EXT;
		if (file_exists($file))
		{
			include $file;
		}
		
		// Neu khong ton tai bien $autoload
		if ( ! isset($autoload))
		{
			return FALSE;
		}
		
		// Load cac file thanh phan
		foreach (array('config', 'helper', 'lang', 'library', 'model') as $p)
		{
			if ( ! isset($autoload[$p]))
			{
				continue;
			}
			
			foreach ($autoload[$p] as $f)
			{
				$this->{$p}($f);
			}
		}
		
		return TRUE;
	}
	
}
