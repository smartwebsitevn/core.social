<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extend_model extends MY_Model {
		
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();
		
	}



	protected function parseSearch( $key, $filter )
	{
		if(! isset($filter[$key]) )
			return;

		$this->search( $this->table, $key, $filter[$key] );
	}
	
	
/*
 * ------------------------------------------------------
 *  Xu ly row
 * ------------------------------------------------------
 */


}