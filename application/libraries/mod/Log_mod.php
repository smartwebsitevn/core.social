<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_mod extends MY_Mod
{
	// tao log
	public function log($table,$table_id,$action,$data, $cleanup_action=false,$cleanup_time=null)
	{
		// kiem tra he thong co bat chuc nang luu log
		if(!config('log_activity')) return;
		$user_id=0;
		$admin_id=0;
		$detail = array_get($data,'detail',$action);
		if(get_area() == 'admin'){
			$admin=admin_get_account_info();
			if($admin) {
				$admin_id = $admin->id;
				$detail = t('html')->a(admin_url('admin') . '?id=' . $admin->id, $admin->name) . ': ' . $detail;
			}
		}

		else{
			$user =user_get_account_info();
			if($user){
			$admin_folder = config('admin_folder', 'main');
			$user_id  =$user->id ;
			$detail =	t('html')->a(site_url($admin_folder.'/user').'?id='.$user->id,$user->name) . ': '.$detail;
			}
			else{
				$detail =	lang('client'). ': '.$detail;
			}
		}

		$data['detail'] =$detail;
		$data['user'] =$user_id;
		$data['admin'] =$admin_id;




		$this->_model()->add($table,$table_id, $action,$data);
		if($cleanup_action) {
			if($cleanup_time == null)
				$cleanup_time=3 * 30 * 24 * 60 * 60; // Xoa log admin login cach day 3 thang
			$this->_model()->cleanup($table, '', $action, $cleanup_time);
		}
	}
	/**
	 * Tao filter tu input
	 * 
	 * @param array $fields
	 * @param array $input
	 * @return array
	 */
	public function create_filter(array $fields, &$input = array())
	{
		// Lay gia tri cua filter dau vao
		$input = array();
		foreach ($fields as $f)
		{
			$v = $this->input->get($f);
			$v = security_handle_input($v, in_array($f, array()));

			$input[$f] = $v;
		}
		
		if ( ! empty($input['id']))
		{
			foreach ($input as $f => $v)
			{
				$input[$f] = ($f != 'id') ? '' : $v;
			}
		}
		
		// Tao bien filter
		$filter = array();
		$query 	= url_build_query($input, TRUE);
		foreach ($query as $f => $v)
		{
			switch ($f)
			{
				case 'acc':
				{
					$f = 'table_id';
					break;
				}
				
				case 'created':
				{
					$created_to = $input['created_to'];
					$v = (strlen($created_to)) ? array($v, $created_to) : $v;
					$v = get_time_between($v);
					$v = ( ! $v) ? NULL : $v;
					break;
				}
			}
			
			if (is_null($v)) continue;
			
			$filter[$f] = $v;
		}
		
		return $filter;
	}
}