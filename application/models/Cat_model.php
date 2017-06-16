<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'models/core/Core_hiarachy_model.php';
class Cat_model extends  Core_hiarachy_model{

	public $table 	= 'cat';
	public $order	= array('sort_order', 'asc');

	public $field_name = 'name';
	public $field_parent_id = 'parent_id';
	public $field_parent_name = 'name';

	public $content_fields = [];//array('name','intro');


	public $translate_auto = TRUE;
	public $translate_fields = array('name');

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
	 * Content HANDEL
	 */

	function content_set($id,$data)
	{
		model('cat_content')->set($id,$data);
	}
	function content_get($id ,$lang_id = null)
	{
		return model('cat_content')->get($id,$lang_id);
	}

	function get_tree($type,$optparent=null,$branchparent=0,$active=1,$maxlevel=9999)
	{
		/*
        chu y khi lay du lieu
        Khi lay ta len nhom du lieu theo parent id de qua trinh su li phia sau duoc nhanh chong
        */
		$input=array('where'=>array('type'=>$type,'id <>'=>$optparent,/*'status'=>$active*/),'sort_order'=>array('field'=>$this->field_parent_id.',sort_order','direct'=>'asc'));

		$data= $this->get_list($input);
		$children=$this->_make_relation($data) ;
		// echo  "<br>---list Child----<br>";   pr($children);
		$list = $this->_data_recurse($branchparent, $children, array(), $maxlevel,0,'',$data);
		//echo  "<br>---list ----<br>";        pr($list);

		return $list;
	}

	function _data_recurse($id, &$children,$list=array(), $maxlevel=1, $level=0,$indent='',$data=array())
	{
		//  pr($children);
		$id_parent=$id;
		if (@$children[$id] && $level <= $maxlevel)
		{

			$field_parent_id =$this->field_parent_id ; $fieldtitle=$this->field_parent_name; $fieldkey = $this->key;
			foreach ($children[$id] as $t)
			{
				//echo  "<br>Ifieldkey=".$fieldkey;
				$id = $t->$fieldkey;
				$tmp = $t;
				//$tmp['children'] = count(@$children[$id]);

				//- Su ly parent
				/* $tmp->{'_parent'} =array();
                 if($data && $id_parent > 0) {
                     foreach($data as $p)
                         if($p->$fieldkey == $id_parent)
                             $tmp->{'_parent'} = $p;
                 }*/
				//- Su ly sub
				$tmp->{'_subs'} = @$children[$id]?(@$children[$id]):array();
				$tmp->{'_sub_ids'} = array();
				if($tmp->{'_subs'}){
					//$sub_ids=array();
					foreach($tmp->{'_subs'} as $it)
					{
						$tmp->{'_sub_ids'}[]= $it->$fieldkey;
					}
				}
				$pre 	= '|-- ';        $spacer = '&nbsp;&nbsp;&nbsp;&nbsp;';
				//$pre 	= '';   $spacer = '';
				// Su ly hien thi moi lien he cha con
				if ($t->$field_parent_id == 0) {
					$txt 	= $t->$fieldtitle;
				} else {
					/*foreach($children as $key => $vs){
                        foreach($vs as $v){
                            if($t->$field_parent_id == $v->$fieldkey){
                                $pre = $v->$fieldtitle.' >> ';
                                break;
                            }
                        }
                        if($pre)  break;

                    }*/
					$txt 	= $pre . $t->$fieldtitle;
				}
				$tmp->{'_'.$this->field_name} = "$indent$txt";// add more field service for display tree
				$list[$id] = $tmp ;
				$list = $this->_data_recurse($id, $children,$list, $maxlevel, $level+1, $indent . $spacer,$data);

			}
		}
		return $list;
	}
	/**
	 * Filter handle
	 */
	function _filter_get_where(array $filter)
	{
		$where = parent::_filter_get_where($filter);
		foreach (array('id','type') as $p)
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
		foreach (array('name') as $p)
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
		static $list = null;
		if($list === null)
			$list = $this->cache_get('_all');
		$row=null;
		if(isset($list[$id])){
			$row =$list[$id];
		}
		return $row;
	}
	/**
	 * Lay danh sach the loai da qua xu ly
	 */
	function get_type($type,$lang_id = NULL)
	{
		$list = $this->cache_get($type);

		return $list;
	}


	function cache_get($type){
		$cache = $this->cache_update($type);
		return $cache;
	}
// tam tat chuc nang load tu cache do dung da ngon ngu thi no khong luu trong cache
	function cache_get_($type){
		t()->load->driver('cache');
		$cache = 'cat/'.$type;
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
				//$it->_content = model('cat_content')->get($it->id);
				$_list[$it->id] =$it;
			}
			$list =$_list;

		}

	}
		else{
			if (in_array($type, mod('cat')->config("cat_hiarachy_types"))){

				$tree = $this->get_tree($type);//list($input);
				$list = array();
				foreach ($tree as $it) {
					if ($it->parent_id > 0) continue;
					$list[$it->id] = $it;
					/*if($it->_subs){
						foreach($it->_subs as $s){
							$s->_content = model('cat_content')->get($s->id);

						}
					}*/
				}
			}
			else{
				$input = array();
				$input['where']['type'] = $type;
				$list = $this->get_list($input);
			}


		}
		//pr($list,0);
		if($list){
			$_list= array();
			foreach($list as $it){
				//$it->_content = model('cat_content')->get($it->id);
				$_list[$it->id] =$it;
			}
			$list =$_list;
		}
		//pr($list);

		$this->cache_set($type,$list);
		return $list;
	}
	function cache_set($type,$data, $tree_type = 'list', $lang_id = 0){
		t()->load->driver('cache');
		$cache = 'cat/'.$type;
		//echo "<br>==$lang_cache:".$lang_cache;
		t()->cache->file->save($cache, $data, config('cache_expire_long', 'main'));

		return $data;
	}
	function cache_del($type, $tree_type = 'list', $lang_id = 0){
		$path = 'application/cache/cat/'.$type;
		delete_files($path,true);
		//@rmdir($path);
	}
	/**
	 * Ham duoc goi khi du lieu thay doi
	 */

	/*function _event_change($act, $params)
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

				}
				break;
			}
		}


	}*/

}