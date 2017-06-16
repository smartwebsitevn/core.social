<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Providers Class
 * 
 * Class model xu ly module theo nha cung cap
 * 
 * @author		***
 * @version		2015-01-09
 */
class Core_providers_model extends CI_Model
{
	/**
	 * Ten model
	 * 
	 * @var string
	 */
	protected $model;
	
	
	/**
	 * Cai dat
	 */
	public function install($key)
	{
		model('ext')->set($this->model, $key);
	}
	
	/**
	 * Gan la cong mac dinh
	 */
	public function set_default($key)
	{
	    $input = array('type' => $this->model);
	    $list  = model('ext')->get_list($input);
	    foreach($list as $row)
	    {
	        $data = ($row->code == $key) ? array('default' => '1') : array('default' => '0');
	        model('ext')->update($row->id, $data);
	    }
	}


	/**
	 * Lay cong mac dinh
	 */
	public function get_default()
	{
	    $input = array('type' => $this->model, 'default' => '1');
	    $info  = model('ext')->get_info_rule($input);
	    $provider = '';
	    if(!$info)
	    {
	        $input = array();
	        $input['where'] = array('type' => $this->model);
	        $list  = model('ext')->get_list($input);
	        if(isset($list[0]))
	        {
	            $provider = $list[0]->code;
	        }
	    }
	    else
	    {
	        $provider = $info->code;
	    }

	    return $provider;
	}

	/**
	 * Go bo
	 */
	public function uninstall($key)
	{
		model('ext')->del($this->model, $key);
		
		$this->del_setting($key);
	}
	
	/**
	 * Kiem tra ton tai
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function exists($key)
	{
		if ( ! $key) return false;

		$filename = ucfirst("{$key}_{$this->model}");

		$url = APPPATH."libraries/{$this->model}/{$filename}".EXT;
		
		return file_exists($url);
	}
	
	/**
	 * Kiem tra da duoc cai dat hay chua
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function installed($key)
	{
		if ( ! $key) return FALSE;
		
		return (in_array($key, $this->get_list_installed()));
	}
	
	/**
	 * Lay ten
	 */
	public function get_name($key)
	{
		if ( ! $key) return FALSE;
		
		$key = strtolower($key);
		
		$this->load->language($this->model.'/'.$key);
		
		return lang($this->model.'_'.$key);
	}
	
	/**
	 * Lay thong tin
	 */
	public function get_info($key, $field = '')
	{
		if ( ! $key) return FALSE;
		
		$key = strtolower($key);
		
		$field = ( ! $field || $field == '*') ? 'id, name, setting' : $field;
		$field = str_replace(' ', '', $field);
		$field = explode(',', $field);
		
		$info = new stdClass();
		
		if (in_array('id', $field))
		{
			$info->id = $key;
		}
		
		if (in_array('name', $field))
		{
			$info->name = $this->get_name($key);
		}
		
		if (in_array('setting', $field))
		{
			$setting = $this->get_setting($key);
			foreach ($setting as $p => $v)
			{
				$info->{$p} = $v;
			}
		}
		
		return $info;
	}
	
	/**
	 * Lay danh sach cac file
	 */
	public function get_list_file()
	{
		$list = array();
		$files = glob(APPPATH."libraries/{$this->model}/*_{$this->model}".EXT);
		foreach ($files as $file)
		{
			$file_name 	= basename($file, EXT);
			
			$key = preg_replace('/_'.$this->model.'$/s', '', $file_name);
			$key = strtolower($key);
			
			$list[] = $key;
		}

		return $list;
	}
	
	/**
	 * Lay danh sach da duoc cai dat
	 */
	public function get_list_installed()
	{
		return model('ext')->get($this->model);
	}
	
	/**
	 * Lay thong tin list
	 */
	public function get_list_info(array $list, $field = '')
	{
		$_list = array();
		foreach ($list as $name)
		{
			$_list[] = $this->get_info($name, $field);
		}
		
		return $_list;
	}
	
	/**
	 * Lay setting
	 */
	public function get_setting($key, $param = '')
	{
		// Lay setting trong data
		$setting = model('setting')->get_group($this->model.'-'.$key);
		
		// Giai ma setting
		$setting = security_encrypt($setting, 'decode');
		
		// Neu chi lay gia tri cua 1 bien
		if ($param)
		{
			return $setting[$param];
		}
		
		return $setting;
	}
	
	/**
	 * Gan setting
	 */
	public function set_setting($key, $data)
	{
		// Ma hoa setting
		$data = security_encrypt($data, 'encode');
		
		// Xoa setting cu
		$this->del_setting($key);
		
		// Luu setting moi vao data
		model('setting')->set_group($this->model.'-'.$key, $data);
	}
	
	/**
	 * Xoa setting
	 */
	public function del_setting($key)
	{
		model('setting')->del_group($this->model.'-'.$key);
	}
	
}