<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'models/core/Core_hiarachy_model.php';
class Menu_item_model extends  Core_hiarachy_model{
	
	public $table 	= 'menu_item';
	public $field_name = 'title';
	public $field_parent_id = 'parent_id';
	public $field_parent_name = 'title';

	public $order 	= array('sort_order', 'asc');
	public $translate_auto = TRUE;
	public $translate_fields = array('title');

	/**
	 * Tao filter tu input
	 */
	function filter_create($fields, &$input = array())
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
			if ($v === NULL) continue;

			$filter[$f] = $v;
		}

		return $filter;
	}

	/**
	 * Lay danh sach items cua menu
	 */
	function get($menu = '')
	{
		$input = array();
		if ($menu)
		{
			$input['where']['menu'] = $menu;
		}
		$url_cur = current_url();
		//echo $url_cur;
		//print_r($widget);
		//getHirarchyString($link='',$item_active='',$parentislink=0,$optparent=null,$branchparent=0,$published=null,$maxlevel=9999)
		$link_setting=array( 'url_cur'=>$url_cur,	'parent_islink'=>true	);
		$menu=$this->get_tree($menu,$link_setting);

		return $menu;
	}

	/**
	 * Lay danh sach items cua menu
	 */
	function get_items($menu = '')
	{
	    $input = array();
	    if ($menu)
	    {
	        $input['where']['menu'] = $menu;
	    }
	
	    $list = $this->get_list($input);
	    foreach ($list as $row)
	    {
	        $row->url = handle_content($row->url, 'output');
	    }
	
	    return $list;
	}
	

	function get_tree($menu,$link_setting,$optparent=null,$branchparent=0,$active=1,$maxlevel=9999)
	{
		/*
        chu y khi lay du lieu
        Khi lay ta len nhom du lieu theo parent id de qua trinh su ly phia sau duoc nhanh chong
        */
		$input=array('where'=>array('menu'=>$menu,'id <>'=>$optparent,'status'=>$active),'sort_order'=>array('field'=>'sort_order','direct'=>'asc'));
		$data= $this->get_list($input);
		//$data=$this->_prepareData(null,$optparent,$published) ;
		$children=$this->_make_relation($data) ;
		// echo  "<br>---list Child----<br>";   print_r($children);
		$list = $this->_menu_recurse($branchparent, $children, '', $maxlevel,0,'',$link_setting);
		//echo  "<br>---list ----<br>";        print_r($list);
		return $list;
	}

	function _menu_recurse($id, &$children, $output, $maxlevel=9999, $level=0,$indent='',$link_setting=array())
	{

		$key=$this->key;
		//$parentactive=$item_active;//JRequest::getInt($key);
		$field_parent_id=$this->field_parent_id;
		$fieldtitle=$this->field_parent_name; $fieldkey = $this->key;
		//echo  "<br>level max=".$maxlevel;
		//echo  "<br>level curr=".$level;
		if (@$children[$id] && $level <= $maxlevel)
		{

			$count_li=1; $total_li=count($children[$id]);
			foreach ($children[$id] as $t)
			{
				$id = $t->$fieldkey;
				$url_cur=$link_setting['url_cur'];
				$parent_islink=$link_setting['parent_islink'];
				$link = handle_content($t->url, 'output_link');
				$target= ' target ="'.$t->target.'" ';
				//echo  "<br>Ifieldkey=".$fieldkey;
				//echo  "<br>children=";print_r($t);
				//$parent_id=$id;// id cha cua no
				$holder='';
				if(!empty($t->holder)){
					$st= explode(':',$t->holder);
					if(method_exists(mod($st[0]),'menu_holder_callback'))
						$holder = mod($st[0])->menu_holder_callback($st[1]);

				}


				$fn="";
				$class_li='';$current_li='';
				if($count_li % 2 ==0)
					$class_li .= " even ";// chan
				else {
					$class_li .=" odd ";// le
					if($count_li ==1)
						$class_li .=" first-child ";
				}
				if($count_li ==$total_li)
				{  $class_li .=" last-child ";    }

				if( $link==$url_cur)
				{
					$class_li .= ' active ';
					$current_li =' id ="current" ';
				}
				if(count(@$children[$id])>0)// neu khac null ->co con
				{
					if(!$parent_islink)
					{
						$link ="javascript:;";
					}
					$datanode='<a href="'.$link.'" '.$target.' class="level'.$level.'" ><span>'.$t->$fieldtitle.'</span></a>'.$holder;
					$output .= '<li class="'.$class_li.'  parent level'.$level.'"  '.$current_li.'>'.$datanode.'<ul>';
					$fn="</ul></li>"; // neu co con   ;
				}
				else
				{
					if(!$parent_islink)
					{
						//if($st->holder)
							//$link ="javascript:;";
					}
					$output .= '<li class="'.$class_li.' level'.$level.' " '.$current_li.'><a href="'. $link.'"  '.$target.' class="level'.$level.'" ><span>'.$t->$fieldtitle.'</span></a>'.$holder.'</li>'; // neu co con ';// i=1 la tu them vao cho do bi loi

				}
				// echo  "<br>children=";print_r($output);
				$output = $this->_menu_recurse($id, $children,$output, $maxlevel, $level+1, $indent,$link_setting);
				$output .= $fn;
				$count_li++;
			}
		}
		return $output;
	}

	function cache_update($menu = 0, $tree_menu = 'list')
	{
		// Lay danh sach trong data
		$data['where']['menu'] = $menu;
		$data['where']['status'] = 1;
		$data['order'] = array('sort_order','asc');
		$list = $this->get_list($data);
		// tao danh sach dang tree
		$cachetree = $this->create_tree($list, $menu, 0);

		$this->cache_set($menu, $cachetree, 'tree');
		// chuyen tree sang list
		$cachelist = array();
		$this->create_list($cachetree, $cachelist);

		// Luu vao cache
		$this->cache_set($menu, $cachelist, 'list');


		if($tree_menu == 'list')
			return $cachelist;
		return $cachetree;
	}
 
    function _event_change($act, $params)
	{
		$where 	= $params[0];
		if(!isset($where['menu']))
		{
		    $id   = $where[$this->key];
		    $info = $this->get_info($id);
		    if(!$info)
		    {
		        $id = isset($params[0]['id']) ? $params[0]['id'] : 0;
		        $info = $this->get_info($id);
		    }
		    $menu  = isset($info->menu) ? $info->menu : '';
		    // Cap nhat lai cache
		}else{
		    $menu = $where['menu'];
		}

		$this->cache_update($menu);
	}
	
	function cache_set($menu,$data, $tree_menu = 'list'){
		t()->load->driver('cache');
		$cache = $this->table.'/'.$menu.'_'.$tree_menu;
		//echo "<br>==$lang_cache:".$lang_cache;
		t()->cache->file->save($cache, $data, config('cache_expire_long', 'main'));

	}
	/*function cache_get_($menu = '0', $tree = 'list')
	{
		t()->load->driver('cache');
		// tam tat chuc nang cache di
		$cache = $this->table.'/'.$menu.'_'.$tree;
		$cache = t()->cache->file->get($cache);
		if(!$cache){
			$cache = $this->cache_update($menu);
		}

		return $cache;
	}*/

	function cache_get($menu = '0', $tree = 'list')
	{
		t()->load->driver('cache');
		$cache = $this->cache_update($menu,$tree);
		return $cache;
	}

	// tao danh sach cay thu muc
	private function create_tree($data = array(), $menu = '', $parent_id = 0, &$parent = array(), $_level = 1)
	{
		$list = array();
		foreach($data as $key => $row)
		{
			// bo qua
			if($row->menu != $menu || $parent_id != $row->parent_id) continue;
			// khoi tao du lieu them
			$row->_parent_id = array();
			$row->_sub_id = array();
			$row->_title = $row->title;
			$row->_level = $_level;
			$row->_url = $row->url;
			if($parent)
			{
				// tao danh sach id cha
				$row->_parent_id = $parent->_parent_id;
				$row->_parent_id[] = $parent_id;
				// ten danh muc moi
				$row->_title = $parent->_title. ' -> ' .$row->title;
				$row->_url = $parent->_url.'/'.$row->url;
			}
			// lay danh sach danh muc con
			$row->_sub = $this->create_tree($data, $menu, $row->id, $row, $_level + 1);
			// lay danh sach id con
			if($row->_sub)
			{
				$row->_sub_id = $this->get_sub_id($row->_sub);
			}
			// tao ten danh muc moi
			$list[$row->id] = $row;
		}
		return $list;
	}

	// tao full danh sach
	private function create_list($data = array(), &$list)
	{
		foreach ($data as $row)
		{
			// Lay danh sach cac the loai con cua the loai cat hien tai
			$sub = isset($row->_sub) ? $row->_sub : array();

			// Loai bo danh sach the loai con cua the loai hien tai
			/*if (isset($row->_sub))
			{
				unset($row->_sub);
			}*/

			// Them the loai hien tai vao danh sach tra ve
			$list[$row->id] = $row;

			// Tiep tuc them cac the loai con cua the loai hien tai vao danh sach tra ve
			$this->create_list($sub, $list);
		}
	}

	private function get_sub_id($data)
	{
		$list = array();
		foreach($data as $row)
		{
			$list[] = $row->id;
			if(isset($row->_sub) && $row->_sub)
			{
				$cate = $this->get_sub_id($row->_sub);
				$list = array_merge($cate,$list);
			}
		}
		return $list;
	}
}
