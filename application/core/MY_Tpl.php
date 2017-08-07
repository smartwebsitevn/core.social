<?php

/**
 * Template Core Class
 *
 * Class xu ly template
 *
 * @author		***
 * @version		2015-04-16
 */
class MY_Tpl
{
	/**
	 * Duong dan cua thu muc chua cac template
	 * 
	 * @var string
	 */
	protected $path;
	
	/**
	 * Template hien tai
	 * 
	 * @var string
	 */
	protected $tpl;
	
	/**
	 * Config cua template hien tai
	 * 
	 * @var array
	 */
	protected $config;
	
	/**
	 * Layout hien tai
	 * 
	 * @var string
	 */
	protected $layout;
	
	/**
	 * Regions cua layout hien tai
	 * 
	 * @var array
	 */
	protected $regions = array();
	
	// --------------------------------------------------------------------
	
	/**
	 * App boot
	 */
	public function boot()
	{
		$this->set_path(APPPATH . 'views');
		
		$this->set_tpl($this->get_tpl_app());
	}
	
	/**
	 * Lay tpl hien tai cua app
	 * 
	 * @return string
	 */


	protected function get_tpl_app()
	{
		$tpl = get_area();

		$tpl_custom = $tpl.'_custom';
		$tpl_mobile = $tpl.'_mobile';
		if (t('input')->is_mobile() && $this->has_tpl($tpl_mobile))
		{
			$tpl = $tpl_mobile;
		}
		/*elseif ($user_layout= t('input')->get_user_layout())
		{
			$tpl = $user_layout;
		}*/
		elseif ($this->has_tpl($tpl_custom))
		{
			$tpl = $tpl_custom;
		}
		
		return $tpl;
	}
	
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
	 * Gan template
	 *
	 * @param string $tpl
	 */

	public function set_tpl($tpl)
	{
		$this->tpl = $tpl;
		
		$this->config = $this->load_config($tpl);

		$this->registerTplViewNamespace($tpl);
	}
	
	/**
	 * Dang ki view namespace cho tpl
	 * 
	 * @param string $tpl
	 */
	protected function registerTplViewNamespace($tpl)
	{
		$tpls = array_unique(array(get_area(), $tpl));
		foreach ($tpls as $tpl)
		{
			t('view')->prependNamespace('tpl', $this->path($tpl));
			t('view')->prependNamespace('mr', $this->path($tpl.'/_macros'));
		}
	}
	
	/**
	 * Lay template hien tai
	 * 
	 * @return string
	 */
	public function get_tpl()
	{
		return $this->tpl;
	}
	
	/**
	 * Kiem tra su ton tai cua tpl
	 * 
	 * @param string $tpl
	 * @return boolean
	 */
	public function has_tpl($tpl)
	{
		return file_exists($this->path("{$tpl}/tpl.php"));
	}
	
	/**
	 * Lay config cua tempalte hien tai
	 * 
	 * @param string $key
	 * @param string $default
	 * @return mixed
	 */
	public function config($key = NULL, $default = NULL)
	{
		return array_get($this->config, $key, $default);
	}
	
	/**
	 * Lay config cua template
	 * 
	 * @param string $tpl
	 * @throws InvalidArgumentException
	 * @return array
	 */
	public function load_config($tpl)
	{
		$file = $this->path("{$tpl}/tpl.php");
		
		if ( ! file_exists($file))
		{
			throw new InvalidArgumentException("Non-existent file: {$file}");
		}
		
		$config = $this->require_file($file);
		$config = $this->make_config($config);

		if (empty($config['layouts']))
		{
			throw new InvalidArgumentException("The layouts param is required");
		}
		
		if ($config['layout'] == '')
		{
			throw new InvalidArgumentException("The layout param is required");
		}
		
		return $config;
	}
	
	/**
	 * Xu ly config
	 * 
	 * @param array $config
	 * @return array
	 */
	protected function make_config($config)
	{
		$config = set_default_value($config, 'regions', array());
		$config = set_default_value($config, 'layouts', array());
		$config = set_default_value($config, 'layout', '');
		$config = set_default_value($config, 'layout_mod', array());
		
		foreach ($config['regions'] as $k => $opt)
		{
			if ( ! is_array($opt))
			{
				$opt = array('name' => $opt);
			}
				
			$opt = set_default_value($opt, 'name', $k);
			$opt = set_default_value($opt, 'desc', '');
			
			$config['regions'][$k] = $opt;
		}

		foreach ($config['layouts'] as $k => $opt)
		{
			if ( ! is_array($opt))
			{
				$opt = array('name' => $opt);
			}
		
			$opt = set_default_value($opt, 'name', $k);
			$opt = set_default_value($opt, 'desc', '');
			$opt = set_default_value($opt, 'regions', array());
			$opt['regions'] = (array) $opt['regions'];
			
			$config['layouts'][$k] = $opt;
		}
		
		return $config;
	}
	
