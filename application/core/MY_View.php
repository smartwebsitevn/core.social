<?php

/**
 * View Core Class
 *
 * Class xu ly template
 *
 * @author		***
 * @version		2015-04-16
 */
class MY_View
{
	/**
	 * Danh sach cac path cua views
	 *
	 * @var array
	 */
	protected $paths;
	
	/**
	 * Path cua cac view da duoc phan tich
	 *
	 * @var array
	 */
	protected $views = array();
	
	/**
	 * Path cua cac namespace
	 *
	 * @var array
	 */
	protected $hints = array();
	
	/**
	 * Cache data cua cac view
	 * 
	 * @var array
	 */
	protected $cached_data = array();
	
	/**
	 * Nesting level of the output buffering mechanism
	 * 
	 * @var int
	 */
	protected $ob_level;
	
	/**
	 * Hint path delimiter value.
	 *
	 * @var string
	 */
	const HINT_PATH_DELIMITER = '::';
	
	
	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		// Gan paths
		$this->setPaths(APPPATH . 'views');
		
		// Lay ob level
		$this->ob_level = ob_get_level();
	}

	/**
	 * Cho phep su dung cac thuoc tinh cua controller
	 */
	public function __get($key)
	{
		return t($key);
	}
	
	/**
	 * Gan paths
	 * 
	 * @param array|string $paths
	 */
	public function setPaths($paths)
	{
		$this->paths = (array) $paths;
	}
	
	/**
	 * Lay paths
	 * 
	 * @return array
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * Them path
	 * 
	 * @param string|array $path
	 */
	public function addPath($path)
	{
		$this->paths = array_merge($this->paths, (array) $path);
	}

	/**
	 * Lay path cua view
	 *
	 * @param  string  $name
	 * @return string
	 */
	public function find($name)
	{
		//echo '<br>find:';pr($name,false);
		if (isset($this->views[$name])) return $this->views[$name];
		if ($this->hasHintInformation($name = trim($name)))
		{
			return $this->views[$name] = $this->findNamedPathView($name);
		}

		return $this->views[$name] = $this->findInPaths($name, $this->paths);
	}

	/**
	 * Tim file view voi namespace
	 * 
	 * @param string $name
	 * @return string|false
	 */
	protected function findNamedPathView($name)
	{
		list($namespace, $view) = $this->parseNamespace($name);
		$paths = array_get($this->hints, (string)$namespace, array());
		
		return $this->findInPaths($view, $paths);
	}
	
	/**
	 * Phan tich namespace
	 * 
	 * @param string $name
	 * @return array
	 */
	protected function parseNamespace($name)
	{
		$segments = explode(static::HINT_PATH_DELIMITER, $name, 2);
		
		if (count($segments) == 1)
		{
			$segments = array(null, $segments[0]);
		}
		
		return $segments;
	}

	/**
	 * Tim file trong paths
	 * 
	 * @param string $name
	 * @param array $paths
	 * @return string|false
	 */
	protected function findInPaths($name, $paths)
	{
		$paths = array_unique((array) $paths);
		
		foreach ($paths as $path)
		{
			$file = "{$path}/{$name}.php";
			
			if (file_exists($file))
			{
				return $file;
			}
		}

		return false;
	}

	/**
	 * Them hints cho namespace
	 *
	 * @param  string  $namespace
	 * @param  string|array  $hints
	 */
	public function addNamespace($namespace, $hints)
	{
		$hints = (array) $hints;

		if (isset($this->hints[$namespace]))
		{
			$hints = array_merge($this->hints[$namespace], $hints);
		}

		$this->hints[$namespace] = $hints;
	}

	/**
	 * Them hints vao truoc cac hints cua namespace
	 *
	 * @param  string  $namespace
	 * @param  string|array  $hints
	 */
	public function prependNamespace($namespace, $hints)
	{
		$hints = (array) $hints;

		if (isset($this->hints[$namespace]))
		{
			$hints = array_merge($hints, $this->hints[$namespace]);
		}

		$this->hints[$namespace] = $hints;
	}

	/**
	 * Kiem tra name co ton tai ki tu phan cach thong tin hay khong
	 *
	 * @param  string  $name
	 * @return boolean
	 */
	public function hasHintInformation($name)
	{
		return strpos($name, static::HINT_PATH_DELIMITER) > 0;
	}
	
	/**
	 * Load view
	 * 
	 * @param string $view
	 * @param array  $data
	 * @param bool	 $return
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function load($view, array $data = array(), $return = false)
	{
		//pr($view,false);
		$file = $this->find($view);
		
		if ( ! $file)
		{
			throw new InvalidArgumentException("View [{$view}] does not exist.");
		}
	
		$this->cached_data = array_merge($this->cached_data, $data);
		
		return $this->loadFile($file, $this->cached_data, $return);
	}
	
	/**
	 * Thuc hien load file
	 * 
	 * @param string $__file
	 * @param array  $__data
	 * @param bool	 $__return
	 * @return string
	 */
	protected function loadFile($__file, array $__data, $__return)
	{
		ob_start();
		
		extract($__data);
		
		include $__file;
		
		// Tra ve contents
		if ($__return)
		{
			return ob_get_clean();
		}
		
		// Xu ly contents dau ra
		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			t('output')->append_output(ob_get_clean());
		}
	}
	
}