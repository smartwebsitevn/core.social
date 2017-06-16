<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_mod extends MY_Mod
{
	/**
	 * Export list data
	 * 
	 * @param string $file_name
	 * @param array $list
	 */
	public function export($file_name, array $list)
	{
		t('load')->helper('download');
		
		download_header($file_name);

		if (count($list))
		{
			$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			
			$method = 'export_'.$ext;
			
			if (method_exists($this, $method))
			{
				call_user_func_array(array($this, $method), array($list));
			}
		}
	}
	
	/**
	 * Export XLS
	 * 
	 * @param array $list
	 */
	protected function export_xls(array $list)
	{
		$make_tr = function(array $list)
		{
			$result = '';
			foreach ($list as $val)
			{
				$result .= "<td>{$val}</td>";
			}
			
			return "<tr>{$result}</tr>";
		};
		
		$result = $make_tr(array_keys(head($list)));
		
		foreach ($list as $row)
		{
			$result .= $make_tr(array_values($row));
		}
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo "<table>{$result}</table>";
	}
	
	/**
	 * Export TXT
	 * 
	 * @param array $list
	 */
	protected function export_txt(array $list)
	{
		foreach ($list as $row)
		{
			foreach ($row as $k => $v)
			{
				echo "{$k}: {$v}"."\r\n";
			}
			
			echo "\r\n";
		}
	}
	
}