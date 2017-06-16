<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__.'/php_sql_parser/PHPSQLParser.php';

class Php_sql_parser_library {
	
	/**
	 * Phan tich SQL
	 * @param string $sql
	 */
	public function parse($sql)
	{
		try
		{
			$parser = new PHPSQLParser($sql, FALSE);
		}
		catch (Exception $e)
		{
			
		}
		
		return (isset($parser->parsed)) ? $parser->parsed : array();
	}
	
	/**
	 * Lay danh sach column
	 * @param array $input		Gia tri tra ve cua fun parse($sql)
	 * @param array $output		Danh sach column tra ve
	 */
	public function get_list_col($input, array &$output)
	{
		if ( ! is_array($input)) return;
		
		foreach ($input as $k => $v)
		{
			if ($k == 'expr_type' && $v == 'colref')
			{
				if (isset($input['base_expr']) && ! in_array($input['base_expr'], $output))
				{
					$output[] = $input['base_expr'];
				}
			}
			else
			{
				$this->get_list_col($v, $output);
			}
		}
	}
	
}