<?php

/**
 * Autoload Class
 *
 * Class xu ly goi tu dong cac class, su dung quy tac PSR-4 autoloading.
 *
 * @author		***
 * @version		1.0.0
 */
class Autoload
{
	/**
	 * Danh sach namespace va dir tuong ung
	 *
	 * @var array
	 */
	protected $prefixes = array();

	/**
	 * Trang thai dang ki autoload
	 *
	 * @var bool
	 */
	protected $registered = false;

	/**
	 * Doi tuong cua autoload
	 *
	 * @var \App\Library\Autoload\Factory
	 */
	protected static $instance;


	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->register();
	}

	/**
	 * Dang ki autoload
	 */
	public function register()
	{
		if ( ! $this->registered)
		{
			$this->registered = spl_autoload_register(array($this, 'loadClass'));
		}
	}

	/**
	 * Huy dang ki autoload
	 */
	public function unregister()
	{
		if ($this->registered && spl_autoload_unregister(array($this, 'loadClass')))
		{
			$this->registered = false;
		}
	}

	/**
	 * Lay doi tuong cua autoload
	 */
	public static function getInstance()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Them dir cho namespace
	 *
	 * @param string $prefix
	 * @param string $dir
	 * @param bool   $prepend
	 */
	public function addNamespace($prefix, $dir, $prepend = false)
	{
		$prefix = trim($prefix, '\\') . '\\';

		$dir = rtrim($dir, '/') . DIRECTORY_SEPARATOR;
		$dir = rtrim($dir, DIRECTORY_SEPARATOR) . '/';

		if ( ! isset($this->prefixes[$prefix]))
		{
			$this->prefixes[$prefix] = array();
		}

		$exists = in_array($dir, $this->prefixes[$prefix]);

		if ($prepend)
		{
			array_unshift($this->prefixes[$prefix], $dir);
		}
		else
		{
			array_push($this->prefixes[$prefix], $dir);
		}

		if ($exists)
		{
			$this->prefixes[$prefix] = array_unique($this->prefixes[$prefix]);
		}
	}

	/**
	 * Load class
	 *
	 * @param string $class
	 * @return bool
	 */
	public function loadClass($class)
	{
		if ( ! count($this->prefixes))
		{
			return false;
		}

		$prefix = $class;
		while (false !== $pos = strrpos($prefix, '\\'))
		{
			$prefix = substr($class, 0, $pos + 1);

			$relative_class = substr($class, $pos + 1);

			if ($this->mappedFile($prefix, $relative_class))
			{
				return true;
			}

			$prefix = rtrim($prefix, '\\');
		}

		return false;
	}

	/**
	 * Tim va require file cua class
	 *
	 * @param string $prefix
	 * @param string $relative_class
	 * @return bool
	 */
	protected function mappedFile($prefix, $relative_class)
	{
		if ( ! isset($this->prefixes[$prefix]))
		{
			return false;
		}

		foreach ($this->prefixes[$prefix] as $dir)
		{
			$file = $dir . str_replace('\\', '/', $relative_class) . '.php';

			if ($this->requireFile($file))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Require file neu ton tai
	 *
	 * @param string $file
	 * @return bool
	 */
	protected function requireFile($file)
	{
		if (file_exists($file))
		{
			require $file;

			return true;
		}

		return false;
	}

} 