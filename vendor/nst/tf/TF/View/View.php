<?php namespace TF\View;

use TF\Support\Contracts\ArrayableInterface as Arrayable;
use TF\Support\Contracts\RenderableInterface as Renderable;

class View implements Renderable {
	
	/**
	 * The view environment instance.
	 *
	 * @var \TF\View\Environment
	 */
	protected $env;
	
	/**
	 * The name of the view.
	 *
	 * @var string
	 */
	protected $view;

	/**
	 * The path to the view file.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * The array of view data.
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * The array of view sections.
	 *
	 * @var array
	 */
	protected $sections;

	/**
	 * Array of registered view name aliases.
	 *
	 * @var array
	 */
	protected $aliases = array();
	
	
	/**
	 * Create a new view instance.
	 *
	 * @param  string  $view
	 * @param  string  $path
	 * @param  array   $data
	 * @return void
	 */
	public function __construct(Environment $env, $view, $path, $data = array(), $sections = array())
	{
		$this->env	= $env;
		$this->view = $view;
		$this->path = $path;
		$this->data = $data;
		$this->sections = $sections;
	}
	
	/**
	 * Get the string contents of the view.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the string contents of the view.
	 *
	 * @param  callable  $callback
	 * @return string
	 */
	public function render(callable $callback = NULL)
	{
		$this->env->fire_event('render', $this);
		
		$contents = $this->get_contents($this->path, $this->gather_data());
		
		$response = isset($callback) ? call_user_func_array($callback, array($this, $contents)) : NULL;
		
		return $response ?: $contents;
	}
	
	/**
	 * Get the evaluated contents of the view at the given path.
	 *
	 * @param  string  $__path
	 * @param  array   $__data
	 * @return string
	 */
	protected function get_contents($__path, array $__data = array())
	{
		if ( ! file_exists($__path))
		{
			throw new \Exception('Non-existent file: '.$__path);
		}
		else 
		{
			ob_start();
			
			extract($__data);
			
			include $__path;
			
			return ltrim(ob_get_clean());
		}
	}
	
