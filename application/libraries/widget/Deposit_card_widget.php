<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_card_widget extends MY_Widget
{
	/**
	 * Form deposit_card
	 */
	function form($temp = '')
	{
	    // Tai cac file thanh phan
	    $this->load->helper('form');
	
	    $this->data['types'] = mod('card_type')->get_list(array('status' => 1));
		$this->data['input'] = array_only((array) t('input')->get(), array('type', 'code', 'serial'));
		
	    // Hien thi view
	    $temp = (!$temp) ? 'tpl::_widget/deposit_card/form' : $temp;
	    $this->load->view($temp, $this->data);
	}

}