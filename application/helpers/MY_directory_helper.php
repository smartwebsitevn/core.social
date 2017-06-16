<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function directory_create($root, $path)
    {
       	$root 	 = rtrim($root, DS);
		$subDirs = explode(DS, $path);
		if ($subDirs == null) {
			return;
		}
		$currDir = $root;
		foreach ($subDirs as $dir) {
			$currDir = $currDir . DS . $dir;
           // echo 	$currDir;
			if (!file_exists($currDir)) {
				mkdir($currDir);
			}
		}
	}




/* End of file directory_helper.php */
/* Location: ./system/helpers/directory_helper.php */