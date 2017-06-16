<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Matrix_library {
	
	var $CI = '';
	var $key = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
	
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	
	/**
	 * Tao the xac thuc
	 */
	function create()
	{
		// Tai file thanh phan
		$this->CI->load->helper('string');
		
		// Tao ma tran
		$matrix = array();
		for ($r = 1; $r <= count($this->key); $r++)
		{
			foreach ($this->key as $c)
			{
				$matrix[$r][$c] = random_string('numeric', 3);
			}
		}
		
		return $matrix;
	}
	
	/**
	 * Tao vi tri ngau nhien cua the xac thuc
	 * @param string 	$key	Ten the xac thuc
	 * @param int 		$n		So vi tri muon tao
	 */
	function position_create($key, $n = 0)
	{
		// Tai file thanh phan
		$this->CI->load->helper('array');
		
		// Xu ly input
		$n = ( ! $n) ? 2 : $n;
		
		// Tao vi tri ngau nhien
		$position = array();
		for ($i = 1; $i <= $n; $i++)
		{
			$r = random_element($this->key);
			$r = array_search($r, $this->key) + 1;
			$c = random_element($this->key);
			
			$position[] = array($r, $c);
		}
		
		// Luu vao session
		$matrix = $this->CI->session->userdata('matrix');
		$matrix[$key] = $position;
		$this->CI->session->set_userdata('matrix', $matrix);
		
		return $position;
	}
	
	/**
	 * Lay vi tri ngau nhien cua the xac thuc
	 * @param string 	$key			Ten the xac thuc
	 * @param bool 		$auto_create	Neu khong lay duoc gia tri thi tu dong tao moi
	 * @param int 		$n				So vi tri muon tao (trong truong hop $auto_create = TRUE)
	 */
	function position_get($key, $auto_create = FALSE, $n = 0)
	{
		// Lay vi tri tu session
		$matrix = $this->CI->session->userdata('matrix');
		$position = (isset($matrix[$key])) ? $matrix[$key] : FALSE;
		
		// Neu khong ton tai
		if ( ! $position && $auto_create)
		{
			$position = $this->position_create($key, $n);
		}
		
		return $position;
	}
	
	/**
	 * Xoa vi tri ngau nhien cua the xac thuc
	 * @param string $key	Ten the xac thuc
	 */
	function position_del($key)
	{
		$matrix = $this->CI->session->userdata('matrix');
		if (isset($matrix[$key]))
		{
			unset($matrix[$key]);
			$this->CI->session->set_userdata('matrix', $matrix);
		}
	}
	
}
