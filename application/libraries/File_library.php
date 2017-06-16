<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File Library
 *
 * @author		***
 * @version		2013-11-01
 */
class File_library {
	
	// Doi tuong chinh cua CI
	var $CI;
	
	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		$this->CI =& get_instance();
	}
	
	
/*
 * ------------------------------------------------------
 *  File server handle
 * ------------------------------------------------------
 */
	/**
	 * Upload file len server luu tru
	 * @param string 	$file_name	Ten file
	 * @param int 		$status		Trang thai file (0 = public | 1 = private)
	 * @param bool 		$del_file	Xoa file tam hay khong
	 */
	function server_upload($file_name, $status, $del_file = FALSE)
	{
		// Lay config
		$config = config('upload', 'main');
		$folder = config('file', 'main');
		$folder = $folder[$status];
		
		// Tao duong dan
		$from 	= $config['path'].$config['folder'].'/'.$folder.'/'.$file_name;
		$to 	= $folder.'/'.$file_name;
		
		// Neu khong ton tai file $from
		if ( ! file_exists($from))
		{
			return;
		}
		pr($from,false);
		pr($to);
		// Chuyen file den server
		$this->CI->load->library('ftp');
		$this->CI->ftp->connect($this->_server_get_config());
		$this->CI->ftp->upload($from, $to);
		$this->CI->ftp->close();
		
		// Xoa file tam
		if ($del_file)
		{
			unlink($from);
		}
	}
	
	/**
	 * Xoa file tren server luu tru
	 * @param string 	$file_name	Ten file
	 * @param int 		$status		Trang thai file (0 = public | 1 = private)
	 */
	function server_del($file_name, $status)
	{
		// Lay config
		$folder 	= config('file', 'main');
		$folder 	= $folder[$status];
		
		// Tao duong dan
		$file = $folder.'/'.$file_name;
		
		// Xoa file tren server
		$this->CI->load->library('ftp');
		$this->CI->ftp->connect($this->_server_get_config());
		$this->CI->ftp->delete_file($file);
		$this->CI->ftp->close();
	}
	
	/**
	 * Lay config ket noi den ftp cua server
	 */
	private function _server_get_config()
	{
		$config_server = config('upload', 'main');
		$config_server = $config_server['server'];
		
		$config = array();
		$config['hostname'] = $config_server['hostname'];
		$config['username'] = $config_server['username'];
		$config['password'] = $config_server['password'];
		//$config['debug']	= TRUE;
		
		return $config;
	}
	
}