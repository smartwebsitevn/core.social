<?php namespace TF\Support;

/**
 * Autoload Class
 * 
 * Class xu ly goi tu dong cac class.
 * Su dung quy tac PSR-4 autoloading, mo rong them tuy chon xu ly theo ca chu thuong
 *
 * @version		2014-08-22
 */
class Autoload
{
	/**
	 * The registered namespace prefixs
	 * 
	 * @var array
	 */
	protected $prefixes = array();
	
	/**
	 * The registered namespace prefixs use lowercase
	 * 
	 * @var array
	 */
	protected $prefixes_lower = array();

	/**
	 * Indicates if a Autoload has been registered.
	 *
	 * @var bool
	 */
	protected $registered = FALSE;
	
	/**
	 * Autoload instance
	 * 
	 * @var \TF\Support\Autoload
	 */
	protected static $instance;
	
	
	/**
	 * Create a new Autoload instance
	 */
	public function __construct()
	{
		// Auto register autoload
		$this->register();
	}
	
	/**
	 * Use as a singleton
	 */
	public static function get_instance()
	{
		if ( ! isset(static::$instance))
		{
			static::$instance = new static();
		}
		
		return static::$instance;
	}
	
	/**
	 * Register autoload
	 */
	public function register()
	{
		if ( ! $this->registered)
		{
			$this->registered = spl_autoload_register(array($this, 'load_class'));
		}
	}
	
	/**
	 * Unregister autoload
	 */
	public function unregister()
	{
		if ($this->registered && spl_autoload_unregister(array($this, 'load_class')))
		{
			$this->registered = FALSE;
		}
	}
	
	/**
	 * Add namespace prefix
	 * 
	 * @param string $prefix
	 * @param string $base_dir
	 * @param bool	 $prepend
	 * @param bool	 $lower
	 */
	public function add_namespace($prefix, $base_dir, $prepend = FALSE, $lower = FALSE)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $base_dir = rtrim($base_dir, '/') . DIRECTORY_SEPARATOR;
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';
		
        $this->prefixes = $this->_add_prefix($this->prefixes, $prefix, $base_dir, $prepend);
		
        if ($lower)
        {
			$this->prefixes_lower = $this->_add_prefix($this->prefixes_lower, strtolower($prefix), $base_dir, $prepend);
        }
    }
	
    /**
     * Handle add directories for namespace
     * 
     * @param array 	$prefixes
     * @param string 	$prefix
     * @param string 	$base_dir
     * @param bool 		$prepend
     * @return array
     */
    protected function _add_prefix(array $prefixes, $prefix, $base_dir, $prepend)
    {
        if ( ! isset($prefixes[$prefix]))
        {
        	 $prefixes[$prefix] = array();
        }
		
        $exists = in_array($base_dir, $prefixes[$prefix]);
        if ($prepend)
        {
            array_unshift($prefixes[$prefix], $base_dir);
        }
        else
        {
            array_push($prefixes[$prefix], $base_dir);
        }
        
        if ($exists)
        {
        	$prefixes[$prefix] = array_unique($prefixes[$prefix]);
        }
        
        return $prefixes;
    }
    
    /**
     * Handle the autoload call
     * 
     * @param string $class
     * @return boolean
     */
	public function load_class($class)
    {
    	if ( ! count($this->prefixes) && ! count($this->prefixes_lower))
    	{
    		return FALSE;
    	}
    	
    	$prefix = $class;
        while (FALSE !== $pos = strrpos($prefix, '\\'))
        {
            $prefix = substr($class, 0, $pos + 1);
            $relative_class = substr($class, $pos + 1);
			
            foreach (array(
            	array($this->prefixes, $prefix, $relative_class), 
            	array($this->prefixes_lower, strtolower($prefix), strtolower($relative_class)),
            ) as $args)
            {
            	$mapped_file = call_user_func_array(array($this, 'mapped_file'), $args);
            	if ($mapped_file)
            	{
            		return TRUE;
            	}
            }
			
            $prefix = rtrim($prefix, '\\');   
        }
		
        return FALSE;
    }
	
    /**
     * Find and Require file
     * 
     * @param array 	$prefixes
     * @param string 	$prefix
     * @param string 	$relative_class
     * @return boolean
     */
	protected function mapped_file(array $prefixes, $prefix, $relative_class)
    {
		if ( ! isset($prefixes[$prefix]))
		{
			return FALSE;
		}
		
        foreach ($prefixes[$prefix] as $base_dir)
        {
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
			
            if ($this->require_file($file))
            {
                return TRUE;
            }
        }
		
        return FALSE;
    }
	
    /**
     * Require a file
     * 
     * @return boolean
     */
	protected function require_file($file)
    {
        if (file_exists($file))
        {
        	require $file;
            return TRUE;
        }
        
        return FALSE;
    }
	
}