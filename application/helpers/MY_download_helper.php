<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Gan head download
	 * @param string $filename
	 */
	function download_header($filename)
	{
		// Tai file thanh phan
		$CI =& get_instance();
		$CI->load->helper('file');
		
		// Lay mime
		$mime = get_mime_by_extension($filename);
		$mime = ( ! $mime) ? 'application/octet-stream' : $mime;
		
		// Gan header
		if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE)
		{
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Content-Transfer-Encoding: binary');
			header('Pragma: public');
		}
		else
		{
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Pragma: no-cache');
		}
	}
	