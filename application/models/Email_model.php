<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_model extends MY_Model
{
	public $table = 'email';
	public $key = 'key';
	public $select = 'key, title';
	public $order = array('key', 'asc');
	
	
	/**
	 * Them moi
	 */
	public function create(array $data, &$insert_id = null)
	{
		$id 	= $data[$this->key];
		$info 	= $this->get_info($id, $this->key);
		
		if ($info)
		{
			$this->update($id, $data);
		}
		else 
		{
			parent::create($data);
		}
	}
	
	/**
	 * Luu thong tin
	 */
	public function set($key, $title, $content, array $data = array())
	{
		$content = handle_content($content, 'input');
		
		$data = array_merge($data, compact('key', 'title', 'content'));
		
		$this->create($data);
	}
	
	/**
	 * Lay thong tin
	 */
	public function get($key)
	{
		$info = $this->get_info($key);
		
		if ($info)
		{
			$info->content = handle_content($info->content, 'output');
		}
		
		return $info;
	}
	
	/**
	 * Gui mail
	 */
	public function send($key, $to, $params = array(), $sendnow = false, $attach = array(), $debug = false)
	{
		return mod('email')->send($key, $to, $params, compact('sendnow','attach', 'debug'));
	}
	
	/**
	 * Lay config gui mail
	 */
	public function get_config()
	{
		return mod('email')->make_config();
	}
	
}