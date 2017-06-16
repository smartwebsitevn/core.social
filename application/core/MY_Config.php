<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config
{
	/**
	 * Lay config item cua file
	 * 
	 * @param string	$key
	 * @param string	$file
	 * @param mixed		$default
	 * @return mixed
	 */
	public function item($key, $file = NULL, $default = FALSE)
	{
		// Tu dong load file neu file chua duoc load
		if ($file && ! isset($this->config[$file]))
		{
			$this->load($file, TRUE, TRUE);
		}
		// Lay item trong file
		if ($file)
		{
			if (isset($this->config[$file][$key]))
			{
				return $this->config[$file][$key];
			}
			
			log_message('error', "Not found config key: {$key} in file: {$file}");
			
			return $default;
		}
		
		if (isset($this->config[$key]))
		{
			return $this->config[$key];
		}
		
		log_message('error', "Not found config key: {$key}");
		
		return $default;
	}
	
}

