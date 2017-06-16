<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zend_search_lucene_library {
	
	// Bien khoi dong cua CI
	var $CI;
	
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{ 
		$this->CI =& get_instance();
		
		// Tai thu vien Zend_Search_Lucene
		$this->CI->load->zend('Search/Lucene');
	}
	
	/**
	 * Ket noi den data
	 */
	function connect($dir = '')
	{
		// Tao path luu data index
		$data = config('data_index', 'main');
		$data = rtrim($data, '/');
		if ($dir)
		{
			$data .= '/'.$dir;
		}
		
		// Ket noi den data index
		$index = '';
		try
		{
			$index = Zend_Search_Lucene::open($data);
		}
		catch (Exception $e)
		{
			$index = Zend_Search_Lucene::create($data);
		}
		
		// Gan kieu du lieu Utf8 khong phan biet chu hoa va chu thuong
		Zend_Search_Lucene_Analysis_Analyzer::setDefault(
			new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive()
		);
		
		return $index;
	}
	
	/**
	 * Lay du lieu tu ket qua tim kiem
	 */
	function result($hits, array $limit = array())
	{
		// Tao ket qua tra ve
		$result = array();
		$result['total'] 	= count($hits);
		$result['list'] 	= array();
		
		foreach ($hits as $i => $hit)
		{
			// Gioi han limit
			$is_get = TRUE;
			if (isset($limit[0]) && isset($limit[1]))
			{
				if ($i < $limit[0])
				{
					$is_get = FALSE;
				}
				elseif (($limit[0]+$limit[1]) <= $i)
				{
					$is_get = FALSE;
					break;
				}
			}
			
			// Thuc hien get document
			if ($is_get)
			{
				$document = $hit->getDocument();
				
				$row = new stdClass();
				foreach ($document->getFieldNames() as $f)
				{
					$row->{$f} = $document->getFieldValue($f);
				}
				
				$result['list'][] = $row;
			}
		}
		
		return $result;
	}
	
}
?>