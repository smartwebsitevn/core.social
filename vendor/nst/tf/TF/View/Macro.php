<?php namespace TF\View;

use TF\View\MacroCall;

class Macro
{
	/**
	 * The MacroCall instance
	 *
	 * @var \TF\View\MacroCall
	 */
	protected $macro_call;
	
	/**
	 * The registered macros
	 *
	 * @var array
	 */
	protected $macros = array();
	
	/**
	 * Array of registered macro name aliases
	 *
	 * @var array
	 */
	protected $aliases = array();
	
	/**
	 * The current namespace
	 *
	 * @var string
	 */
	protected $namespace;
	
	
	/**
	 * Create a new Macro instance
	 */
	public function __construct()
	{
		$this->macro_call = new MacroCall($this);
	}
	
	/**
	 * Dynamically handle call macros
	 *
	 * @param  string  $method
	 * @param  array   $args
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		return $this->call($method, $args);
	}
	
	/**
	 * Register a macro
	 * 
	 * @param string 	$name
	 * @param callable 	$callback
	 */
	public function create($name, callable $callback)
	{
		unset($this->aliases[$name]);
		
		$this->macros[$name] = $callback;
	}
	
	/**
	 * Checks if macro is registered
	 *
	 * @param  string    $name
	 * @return boolean
	 */
	public function has($name)
	{
		return isset($this->macros[$name]);
	}
	
	/**
	 * Call a macro
	 * 
	 * @param string 	$name
	 * @param array 	$args
	 * @throws \InvalidArgumentException
	 * @return mixed
	 */
	public function call($name, array $args)
	{
		$name = $this->make_name($name);
		
		if ( ! $this->has($name))
		{
			throw new \InvalidArgumentException("Macro {$name} does not exist.");
		}
		
		return call_user_func_array($this->macros[$name], $args);
	}
	
	/**
	 * Call macro with namespace
	 *
	 * @param string $namespace
	 * @return \TF\View\MacroCall
	 */
	public function call_namespace($namespace)
	{
		return $this->macro_call->_namespace($namespace);
	}
	
	/**
	 * Make macro name
	 * 
	 * @param string $name
	 * @return string
	 */
	protected function make_name($name)
	{
		if ($this->namespace)
		{
			$name = $this->namespace . '::' . $name;
			
			$this->namespace = '';
		}
		
		$name = $this->get_alias($name);
		
		return $name;
	}
	
	/**
	 * Add an alias for a macro
	 * 
	 * @param string $name
	 * @param string $alias
	 */
	public function alias($name, $alias)
	{
		$this->aliases[$alias] = $name;
	}
	
	/**
	 * Get the alias for an macro if available.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function get_alias($name)
	{
		return isset($this->aliases[$name]) ? $this->aliases[$name] : $name;
	}
	
}