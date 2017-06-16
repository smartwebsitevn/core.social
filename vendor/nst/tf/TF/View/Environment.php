<?php namespace TF\View;

use TF\Container\Container;
use TF\Events\Dispatcher;

Class Environment {

	/**
	 * The macro instance.
	 *
	 * @var \TF\View\Macro
	 */
	public $macro;
	
	/**
	 * The container instance.
	 *
	 * @var \TF\Container\Container
	 */
	protected $container;
	
	/**
	 * The event dispatcher instance.
	 *
	 * @var \TF\Events\Dispatcher
	 */
	protected $events;
	
	/**
	 * Path to view file
	 *
	 * @var string
	 */
	protected $view_path;
	
	/**
	 * List view extended
	 *
	 * @var array
	 */
	protected $extends = array();
	
	/**
	 * Data that should be available to all templates
	 *
	 * @var array
	 */
	protected $shared = array();
	
	/**
	 * Array of registered view name aliases.
	 *
	 * @var array
	 */
	protected $aliases = array();
	
	
	function a()
	{
		$this->extend('box');
		
		$this->section('title', 'Title a - {parent}');
		
		
		$this->section('body');
		
		pr('body top', false);
		
		$this->extend('list');
		$this->section('items');
		echo 'Items list - {parent}';
		$this->end_section();
		$this->section('page', 'page');
		$this->end_extend();
			
		pr('body bottom', false);
			
		$this->end_section();
			
		
		$this->end_extend();
		
		return ;
		
		
		$this->share(array('s1' => '1111', 's2' => '222'))
			->share('s3', '333');
		
		$view = $this->make('a', array('data' => 'Data'), array('section' => 'Section'));
		
		echo $view;
		return;
		
		//$this->extend('a');
		
		/* 
		$v = $this->set_section_cur('title');
		pr(var_dump($v), FALSE);
		
		$v = $this->get_section_cur();
		pr($v, FALSE);
		 */
		
		$this->extend('box');
		
			$this->section('title');
			
				echo 'Title 1';
				
				$this->extend('list');
					$this->section('title');
						echo 'Title 2';
					$this->end_section();
					$this->section('body', 'body 2');
				$this->end_extend();
				
			$this->end_section();
			
			$this->section('body', 'body 1');
			
		$this->end_extend();
		
		
		pr($this);
	}
	
	/**
	 * Create a new Environment instance
	 * 
	 * @param Container 	$container
	 * @param Dispatcher 	$events
	 * @param string 		$view_path
	 */
	public function __construct(Container $container, Dispatcher $events, $view_path)
	{
		$this->macro		= new Macro();
		$this->container 	= $container;
		$this->events 		= $events;
		$this->set_view_path($view_path);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Start extend a view
	 * 
	 * @param string $view
	 */
	public function extend($view)
	{
		$this->create_extend($view);
	}
	
	/**
	 * End and render current extent view
	 */
	public function end_extend()
	{
		$extend = $this->get_extend_cur();
		if ($extend === FALSE)
		{
			return;
		}
		
		$this->del_extend($extend['level']);
		
		echo $this->make($extend['view'], array(), $extend['sections'])->render();
	}
	
	/**
	 * Create a new extend
	 * 
	 * @param string $view
	 */
	protected function create_extend($view)
	{
		$this->extends[] = array(
			'view' 			=> $view,
			'sections' 		=> array(),
			'section_cur' 	=> '',
		);
	}
	
	/**
	 * Delete a extend
	 * 
	 * @param int $extend_level
	 */
	protected function del_extend($extend_level)
	{
		unset($this->extends[$extend_level]);
	}
	
	/**
	 * Get current extend level
	 * 
	 * @return false|int
	 */
	protected function get_extend_level()
	{
		$levels = array_keys($this->extends);
		
		return end($levels);
	}
	
	/**
	 * Get current extend level and send it to a callback
	 * 
	 * @param callable $callback
	 * @return false|mixed
	 */
	protected function callback_extend_level(callable $callback)
	{
		$extend_level = $this->get_extend_level();
		if ($extend_level === FALSE)
		{
			return FALSE;
		}
		
		return call_user_func_array($callback, array($extend_level));
	}
	
	/**
	 * Get current extend
	 * 
	 * @return false|array
	 */
	protected function get_extend_cur()
	{
		return $this->callback_extend_level(function ($extend_level)
		{
			$extend = $this->extends[$extend_level];
			$extend['level'] = $extend_level;
			
			return $extend;
		});
	}
	
	/**
	 * Set or start set value for a section
	 * 
	 * @param string $section
	 * @param string $content
	 */
	public function section($section, $content = '')
	{
		$this->set_section_content($section, $content);
		
		if ($content === '')
		{
			$this->set_section_cur($section);
			
			ob_start();
		}
	}
	
	/**
	 * End and set contents for current section
	 */
	public function end_section()
	{
		$this->set_section_content($this->get_section_cur(), ob_get_clean());
		
		$this->set_section_cur('');
	}
	
	/**
	 * Set section current
	 * 
	 * @param string $section
	 * @return bool
	 */
	protected function set_section_cur($section)
	{
		return $this->callback_extend_level(function ($extend_level) use ($section)
		{
			$this->extends[$extend_level]['section_cur'] = $section;
			return TRUE;
		});
	}
	
	/**
	 * Get section current
	 * 
	 * @return false|string
	 */
	protected function get_section_cur()
	{
		return $this->callback_extend_level(function ($extend_level)
		{
			return $this->extends[$extend_level]['section_cur'];
		});
	}
	
	/**
	 * Set contents for a section
	 * 
	 * @param string $section
	 * @param string $content
	 * @return bool
	 */
	protected function set_section_content($section, $content)
	{
		return $this->callback_extend_level(function ($extend_level) use ($section, $content)
		{
			$this->extends[$extend_level]['sections'][$section] = $content;
			return TRUE;
		});
	}

	// --------------------------------------------------------------------
	
	/**
	 * Get the evaluated view contents for the given view.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  array $sections
	 * @return \TF\View\View
	 */
	public function make($view, $data = array(), $sections = array())
	{
		$view = $this->get_alias($view);
		
		$path = $this->get_file_path($view);
		
		$view = new View($this, $view, $path, $data, $sections);
		
		$this->fire_event('create', $view);
		
		return $view;
	}
	
	/**
	 * Get path of view file
	 * 
	 * @param string $view
	 * @return string
	 */
	protected function get_file_path($view)
	{
		return $this->view_path . $view . '.php';
	}
	
	/**
	 * Set view path
	 * 
	 * @param string $view_path
	 */
	public function set_view_path($view_path)
	{
		$view_path = rtrim($view_path, '/') . DIRECTORY_SEPARATOR;
		$view_path = rtrim($view_path, DIRECTORY_SEPARATOR) . '/';
		
		$this->view_path = $view_path;
	}
	
	/**
	 * Get current view path
	 * 
	 * @return string
	 */
	public function get_view_path()
	{
		return $this->view_path;
	}
	
	/**
	 * Add a piece of shared data to the environment.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return \TF\View\Environment
	 */
	public function share($key, $value = NULL)
	{
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->share($k, $v);
			}
		}
		else 
		{
			$this->shared[$key] = $value;
		}
		
		return $this;
	}
	
	/**
	 * Get an item from the shared data.
	 *
	 * @param  string  $key			If $key = NULL then get all shared
	 * @param  mixed   $default
	 * @return mixed
	 */
	public function get_shared($key = NULL, $default = NULL)
	{
		if ($key === NULL)
		{
			return $this->shared;
		}
		else 
		{
			return (isset($this->shared[$key])) ? $this->shared[$key] : $default;
		}
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
	 * Register a view event.
	 * 
	 * @param string 			$event
	 * @param string|array 		$views
	 * @param callable|string 	$callback
	 * @param number 			$priority
	 */
	public function event($event, $views, $callback, $priority = 0)
	{
		$callback = $this->make_event_callback($event, $callback);
		
		foreach ((array) $views as $view)
		{
			$this->events->listen("view.{$event}:{$view}", $callback, $priority);
		}
	}
	
	/**
	 * Call the event for a given view.
	 * 
	 * @param string $event
	 * @param View $view
	 * @return array|null
	 */
	public function fire_event($event, View $view)
	{
		return $this->events->fire("view.{$event}:".$view->get_name(), array($view));
	}
	
	/**
	 * Make callback for view event.
	 * 
	 * @param string 			$event
	 * @param callable|string 	$callback
	 * @return callable|string
	 */
	protected function make_event_callback($event, $callback)
	{
		if (is_string($callback))
		{
			$callback .= '|'.$event;
		}
		
		return $callback;
	}
	
	/**
	 * Register a view creator.
	 *
	 * @param string|array 		$views
	 * @param callable|string 	$callback
	 * @param number 			$priority
	 */
	public function creator($views, $callback, $priority = 0)
	{
		return $this->event('create', $views, $callback, $priority);
	}

	/**
	 * Register a view composer.
	 *
	 * @param string|array 		$views
	 * @param callable|string 	$callback
	 * @param number 			$priority
	 */
	public function composer($views, $callback, $priority = 0)
	{
		return $this->event('render', $views, $callback, $priority);
	}
	
}