<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_api_sms_receive extends MY_Controller
{
	/**
	 * Ham khoi dong
	 */
	public function __construct()
	{
		parent::__construct();

		$this->lang->load('admin/'.$this->_get_mod());
	}
	
	/**
	 * Remap method
	 */
	public function _remap($method, $params = array())
	{
		return $this->_remap_action($method, $params, array('view'));
	}
	
	/**
	 * List
	 */
	public function index()
	{
		$list = array();
		$list['mod'] = 'log_api';
		$list['filter'] = true;
		$list['filter_fields'] = array('value', 'ip', 'created', 'created_to');
		$list['filter_value'] = array('key' => 'sms_receive');
		$list['actions'] = array('view');
		$list['display'] = false;
		$this->_list($list);
		
		foreach ($this->data['list'] as $i => $row)
		{
			$r = $this->_make_log_epay($row);
			
			$r = array_merge($r, array_only((array) $row, ['created', 'ip']));
			
			$this->data['list'][$i] = $r;
		}
		
		$this->_display();
	}
	
	/**
	 * Tao log data tu epay
	 * 
	 * @param $row
	 * @return array
	 */
	protected function _make_log_epay($row)
	{
		$input = array_get($row->input, 'get', []);
		
		return [
			'message' 	=> array_get($input, 'content'),
			'port' 		=> array_get($input, 'shortcode'),
			'phone' 	=> array_get($input, 'userid'),
			'response' 	=> $row->output,
		];
	}
	
	/**
	 * View
	 */
	protected function _view($info)
	{
		$info = $this->_mod()->add_info($info);
		
		pr($info);
	}
	
}