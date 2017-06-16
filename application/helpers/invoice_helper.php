<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * Lay thong tin chi tiet
	 */
	function invoice_get_info($invoice_id, $field = '')
	{
		return mod('invoice')->get_info($invoice_id, $field);
	}
	
	/**
	 * Them cac thong tin phu
	 */
	function invoice_add_info($invoice)
	{
		return mod('invoice')->add_info($invoice);
	}
	
	/**
	 * Kiem tra co the thuc hien hanh dong voi invoice hay khong
	 */
	function invoice_can_do($invoice, $action)
	{
		return mod('invoice')->can_do($invoice, $action);
	}

	
	/**
	 * Thuc hien tuy chinh voi invoice
	 */
	function invoice_action($invoice, $action,$note)
	{
		return mod('invoice')->action($invoice, $action,$note);
	}
	
	/**
	 * Lay thong tin khach hang cua invoice
	 */
	function invoice_get_client($invoice_id)
	{
		return mod('invoice')->get_client($invoice_id);
	}
	
	/**
	 * Goi ham xu ly cua module tuong ung
	 */
	function invoice_call_module($invoice, $act, $use_http = false)
	{
		return mod('invoice')->call_module($invoice, $act, $use_http);
	}
	