<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Esms_sms extends MY_sms
{
	/**
	 * Lay danh sach ip cua service
	 *
	 * @see MY_Sms::get_service_ip()
	 */
	public function get_service_ip()
	{
		return array('112.78.2.84');
	}
	
	/**
	 * Lay input khi nhan thong tin tu service
	 *
	 * @see MY_Sms::get_input_receive()
	 */
	public function get_input_receive($param = NULL)
	{
		$data = array();
		$data['sms_id'] 	= $this->input->get('smsid');
		$data['message'] 	= $this->input->get('content');
		$data['port'] 		= $this->input->get('serviceid');
		$data['phone'] 		= $this->input->get('sender');
		
		return (is_null($param)) ? $data : $data[$param];
	}
	
	/**
	 * Gui phan hoi sau khi nhan yeu cau tu service
	 *
	 * @see MY_Sms::make_feedback()
	 */
	public function make_feedback($content)
	{
		return '
			<ClientResponse>
				<Message>' . $content . '</Message>
				<Smsid>' . $this->get_input_receive('sms_id') . '</Smsid>
				<Receiver>' . $this->get_input_receive('phone') . '</Receiver>
			</ClientResponse>
		';
	}
	
}