	/**
	 * Get the data bound to the view instance.
	 *
	 * @return array
	 */
	protected function gather_data()
	{
		$data = array_merge($this->env->get_shared(), $this->data);
		
		foreach ($data as $key => $value)
		{
			if ($value instanceof Renderable)
			{
				$data[$key] = $value->render();
			}
		}
		
		return $data;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the view environment instance.
	 *
	 * @return \TF\View\Environment
	 */
	public function get_env()
	{
		return $this->ent;
	}
	
	/**
	 * Get the name of the view.
	 *
	 * @return string
	 */
	public function get_name()
	{
		return $this->view;
	}
	
	/**
	 * Get the path to the view file.
	 *
	 * @return string
	 */
	public function get_path()
	{
		return $this->path;
	}
	
	/**
	 * Set the path to the view.
	 *
	 * @param  string  $path
	 * @return void
	 */
	public function set_path($path)
	{
		$this->path = $path;
		
		return $this;
	}
	
	/**
	 * Add a piece of data to the view.
	 *
	 * @param  string|array  $key
	 * @param  mixed   $value
	 * @return \TF\View\View
	 */
	public function with($key, $value = '')
	{
		return $this->set_data($key, $value);
	}
	
	/**
	 * Add a view instance to the view data.
	 *
	 * @param  string  $key
	 * @param  string  $view
	 * @param  array   $data
	 * @param  array   $sections
	 * @return \TF\View\View
	 */
	public function nest($key, $view, $data = array(), $sections = array())
	{
		$view = $this->get_alias($view);
		
		return $this->set_data($key, $this->env->make($view, $data, $sections));
	}
	
	/**
	 * Import a view
	 * 
	 * @param  string  $view
	 * @param  array   $data
	 * @param  array   $sections
	 * @return \TF\View\View
	 */
	public function import($view, $data = array(), $sections = array())
	{
		$view = $this->get_alias($view);
		
		$data = ( ! count($data)) ? $this->data : $data;
		
		echo $this->env->make($view, $data, $sections)->render();
		
		return $this;
	}
	
	/**
	 * Add an alias for a view.
	 *
	 * @param  string  $view
	 * @param  string  $alias
	 * @return void
	 */
	public function alias($view, $alias)
	{
		$this->aliases[$alias] = $view;
	}
	
	/**
	 * Get the alias for an view if available.
	 *
	 * @param  string  $view
	 * @return string
	 */
	protected function get_alias($view)
	{
		return isset($this->aliases[$view]) ? $this->aliases[$view] : $view;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Register a macro
	 * 
	 * @param string 	$macro
	 * @param callable 	$callback
	 */
	public function create_macro($macro, callable $callback)
	{
		$name = $this->make_macro_name($macro);
		
		return $this->env->macro->create($name, $callback);
	}
	
	/**
	 * Call a macro
	 * 
	 * @param string $view
	 * @param string $macro
	 * @param array $args
	 * @return mixed
	 */
	public function macro($view = NULL, $macro = NULL, array $args = array())
	{
		$view = (is_null($view)) ? $this->view : $view;
		$view = $this->get_alias($view);
		
		// Autoload macro from other view
		$name = $this->make_macro_name($macro, $view);
		if ($view != $this->view && ! $this->env->macro->has($name))
		{
			$this->env->make($view)->render();
		}
		
		// Use magic method with namespace
		if (is_null($macro))
		{
			return $this->env->macro->call_namespace($view);
		}
		
		return $this->env->macro->call($name, $args);
	}
	
	/**
	 * Make name for macro
	 * 
	 * @param string $macro
	 * @param string $view
	 * @return string
	 */
	protected function make_macro_name($macro, $view = NULL)
	{
		$view = (is_null($view)) ? $this->view : $view;
		
		return $view . '::' . $macro;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Start extend a view
	 *
	 * @param string $view
	 */
	public function extend($view)
	{
		return $this->env->extend($view);
	}
	
	/**
	 * End and render current extent view
	 */
	public function end_extend()
	{
		return $this->env->end_extend();
	}
	
	/**
	 * Set or start set value for a section
	 *
	 * @param string $section
	 * @param string $content
	 */
	public function section($section, $content = '')
	{
		return $this->env->section($section, $content);
	}
	
	/**
	 * End and set contents for current section
	 */
	public function end_section()
	{
		return $this->env->end_section();
	}
	
	/**
	 * Check section exists
	 * @param string $section
	 * @return bool
	 */
	public function section_exists($section)
	{
		return $this->data_action($this->sections, 'exists', $section);
	}
	
	/**
	 * Get the string contents of a section.
	 *
	 * @param  string  $section
	 * @param  string  $default
	 * @return string
	 */
	public function get_section($section, $default = '')
	{
		if (isset($this->sections[$section]))
		{
			return strtr($this->sections[$section], array('{parent}' => $default));
		}
		
		return $default;
	}
	
	/**
	 * Set contents for a section.
	 * 
	 * @param  string|array  $section
	 * @param  string  $value
	 * @return \TF\View\View
	 */
	public function set_section($section, $value = '')
	{
		$this->sections = $this->data_action($this->sections, 'set', $section, $value);
		
		return $this;
	}
	
	/**
	 * Delete a section.
	 * @param unknown $section
	 * @return \TF\View\View
	 */
	public function del_section($section)
	{
		$this->sections = $this->data_action($this->sections, 'unset', $section);
		
		return $this;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Check key exists in the data
	 * @param string $key
	 * @return bool
	 */
	public function data_exists($key)
	{
		return $this->data_action($this->data, 'exists', $key);
	}
	
	/**
	 * Get key value in the data
	 *
	 * @param  string  $key
	 * @param  string  $default
	 * @return mixed
	 */
	public function get_data($key = NULL, $default = '')
	{
		return $this->data_action($this->data, 'get', $key, $default);
	}
	
	/**
	 * Set value for key in the data
	 * 
	 * @param  string|array  $key
	 * @param  string  $value
	 * @return \TF\View\View
	 */
	public function set_data($key, $value = '')
	{
		$this->data = $this->data_action($this->data, 'set', $key, $value);
		
		return $this;
	}
	
	/**
	 * Delete a key in the data
	 * @param string $key
	 * @return \TF\View\View
	 */
	public function del_data($key)
	{
		$this->data = $this->data_action($this->data, 'unset', $key);
		
		return $this;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Handle data
	 * 
	 * @param array 	$data
	 * @param string 	$action
	 * @param string 	$key
	 * @param mixed 	$value
	 * @return mixed
	 */
	protected function data_action($data, $action, $key, $value = '')
	{
		switch ($action)
		{
			case 'exists':
			{
				return array_key_exists($key, $data);
			}
			
			case 'get':
			{
				if ($key === NULL)
				{
					return $data;
				}
				
				return isset($data[$key]) ? $data[$key] : $value;
			}
			
			case 'set':
			{
				if (is_array($key))
				{
					foreach ($key as $k => $v)
					{
						$data[$k] = $v;
					}
				}
				else 
				{
					$data[$key] = $value;
				}
				
				return $data;
			}
			
			case 'unset':
			{
				unset($data[$key]);
				
				return $data;
			}
		}
	}
	
}