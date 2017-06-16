<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form_content_model extends MY_Model {

	public $table 	= 'form_content';
	public $order	= array('sort_order', 'asc');
	//public $translate_auto = TRUE;
	//public $translate_fields = array('name');

	public $content_fields = array('title','content');

	function __construct()
	{
		parent::__construct();
		// Goi class core_info_multi_model
		$this->load->model('core/core_content_lang_model', $this->table.'_content_model');
		$this->{$this->table.'_content_model'}->key = $this->table.'_'.$this->key;
		$this->{$this->table.'_content_model'}->table = $this->table.'_content';
		$this->{$this->table.'_content_model'}->fields_handle = $this->content_fields;
	}
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);

		foreach (array('id') as $p)
		{
			$f = (in_array($p, array())) ? $p.'_id' : $p;
			$f = $this->table.'.'.$f;
			$m = (in_array($p, array('created'))) ? 'range' : '';
			$this->_filter_set_where($filter, $p, $f, $where, $m);
		}

		if (isset($filter['ids']))
		{
			$this->db->where_in($this->table.'.'.'id',$filter['ids']);
			//$where[$this->table.'.'.'id'] = explode(',',$filter['ids']);
		}
		if (isset($filter['status']))
		{
			$v = ($filter['status']) ;//? 'on' : 'off';
			$where[$this->table.'.'.'status'] = config('status_'.$v, 'main');
		}
		//pr($where);
		foreach (array('title') as $p)
		{
			if (isset($filter[$p]))
			{
				$this->db->like($this->table.'.'.$p, $filter[$p]);
			}
		}
		return $where;
	}


	function get($id,$lang_id = NULL)
	{
		$list = $this->cache_get('_all');
		// Loc danh sach cat theo cat hien tai
		if ($lang_id == NULL)// neu truyen lang
		{
			$lang_id = lang_get_cur()->id;
		}
		$row=null;
		if(isset($list[$id])){
			$row =$list[$id];

			if(isset($row->_content[$lang_id])) {
				$content = (array)$row->_content[$lang_id];
				foreach ($content as $k => $v)
					$row->$k = $v;

			}
			//echo $id;r($row);
		}
		return $row;
	}
	/**
	 * Lay danh sach the loai da qua xu ly
	 */
	function get_type($type,$lang_id = NULL)
	{
		$list = $this->cache_get($type);

		// Loc danh sach cat theo cat hien tai
		if ($lang_id == NULL)// neu truyen lang
		{
			$lang_id = lang_get_cur()->id;
		}
		//pr($lang_id);
		//pr($list);
		// Them ten cua cac the content vao de tien lay thong tin
		foreach ($list as $row)
		{
			// lay lang mac dinh
			if(isset($row->_content[$lang_id])) {
				$content = (array)$row->_content[$lang_id];
				foreach ($content as $k => $v)
					$row->$k = $v;
			}
		}
		return $list;
	}




	function cache_get($type){
		t()->load->driver('cache');
		$cache = 'form_content/'.$type;
		$cache = t()->cache->file->get($cache);

		if(!$cache){

			$cache = $this->cache_update($type);
		}

		return $cache;
	}

	function cache_update($type=null)
	{
		if($type == '_all'){
			// luu lai tat ca danh sach duoi dang phang
			$list = $this->get_list();
			if($list){
				$_list= array();
				foreach($list as $it){
					$it->_content = model('form_content_content')->get($it->id);
					$_list[$it->id] =$it;
				}
				$list =$_list;
			}

		}
		else{
				$input = array();
				$input['where']['type'] = $type;
				$list = $this->get_list($input);
		}

		if($list){
			$_list= array();
			foreach($list as $it){
				$it->_content = model('form_content_content')->get($it->id);
				$_list[$it->id] =$it;
			}
			$list =$_list;
		}


		$this->cache_set($type,$list);
		return $list;
	}
	function cache_set($type,$data){
		t()->load->driver('cache');
		$cache = 'form_content/'.$type;
		//echo "<br>==$lang_cache:".$lang_cache;
		t()->cache->file->save($cache, $data, config('cache_expire_long', 'main'));

		return $data;
	}
	function cache_del($type, $tree_type = 'list', $lang_id = 0){
		$path = 'application/cache/form_content/'.$type;
		delete_files($path,true);
		//@rmdir($path);
	}
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */
	function _event_change($act, $params)
	{


		switch ($act)
		{
			case 'del':
			{
				$where 	= $params[0];
				if (isset($where[$this->key]))
				{
					$id = $where[$this->key];
					// Xoa noi dung lang
					model('cat_content')->del($id);

					// Cap nhat lai cache
					// chu y du cat co su dung song ngu, do do phan add, edit dc goi truoc cat_content model update du lieu
					// do do ta phai goi update cache o trong Controller khi goi ham add,edit

					//$where 	= $params[0];
					//$id = $where[$this->key];
					/*$info =$this->get_info($id);
					// Cap nhat lai cache
					$this->cache_update($info->type);
					$this->cache_update('_all');*/
				}
				break;
			}
		}


	}


}