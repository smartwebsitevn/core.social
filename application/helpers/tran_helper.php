<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Lay thong tin chi tiet
	 */
	function tran_get_info($tran_id, $field = '')
	{
		return mod('tran')->get_info($tran_id, $field);
	}
	
	/**
	 * Them cac thong tin phu
	 */
	function tran_add_info($tran)
	{
		return mod('tran')->add_info($tran);
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong voi tran hay khong
	 */
	function tran_can_do($tran, $action)
	{
		return mod('tran')->can_do($tran, $action);
	}
	
	/**
	 * Tao ma bao mat cho giao dich
	 */
	function tran_create_security()
	{
		return mod('tran')->create_security();
	}
	
	/**
	 * Thuc hien tuy chinh voi tran
	 */
	function tran_action($tran, $action,$note='')
	{
		return mod('tran')->action($tran, $action,$note);
	}
	
	/**
	 * Lay thong tin khach hang cua tran
	 */
	function tran_get_client($tran_id)
	{
		return mod('tran')->get_client($tran_id);
	}
	
	/**
	 * Goi ham xu ly cua module tuong ung
	 */
	/*function tran_call_module($tran, $act, $use_http = false)
	{
		return mod('tran')->call_module($tran, $act, $use_http);
	}*/
	