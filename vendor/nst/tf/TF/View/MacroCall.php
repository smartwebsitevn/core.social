<?php namespace TF\View;

class MacroCall
{
	/**
	 * The macro instance
	 *
	 * @var \TF\View\Macro
	 */
	protected $macro;
	
	/**
	 * The current namespace
	 *
	 * @var string
	 */
	protected $namespace;
	
	
	/**
	 * Create a new MacroCall instance
	 *
	 * @param \TF\View\Macro $macro
	 */
	public function __construct(Macro $macro)
	{
		$this->macro = $macro;
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
		$macro = $method;
		
		if ($this->namespace)
		{
			$macro = $this->namespace . '::' . $macro;
				
			$this->namespace = '';
		}

		return $this->macro->call($macro, $args);
	}

	/**
	 * Call macro with namespace
	 *
	 * @param string $namespace
	 * @return \TF\View\MacroCall
	 */
	public function _namespace($namespace)
	{
		$this->namespace = $namespace;
		
		return $this;
	}
	
}