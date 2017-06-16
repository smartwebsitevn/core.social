<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'models/core/Core_hiarachy_model.php';
class Blog_cat_model extends Core_hiarachy_model
{
    public $table = 'blog_cat';
   // public $order = array(array('created', 'desc'), array('id', 'desc'));
    public $order = array(array('sort_order', 'asc'), array('id', 'desc'));

    public $translate_auto = TRUE;
    public $translate_fields = array(
        'name', 'brief', 'description',
        'seo_title',   'seo_description'
    );
    public $field_name = 'name';
    public $field_parent_id = 'parent_id';
    public $field_parent_name = 'name';
    public $field_sort_order = 'sort_order';


    public $fields = array(
        //== Info core
        'status',  'sort_order',   'created',  'updated',
        //== Info main
        'name', 'brief', 'description',// 'tags','video',
        //'parent_id',
        //== Info thuoc tinh bool
        //'comment_fb_allow', 'comment_allow',
        'is_feature', //'is_new',   'is_in_menu',
        //== Info lien ket bang khac
        //'cat_id',
        // == Info seo
        'seo_title',   'seo_url',    'seo_description',  'seo_keywords',//  'seo_index',
        //== Info kkac
        //'icon_fa',
    );
    public $fields_filter = array(
        'name','id', 'parent_id','level',
        '!id', '%name', 'BINARY name', 'BINARY seo_url' ,
        'is_feature', 'is_new',  'is_in_menu',
        'created', 'created_to',
    );
    public $fields_rule = array(
        'name' => 'required',
        //'parent_id' => 'required|callback__check_parent',
        'parent_id' => ['parent','callback__check_parent'],
    );


    //public $fields_type_currency = array( 'price',  );
    //public $fields_type_relation_cat = array( 'price',  );
    public $fields_type_image = array(/*'avatar',*/ 'image', 'banner', 'icon');
    public $fields_type_content = array( 'brief','description',);
    //public $fields_type_list_json = array('common_data', 'stats_data');
    //public $fields_list_comma = array('common_data', 'stats_data');
    public $actions_row = array('edit', 'del', 'feature', 'feature_del', 'translate');
    public $actions_list = array('del');


    /*
     * ------------------------------------------------------
     *  Main handle
     * ----------------------------------------------------
     */
    function get_list_show_in_menu($filter = [])
    {
        $filter['status'] = 1;
        $filter['is_in_menu'] = 1;
        $input = array();
        $input['order'] = array('sort_order', 'asc');
        return $this->filter_get_list($filter, $input);
    }
    /**
     * Tim kiem du lieu
     */
    function _search($field, $key)
    {
        switch ($field) {
            case 'name': {
                $this->db->like($this->table . '.' . $field, $key);
                break;
            }
        }
    }
    /**
     * Filter handle
     *
     */
    function _filter_get_where(array $filter)
    {
        $where = parent::_filter_get_where($filter);
        //pr($filter);
        foreach ($this->fields_filter as $key) {
            if (isset($filter[$key]) && $filter[$key] != -1) {
                //echo '<br>key='.$key.', v='.$filter[$key];
                $this->_filter_parse_where($key, $filter);
            }
        }


        if (isset($filter['image'])) {
            $where[$this->table . '.image_id >'] = '0';
        }

        // Loc dang FIND_IN_SET
        foreach (array('tags') as $f) {
            if (isset($filter[$f]) && $filter[$f]) {
                $value = [];
                if (is_array($filter[$f])) {
                    foreach ($filter[$f] as $v) {
                        $value[] = "FIND_IN_SET(" . $this->db->escape($v) . ", `" . $f . "`)";
                    }
                } else
                    $value[] = "FIND_IN_SET(" . $this->db->escape($filter[$f]) . ", `" . $f . "`)";


                if ($value) {
                    $this->db->where('((' . implode(') or (', $value) . '))');
                }
            }
        }
        //=== Su ly loc theo ngay tao
        //  1: tu ngay  - den ngay
        if (isset($filter['created']) && isset($filter['created_to'])) {
            $where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
            $where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
        } //2: tu ngay
        elseif (isset($filter['created'])) {
            $where[$this->table . '.created >='] = is_numeric($filter['created']) ? $filter['created'] : get_time_from_date($filter['created']);
        } //3: den ngay
        elseif (isset($filter['created_to'])) {
            $where[$this->table . '.created <='] = is_numeric($filter['created_to']) ? $filter['created_to'] : get_time_from_date($filter['created_to']) + 24 * 60 * 60;// phai cong them 1 ngay de thoi gian no la cuoi cua ngay hien thoi
        }

        // hien thi san pham phia ngoai
        if (isset($filter['show'])) {
            $where[$this->table . '.status'] = '1';
        }
        return $where;
    }


    /*
     * ------------------------------------------------------
     *  Xu ly Tree
     * ------------------------------------------------------
     */
   