	/**
	 * Require file
	 * 
	 * @param string $file
	 * @return mixed
	 */
	protected function require_file($file)
	{
		return require $file;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Kiem tra layout co ton tai hay khong
	 *
	 * @param string $layout
	 * @return bool
	 */
	public function has_layout($layout)
	{
		return isset($this->config['layouts'][$layout]);
	}
	
	/**
	 * Gan layout
	 *
	 * @param string $layout
	 * @return bool
	 */
	public function set_layout($layout)
	{
		if ( ! $this->has_layout($layout))
		{
			return FALSE;
		}
		
		$this->layout = $layout;
		$this->regions = $this->make_regions_contents($this->regions, $this->config['layouts'][$layout]['regions']);
		
		return TRUE;
	}
	
	/**
	 * Tao bien luu content cua cac region
	 * 
	 * @param array $regions_contents
	 * @param array $regions
	 * @return array
	 */
	protected function make_regions_contents(array $regions_contents, array $regions)
	{
		// Loai bo cac region cu khong hop le
		foreach ($regions_contents as $region => $contents)
		{
			if ( ! in_array($region, $regions))
			{
				unset($regions_contents[$region]);
			}
		}
		
		// Khoi tao cac region moi
		$regions_contents = set_default_value($regions_contents, $regions, array());
		
		return $regions_contents;
	}
	
	/**
	 * Lay layout hien tai
	 *
	 * @return string
	 */
	public function get_layout()
	{
		return $this->layout;
	}
	
	/**
	 * Lay layout tuong ung voi method cua controller
	 * 
	 * @param string $controller
	 * @param string $method
	 * @return string
	 */
	public function get_layout_mod($controller, $method)
	{
		// Lay config
		$config = $this->config('layout_mod');
		
		// Layout gan rieng cho method
		if (isset($config[$controller][$method]))
		{
			$layout = $config[$controller][$method];
		}
		
		// Layout mac dinh cua controller
		elseif (isset($config[$controller]['*']))
		{
			$layout = $config[$controller]['*'];
		}
		
		// Layout gan cho toan bo controller
		elseif (isset($config[$controller]) && ! is_array($config[$controller]))
		{
			$layout = $config[$controller];
		}
		
		// Layout mac dinh cua template
		else 
		{
			$layout = $this->config('layout');
		}
		
		return $layout;
	}
	
	/**
	 * Kiem tra region co ton tai trong layout hien tai hay khong
	 * 
	 * @param string $region
	 * @return bool
	 */
	public function has_region($region)
	{
		return isset($this->regions[$region]);
	}
	
	/**
	 * Lay regions cua layout hien tai
	 * 
	 * @return array
	 */
	public function get_regions()
	{
		return $this->regions;
	}
	
	/**
	 * Lay danh sach ten cua cac regions
	 * 
	 * @return array
	 */
	public function get_regions_name()
	{
		return array_keys($this->get_regions());
	}
	
	/**
	 * Ghi content vao region
	 * 
	 * @param string 	$region
	 * @param string 	$content
	 * @param boolean 	$overwrite
	 * @return boolean
	 */
	public function write($region, $content, $overwrite = FALSE)
	{
		if ( ! $this->has_region($region))
		{
			return FALSE;
		}
		
		if ($overwrite)
		{
			$this->regions[$region] = array($content);
		}
		else 
		{
			$this->regions[$region][] = $content;
		}
		
		return TRUE;
	}
	
	/**
	 * Hien thi layout
	 * 
	 * @throws InvalidArgumentException
	 */
	public function render()
	{
		$layout = $this->get_layout();
		//pr($layout);
		if ( ! $layout)
		{
			throw new InvalidArgumentException("The layout does not exist");
		}
		
		$view = "tpl::_layouts/{$layout}";
		t('load')->view($view, $this->render_contents());
	}
	
	/**
	 * Tao contents render layout
	 * 
	 * @return array
	 */
	protected function render_contents()
	{
		$result = array();
		foreach ($this->regions as $region => $contents)
		{
			$result[$region] = implode('', $contents);
			
			// Reset contents
			$this->regions[$region] = array();
		}
		
		return $result;
	}

	/**
	 * Tao path den views
	 *
	 * @param string $path
	 * @return string
	 */
	public function path($path = '')
	{
		return $this->get_path() . $path;
	}

	/**
	 * Tao path den tpl hien tai
	 *
	 * @param string $path
	 * @return string
	 */
	public function tpl_path($path = '')
	{
		$path = $this->get_tpl() . ($path ? '/'.$path : $path);
		
		return $this->path($path);
	}
	
	/**
	 * Hien thi tpl
	 * 
	 * @param string $view		File view (NULL => Khong su dung view)
	 * @param string $layout	File layout ('' => Lay theo config || NULL => Khong su dung layout)
	 * @param array  $data		View data
	 */
	public function display($view, $layout = '', array $data = array())
	{
		// Neu request ajax thi chi hien thi view
		if (t('input')->is_ajax_request())
		{
			$layout = null;
		}

		// Kiem tra view & layout
		if (is_null($view) && is_null($layout))
		{
			show_error('Required View or Layout');
		}
		
		// Neu khong su dung layout
		if (is_null($layout))
		{
			view($view, $data);
			return;
		}
		
		// Lay layout theo config
		if ($layout == '')
		{
			$controller = t('uri')->rsegment(1);
			$method 	= t('uri')->rsegment(2);
			
			$layout = $this->get_layout_mod($controller, $method);
		}

		// Gan layout
		if ( ! $this->set_layout($layout))
		{
			show_error('Non-existent layout: '.$layout);
		}
		//pr($data);
		// Hien thi view
		if ($view && $this->has_region('content'))
		{
			$this->write('content', view($view, $data, true), true);
		}
		
		// Hien thi cac widget
		//pr(get_area());
		if(get_area() != 'admin')
			t('module')->display_widgets($this->get_regions_name(),$layout);
		
		// Hien thi template
		$this->render();
	}
	
}