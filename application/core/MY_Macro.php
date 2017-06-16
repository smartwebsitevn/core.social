<?php

require __DIR__.'/MY_Macro_View.php';

/**
 * Macro Class
 *
 * Class xu ly macro
 *
 * @author		***
 * @version		2015-03-13
 */
class MY_Macro
{
	/**
	 * Danh sach macro da dang ki
	 *
	 * @var array
	 */
	protected $macros = array();

	/**
	 * Namespace cua macro dang duoc goi
	 *
	 * @var array
	 */
	protected $namespace = array();

	/**
	 * Namespace mac dinh
	 *
	 * @var string
	 */
	protected $default_namespace = 'tpl::_macros/common';
	
	/**
	 * Danh sach cac file macro da duoc load
	 *
	 * @var array
	 */
	protected $loaded = array();


	/**
	 * Test
	 */
	public function _t()
	{
		/*$this->loadMacroFile('tpl::macros/common');
		$this->loadMacroFile('tpl::macros/common');

		$v = $this->isLoadedMacroFile('tpl::macros/common');
		pr(var_dump($v), 0);*/

		/*$v = $this->makeMacroName('fun');
		pr($v);*/

		//pr($this);
	}

	/**
	 * Xu ly goi macro nhu method cua class
	 *
	 * @param string $method
	 * @param array  $args
	 * @return mixed
	 */
	public function __call($method, array $args)
	{
		return $this->callMacro($method, $args);
	}

	/**
	 * Dang ki macro
	 *
	 * @param string   $name
	 * @param callable $macro
	 */
	public function registerMacro($name, callable $macro)
	{
		$name = $this->makeMacroName($name);

		$this->macros[$name] = $macro;
	}

	/**
	 * Kiem tra macro da duoc dang ki hay chua
	 *
	 * @param string $name
	 * @return bool
	 */
	public function hasMacro($name)
	{
		$name = $this->makeMacroName($name);

		return isset($this->macros[$name]);
	}

	/**
	 * Goi macro
	 *
	 * @param string $name
	 * @param array  $args
	 * @return mixed
	 * @throws \BadMethodCallException
	 */
	public function callMacro($name, array $args)
	{
		// Xu ly ten
		$name = $this->makeMacroNameCall($name);

		// Tu dong load file macro
		list($namespace, $function) = $this->parseMacroName($name);

		$this->loadMacroFile($namespace);

		// Goi macro neu da duoc dang ki
		if ($this->hasMacro($name))
		{
			return call_user_func_array($this->macros[$name], $args);
		}

		throw new \BadMethodCallException("Macro [{$name}] does not exist.");
	}

	/**
	 * Goi macro voi namespace
	 *
	 * @param string $namespace
	 * @return Macro
	 */
	public function callMacroNamespace($namespace = null)
	{
		$this->namespace[] = $namespace ?: $this->getDefaultNamespace();

		return $this;
	}

	/**
	 * Tao name cho macro duoc goi
	 *
	 * @param string $name
	 * @return string
	 */
	protected function makeMacroNameCall($name)
	{
		if (count($this->namespace))
		{
			$name = array_pop($this->namespace) . '@' . $name;
		}
		
		$name = $this->makeMacroName($name);

		return $name;
	}

	/**
	 * Xu ly macro name
	 *
	 * @param string $name
	 * @return string
	 */
	protected function makeMacroName($name)
	{
		list($namespace, $function) = $this->parseMacroName($name);

		$namespace = $namespace ?: $this->getDefaultNamespace();

		return $namespace.'@'.$function;
	}

	/**
	 * Phan tich ten macro
	 *
	 * @param string $name
	 * @return array array(namespace, function)
	 */
	protected function parseMacroName($name)
	{
		$name = ltrim($name, '@');

		if (str_contains($name, '@'))
		{
			$segments = explode('@', $name, 2);
		}
		else
		{
			$segments = array(null, $name);
		}

		return $segments;
	}

	/**
	 * Load file macro
	 *
	 * @param string $file
	 */
	protected function loadMacroFile($file)
	{
		if ($this->isLoadedMacroFile($file)) return;
		
		new MY_Macro_View($this, $file);
		
		$this->loaded[] = $file;
	}

	/**
	 * Kiem tra file macro da duoc load hay chua
	 *
	 * @param string $file
	 * @return bool
	 */
	protected function isLoadedMacroFile($file)
	{
		return in_array($file, $this->loaded);
	}

	/**
	 * Gan namespace mac dinh
	 *
	 * @param string $default_namespace
	 */
	public function setDefaultNamespace($default_namespace)
	{
		$this->default_namespace = $default_namespace;
	}

	/**
	 * Lay namespace mac dinh
	 *
	 * @return string
	 */
	public function getDefaultNamespace()
	{
		return $this->default_namespace;
	}

}