     function get_tree($where=array(),$style="default")
    {
        $parent_id=0;
        if(isset($where['parent_id'])){
            $parent_id =$where['parent_id'];
            unset($where['parent_id']);
        }
        // pr($where);
        $where['show']=1;
        $input=array('sort_order'=>array('field'=>'sort_order','direct'=>'asc'));
        $data= $this->filter_get_list($where,$input);
        //pr_db($data);
        if(!$data) return ;
        foreach ($data as $row) {
            $row = mod("blog_cat")->add_info($row);
        }
        $children=$this->_make_relation($data) ;
        $style ="_tree_".$style;
        $list = $this->{$style}($parent_id, $children);
        return $list;
    }

    function _tree_default($id, &$children, $output='', $maxlevel = 9999, $level = 0, $indent = '', $link_setting = array())
    {
        $fieldtitle = $this->field_parent_name;
        $fieldkey = $this->key;
        if (@$children[$id] && $level <= $maxlevel) {
            $count_li = 1;
            $total_li = count($children[$id]);
            foreach ($children[$id] as $t) {
                $id = $t->$fieldkey;
                $url_cur = $linkCategory = $parent_islink = null;
                if (isset($link_setting['url_cur']))
                    $url_cur = $link_setting['url_cur'];
                if (isset($link_setting['parent_islink']))
                    $parent_islink = $link_setting['parent_islink'];

                $linkCategory = $t->_url_view;
                $fn = "";
                $class_li = '';
                $current_li = '';
                $active = '';
                if ($count_li % 2 == 0)
                    $class_li .= " even ";// chan
                else {
                    $class_li .= " odd ";// le
                    if ($count_li == 1)
                        $class_li .= " first-child ";
                }
                if ($count_li == $total_li) {
                    $class_li .= " last-child ";
                }
                if ($linkCategory == $url_cur) {
                    $active = ' active ';
                    $class_li .= ' active ';
                    $current_li = ' id ="current" ';
                }
                if($t->icon_id && isset($t->icon->url))
                    $icon = '<img src="'.$t->icon->url.'" alt="img">';
                elseif($t->icon_fa){
                    $icon = '<icon class="fa fa-'.$t->icon_fa.'"></icon> ';
                }
                else{
                    $icon = '<icon class="fa fa-arrows"></icon> ';
                }

                if (count(@$children[$id]) > 0)// neu khac null ->co con
                {
                    $datanode =
                        '<a href="Javascript:;"  class="level' . $level . $active . '">
						 <span class="icon">' . $icon . '</span>
						 <span class="text">' . $t->$fieldtitle . '</span>
					   </a>';
                    $output .= '<li class="' . $class_li . '  parent level' . $level . '"  ' . $current_li . '>' . $datanode .
                        '	<span class="dropdown-toggle"><span>toggle</span></span>
					        <ul class="submenu">
						';
                    $fn = "</ul></li>"; // neu co con   ;
                } else {
                    $output .= '<li class=" ' .  $class_li . ' level' . $level . ' " ' . $current_li . '>
					<a href="' . $linkCategory . '"  class=" level' . $level . $active . '" >
					 	 <span class="icon">' . $icon . '</span>
						 <span class="text">' . $t->$fieldtitle . '</span>
					</a></li>'; // neu co con ';// i=1 la tu them vao cho do bi loi
                }
                $output = $this->_tree_default($id, $children, $output, $maxlevel, $level + 1, $indent, $link_setting);
                $output .= $fn;
                $count_li++;
            }
        }
        return $output;
    }
    function _tree_style2($id, &$children, $output='', $maxlevel = 9999, $level = 0, $indent = '', $link_setting = array())
    {
        $fieldtitle = $this->field_parent_name;
        $fieldkey = $this->key;
        if (@$children[$id] && $level <= $maxlevel) {
            $count_li = 1;
            $total_li = count($children[$id]);
            foreach ($children[$id] as $t) {
                $id = $t->$fieldkey;
                $url_cur = $linkCategory = $parent_islink = null;
                if (isset($link_setting['url_cur']))
                    $url_cur = $link_setting['url_cur'];
                if (isset($link_setting['parent_islink']))
                    $parent_islink = $link_setting['parent_islink'];

                $linkCategory = $t->_url_view;
                $fn = "";
                $class_li = '';
                $current_li = '';
                $active = '';
                if ($count_li % 2 == 0)
                    $class_li .= " even ";// chan
                else {
                    $class_li .= " odd ";// le
                    if ($count_li == 1)
                        $class_li .= " first-child ";
                }
                if ($count_li == $total_li) {
                    $class_li .= " last-child ";
                }
                if ($linkCategory == $url_cur) {
                    $active = ' active ';
                    $class_li .= ' active ';
                    $current_li = ' id ="current" ';
                }
                if (count(@$children[$id]) > 0)// neu khac null ->co con
                {
                    //<a href="#" style="background-image: url(images/feature2.png);"><span>Ki?n th?c cu?c s?ng</span></a>
                    $bg='';
                    if($level==0 && $t->icon->url)
                        $bg = 'style="background-image: url('.$t->icon->url.');"';
                    $datanode =
                        '<a href="Javascript:;" '.$bg.'  class="level' . $level . $active . '">
						 <span>' . $t->$fieldtitle . '</span>
					   </a>';
                    /*if($parent_islink)
                    {
                        $datanode='<a href="'.$linkCategory.'" class="level'.$level.$active.'" ><span>'.$t->$fieldtitle.'</span></a>';
                    }*/

                    $output .= '<li class="feature-element ' . $class_li . '  parent level' . $level . '"  ' . $current_li . '>' . $datanode .
                        '<ul class="wrap-menu-list desktop clearfix " >
						';
                    $fn = "</ul></li>"; // neu co con   ;
                } else {
                    $main_sub = ''; $bg=''; $icon= '';
                    if ($level == 0 && $t->icon->url){
                        $main_sub = 'feature-element';
                        $bg = 'style="background-image: url('.$t->icon->url.');"';
                    }
                    else{
                        $main_sub = 'sub-element';

                        if($t->icon_id)
                            $bg = 'style="background-image: url('.$t->icon->url.');"';
                        elseif($t->icon_fa){
                            $icon = '<icon class="fa fa-'.$t->icon_fa.'"></icon> ';
                        }
                        else{
                            $icon = '<icon class="fa fa-arrows"></icon> ';
                        }

                    }
                    $output .= '<li class=" ' . $main_sub . $class_li . ' level' . $level . ' " ' . $current_li . '>
					<a href="' . $linkCategory . '" '.$bg.' class=" level' . $level . $active . '" >
					  <span>'.$icon . $t->$fieldtitle . '</span>
					</a></li>'; // neu co con ';// i=1 la tu them vao cho do bi loi
                }
                $output = $this->_tree_style2($id, $children, $output, $maxlevel, $level + 1, $indent, $link_setting);
                $output .= $fn;
                $count_li++;
            }
        }
        return $output;
    }
    function _tree_style1($id, &$children, $output, $maxlevel = 9999, $level = 0, $indent = '', $link_setting = array())
    {
        $fieldtitle = $this->field_parent_name;
        $fieldkey = $this->key;
        if (@$children[$id] && $level <= $maxlevel) {
            $count_li = 1;
            $total_li = count($children[$id]);
            foreach ($children[$id] as $t) {
                $id = $t->$fieldkey;
                $url_cur = $linkCategory = $parent_islink = null;
                if (isset($link_setting['url_cur']))
                    $url_cur = $link_setting['url_cur'];
                if (isset($link_setting['parent_islink']))
                    $parent_islink = $link_setting['parent_islink'];

                $linkCategory = $t->_url_view;
                $fn = "";
                $class_li = '';
                $current_li = '';
                $active = '';
                if ($count_li % 2 == 0)
                    $class_li .= " even ";// chan
                else {
                    $class_li .= " odd ";// le
                    if ($count_li == 1)
                        $class_li .= " first-child ";
                }
                if ($count_li == $total_li) {
                    $class_li .= " last-child ";
                }

                if ($linkCategory == $url_cur) {
                    $active = ' active ';
                    $class_li .= ' active ';
                    $current_li = ' id ="current" ';
                }
                if (count(@$children[$id]) > 0)// neu khac null ->co con
                {

                    $datanode =
                        '
						<a href="Javascript:;"  class=" level' . $level . $active . '">
						    <span class="link__icon udi udi-development"></span> ' . $t->$fieldtitle . '
					</a>';
                    /*if($parent_islink)
                    {
                        $datanode='<a href="'.$linkCategory.'" class="level'.$level.$active.'" ><span>'.$t->$fieldtitle.'</span></a>';
                    }*/

                    $output .= '<div class="c_link-bar__link active  ' . $class_li . '  parent level' . $level . '"  ' . $current_li . '>' . $datanode .
                        '
						    <div class="c_link-bar__dropdown">
                    <div class="dropdown__menu-list container">
						';
                    $fn = "</div></div></div>"; // neu co con   ;
                } else {
                    if ($level == 0) {

                        $output .= '<div  class="c_link-bar__link active ' . $class_li . ' level' . $level . ' " ' . $current_li . '>
					<a href="' . $linkCategory . '"  class="level' . $level . $active . '" >
					  <span class="link__icon udi udi-development"></span> ' . $t->$fieldtitle . '
					</a></div>'; // neu co con ';// i=1 la tu them vao cho do bi loi
                    } else {

                        $output .= '<div  class="menu__link ' . $class_li . ' level' . $level . ' " ' . $current_li . '>
					<a href="' . $linkCategory . '"  class="level' . $level . $active . '" >
					   <b class="menu__icon udi udi-web-development"></b>
                                <span class="menu__title">' . $t->$fieldtitle . '</span>

					</a></div>'; // neu co con ';// i=1 la tu them vao cho do bi loi
                    }
                }

                $output = $this->_tree_style1($id, $children, $output, $maxlevel, $level + 1, $indent, $link_setting);
                $output .= $fn;
                $count_li++;
            }
        }
        return $output;
    }


}