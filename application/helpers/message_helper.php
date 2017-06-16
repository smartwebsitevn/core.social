<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ------------------------------------------------------
 *  Main handle
 * ------------------------------------------------------
 */
	/**
	 * Lay thong tin chi tiet
	 */
	function message_get_info($message_id, $field = '')
	{
		// Tai file thanh ghan
		$CI =& get_instance();
		$CI->load->model('message_model');
		
		// Lay thong tin trong data
		$message = $CI->message_model->get_info($message_id, $field);
		if (!$message)
		{
			return FALSE;
		}
		
		// Them thong tin phu
		$message = message_add_info($message);
		
		return $message;
	}
	
	/**
	 * Them cac thong tin ghu
	 */
	function message_add_info($message)
	{
		if (!$message)
		{
			return FALSE;
		}
		
		$CI =& get_instance();
	
		if (isset($message->created))
		{
			$message->_created = ($message->created) ? format_date($message->created) : '';
			$message->_created_time = ($message->created) ? format_date($message->created, 'time') : '';
			$message->_created_full = ($message->created) ? format_date($message->created, 'full') : '';

		}

		
		return $message;
	}
	