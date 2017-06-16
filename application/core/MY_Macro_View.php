<?php

class MY_Macro_View
{
	/**
	 * Doi tuong cua Macro
	 * 
	 * @var MY_Macro
	 */
	protected $factory;
	
	/**
	 * Ten view
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Danh sach cac bi danh da tao
	 *
	 * @var array
	 */
	protected $aliases = array();
	
	
	/**
	 * Khoi tao doi tuong
	 *
	 * @param MY_Macro 	$factory
	 * @param string 	$name
	 */
	public function __construct($factory, $name)
	{
		$this->factory = $factory;
		
		$this->setName($name);
		
		$this->loadFile();
	}

	/**
	 * Getter
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		// Xu ly goi macro trong file hien tai
		if ($key == 'macro')
		{
			return $this->macro($this->getName());
		}
	}
	
	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Tao bi danh
	 *
	 * @param string $name
	 * @param string $alias
	 */
	public function alias($name, $alias)
	{
		$this->aliases[$alias] = $name;
	}
	
	/**
	 * Lay bi danh
	 *
	 * @param string $name
	 * @return string
	 */
	protected function getAlias($name)
	{
		return isset($this->aliases[$name]) ? $this->aliases[$name] : $name;
	}

	/**
	 * Dang ki macro
	 *
	 * @param string   $name
	 * @param callable $macro
	 */
	public function register($name, callable $macro)
	{
		$name = $this->getName().'@'.$name;

		return $this->factory->registerMacro($name, $macro);
	}

	/**
	 * Goi macro voi namespace
	 *
	 * @param string $namespace
	 * @return Macro
	 */
	public function macro($namespace = null)
	{
		$namespace = $this->getAlias($namespace);
		
		return $this->factory->callMacroNamespace($namespace);
	}
	
	/**
	 * Load file macro hien tai
	 * 
	 * @throws InvalidArgumentException
	 * @return string
	 */
	protected function loadFile()
	{
		$view = $this->getName();
		
		$file = t('view')->find($view);
		
		if ( ! $file)
		{
			throw new InvalidArgumentException("Macro file [{$view}] does not exist.");
		}
		
		ob_start();
		
		include $file;
		
		return ob_get_clean();
	}

}