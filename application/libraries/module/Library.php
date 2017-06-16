<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Library Class
 * 
 * Thu vien xu ly cua module
 * 
 * @author		***
 * @version		2013-12-31
 */
class Module_library {
	
	// Doi tuong cua CI
	var $CI;
	
	/**
	 * Duong dan cua thu muc chua cac module
	 * 
	 * @var string
	 */
	protected $path;
	
	
	// --------------------------------------------------------------------
	
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		// Lay doi tuong cua CI
		$this->CI =& get_instance();
		
		// Load file config
		$this->CI->config->load('module', TRUE);
		
		// Gan path
		$this->set_path(APPPATH . 'modules');
	}

	// --------------------------------------------------------------------
	
	/**
	 * Gan path
	 *
	 * @param string $path
	 */
	public function set_path($path)
	{
		$path = rtrim($path, '/') . DIRECTORY_SEPARATOR;
		$path = rtrim($path, DIRECTORY_SEPARATOR) . '/';
	
		$this->path = $path;
	}
	
	/**
	 * Lay path hien tai
	 *
	 * @return string
	 */
	public function get_path()
	{
		return $this->path;
	}

	/**
	 * Tao path den modules
	 *
	 * @param string $path
	 * @return string
	 */
	public function path($path = '')
	{
		return $this->get_path() . $path;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Goi cac module duoc yeu cau
	 */
	public function __get($key)
	{
		$module = strtolower($key);
		$class 	= $this->create_class_name('module', $module);
		
		$obj = $this->CI->load->get_class($class);
		if ($obj === FALSE)
		{
			$this->CI->load->_class($class);
			$obj = $this->CI->load->get_class($class);
		}
		
		return $obj;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Goi controller method cua module
	 * @param string 	$area			Ten module
	 * @param string 	$module		Ten controller
	 * @param string 	$method			Ten method
	 */
	public function call_controller($area,$module)
	{

		// Kiem tra controller
		if ( ! in_array($area, array('site', 'admin')))
		{
			show_404('', FALSE);
		}

		// Kiem tra module
		if ( ! module_exists($module))
		{
			show_404('', FALSE);
		}

		// Kiem tra trang thai cua module
		/*if (
			($area == 'admin' && ! module_install($module)) ||
			($area != 'admin' && ! module_active($module))
		)
		{
			show_404('', FALSE);
		}
		*/
		//pr($area);
		$segs= t()->uri->rsegment_array();

		// Goi controller method cua module
		$controller = $segs[3];
		$method = isset($segs[4])?$segs[4]:'index';
		//echo $module.$method;
		
		if($area =='admin'){
			$params =array('area'=>'admin');
			// Kiem tra file controller
			$file = APPPATH.'modules/'.$module.'/controllers/'.$area.'/'.$controller.EXT;
		}else{
			$params =array();
			$file = APPPATH.'modules/'.$module.'/controllers/'.$controller.EXT;
		}

		if ( ! file_exists($file))
		{
			if ($controller == 'admin')
			{
				redirect( module_url($module, $controller, 'setting') );
			}
			else 
			{
				show_404('', FALSE);
			}
		}
		
		//echo '<br>';pr($file,false);
		
		
		// Load controller
		$class 	= $this->create_class_name('controller', $module, $controller,false,$params);
		
		$obj 	= $this->CI->load->get_class($class);
		if ($obj === FALSE)
		{ 
			$this->CI->load->_class($class, $this->{$module});
			$obj = $this->CI->load->get_class($class);
		}
		// Kiem tra method
		$method = ($method == '') ? 'index' : $method;
		if (
			substr($method, 0, 1) == '_' ||	// is private
			! method_exists($obj, $method)	// not exists
		)
		{
			show_404('', FALSE);
		}

		// Luu method hien tai
		$obj->area = $area;
		$obj->module = $module;
		$obj->controller = $controller;
		$obj->method = $method;
		// Chuyen den controller method
		return call_user_func_array(array($obj, $method), array());
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Goi controller method callback cua module
	 * 
	 * Dung de kiem tra gia tri cua bien khi su dung form_validation
	 * 
	 * @param string 	$module			Ten module
	 * @param string 	$controller		Ten controller
	 * @param string 	$method			Ten method
	 * @param mixed 	$value			Gia tri cua bien tu form
	 * @param string 	$args			Bien cua method duoc gan trong rule
	 * @param string 	$error			Loi tra ve neu co
	 */
	public function call_controller_callback($module, $controller, $method, $value, $args, &$error)
	{
		$class 	= $this->create_class_name('controller', $module, $controller);
		$obj 	= $this->CI->load->get_class($class);
		$method	= '_'.$method;
		
		if ( ! method_exists($obj, $method))
		{
			$error = 'Non-existent method: '.$method;
			return FALSE;
		}
		
		return $obj->{$method}($value, $args, $error);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Lay cac widget tai vi tri
	 * 
	 * @param string|array 	$regions		Cac vi tri can lay widget
	 * @param bool 			$get_contents	Co lay ca noi dung cua widget hay khong
	 * @return array
	 */
	public function get_widgets($regions, $get_contents = true ,$layout=null)
	{

		$url_cur = current_url(TRUE);

		// Su ly lang tren uri
		$langcur = t('uri')->langcur;
		if($langcur){
			$url_cur = preg_replace('#'.preg_quote($langcur).'/#i', '', $url_cur);
			$url_cur = preg_replace('#'.preg_quote($langcur).config('url_suffix', '').'#i', '', $url_cur);
		}
		$url_cur =trim($url_cur,'/');
		//pr($url_cur);
		$filter = array();
		$filter['region'] = $regions;
		/*if($layout)
			$filter['layout'] = $layout;*/

		$filter['status'] = TRUE;
		
		$input = array();
		$input['select'] = 'widget.*';
		$widget_list = model('widget')->filter_get_list($filter, $input);
		//pr_db();
		$result = array();
		foreach ($widget_list as $widget)
		{
			$widget = (object) model('widget')->handle_data_output((array) $widget);

			// Kiem tra status_auth
			if (
				($widget->status_auth == 'user' && ! user_is_login())
				|| ($widget->status_auth == 'guest' && user_is_login())
			)
			{
				continue;
			}
			/*if($widget->url_show){
				echo $url_cur;
				pr($widget->url_show);
			}*/
			// Neu url hien tai khong nam trong nhung url ma widget duoc phep hien thi
			if (
				! empty($widget->url_show)

			)
			{
				if(! url_in_list($url_cur, $widget->url_show))
					continue;
			}
			
			// Neu url hien tai nam trong nhung url ma widget khong duoc phep hien thi
			if (
				! empty($widget->url_hide) &&
				url_in_list($url_cur, $widget->url_hide)
			)
			{
				continue;
			}
			
			// Hien thi widget
			if (module_exists($widget->module))
			{
				if ($get_contents)
				{
					$widget->contents = $this->{$widget->module}->widget_display($widget);
				}
				
				$result[] = $widget;
			}
		}

		return $result;
	}
	
	/**
	 * Hien thi cac widget
	 * 
	 * @param array $regions	Cac vi tri
	 */
	public function display_widgets($regions,$layout=null)
	{

		// Neu khong ton tai region nao
		if ( ! count(t('tpl')->get_regions_name()))
		{
			return;
		}
		
		$widget_list = $this->get_widgets($regions, false ,$layout);

		foreach ($widget_list as $widget)
		{
			if (t('tpl')->has_region($widget->region))
			{
				t('tpl')->write($widget->region, $this->{$widget->module}->widget_display($widget));
			}
		}
	}


	/**
	 * Hien thi cac widget tai 1 vi tri
	 *
	 * @param array $region	  vi tri
	 */
	public function display_widgets_region($region,$return = false)
	{
		if(!$region) return;
		$widget_list = t('module')->get_widgets($region, true );
		foreach ($widget_list as $widget)
		{
			if (t('tpl')->has_region($widget->region))
			{
				if(!$return)
					echo  $widget->contents;

			}
		}
		if($return){
			return $widget_list;
		}
	}
	// --------------------------------------------------------------------
	
	/**
	 * Tao class name cho cac class thanh phan cua module
	 * @param string 	$type		Loai class (module || library || model || controller)
	 * @param string 	$module		Ten module
	 * @param string	$class		Ten class
	 * @param boolean	$handle		Xu ly class name hay khong
	 */
	public function create_class_name($type,$module, $class = '', $handle = FALSE,$param=array())
	{
		
		// Tao class name
		$url = 'modules/'.$module.'/';
		if ($type == 'module')
		{
			$url .= 'module';
		}
		elseif ($type == 'library')
		{
			$url .= 'libraries/'.$class;
		}
		elseif ($type == 'model')
		{
			$url .= 'models/'.$class;
		}
		elseif ($type == 'controller')
		{
			if(isset($param['area']))
				$url .= 'controllers/'.$param['area'].'/'.$class;
			else
			$url .= 'controllers/'.$class;
		}
		$name = str_replace('/', '\\', $url);
		
		// Xu ly class name
		if ($handle)
		{
			$name = strtolower($name);
			$name = explode('\\', $name);
			foreach ($name as $i => $v)
			{
				$name[$i] = ucfirst($v);
			}
			$name = implode('\\', $name);
		}
		//pr($name);
		return $name;
	}
	
	/**
	 * Lay danh sach module
	 */
	public function get_list()
	{
		// Tai file thanh phan
		$this->CI->load->helper('directory');
		
		// Lay cac thu muc module
		$dirs = directory_map(APPPATH.'modules/', TRUE);
		$dirs = ( ! is_array($dirs)) ? array() : $dirs;
		
		// Lay danh sach module
		$list = array();
		foreach ($dirs as $module)
		{
			$module =trim($module,DS);
			if (module_exists($module))
			{
				$list[] = $module;
			}
		}
		
		return $list;
	}
	
	/**
	 * Xu ly gia tri cua bien
	 * @param array $opt		Thuoc tinh cua bien
	 * @param mixed $value		Gia tri dau vao
	 */
	public function handle_param_value(array $opt, $value)
	{
		switch ($opt['type'])
		{
			case 'bool':
			{
				$value = ($value) ? TRUE : FALSE;
				
				break;
			}
			
			case 'select':
			case 'radio':
			{
				$value = ( ! isset($opt['values'][$value])) ? '' : $value;
				
				break;
			}
			
			case 'select_multi':
			case 'checkbox':
			{
				$value = ( ! is_array($value)) ? array($value) : $value;
				foreach ($value as $i => $v)
				{
					if ( ! isset($opt['values'][$v]))
					{
						unset($value[$i]);
					}
				}
				$value = array_values($value);
				
				break;
			}
			
			case 'date':
			{
				$value = ( ! is_numeric($value)) ? get_time_from_date($value) : $value;
				
				break;
			}
		}
		
		return $value;
	}
	
	/**
	 * Kiem tra loai bien
	 * @param array  $param		Thong tin bien
	 * @param string $type		Loai bien can kiem tra (file)
	 */
	public function param_is(array $param, $type)
	{
		switch ($type)
		{
			case 'file':
			{
				return (in_array($param['type'], array('file', 'image', 'file_multi', 'image_multi'))) ? TRUE : FALSE;
			}
		}
		
		return FALSE;
	}
	
}
