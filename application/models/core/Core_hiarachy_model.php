<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model Core Stats Class
 *
 * Class model xu ly thong ke
 *
 * @author		phamkhanhcuong@gmail.com
 * @version		2014-03-22
 */

class Core_hiarachy_model extends MY_Model {
    //==== hiarechy ====
    public $field_name = 'title';
    // Field parent id
    public $field_parent_id  = 'parent_id';
    // Field parent name
    public $field_parent_name = 'parent_name';
    // Field parent level
    public $field_parent_level = 'parent_level';

    /*
     * ------------------------------------------------------
     *  Main handle
     * ------------------------------------------------------
     */
    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

    }

    //============================== SETS FUNCTION PROCESS HIARACHY DATA
    function has_childs($id)
    {
        $childs = $this->get_list_childs($id);
        if(count($childs)>0)
            return true;
        return false;
    }
    function get_list_childs($id)
    {
        $list = $this->get_hierarchy_data(null,$id);
        $child=array();
        foreach($list as $l)
        {
            //echo '<br>co con';        print_r($l);
            $child[]=$l;
        }
        return $child;
    }

    function get_list_childs_level($id)
    {
        $list = $this->get_hierarchy_data(null,$id);
        $level=array();
        //echo '<br>list=';        print_r($list);
        foreach($list as $l)
        {
            //echo '<br>ssss=';        print_r($l);
            $level[]=$l[$this->field_parent_level];
        }
        //  echo '<br>Level Max='.max($level) ;
        //  print_r($level);
        return $level;
    }


    /*Node: with data hiarachy, we must get all data then limit(slide array) that data */
    function get_list_hierarchy($input = array(),$filter=[])
    {
        $result= $this->filter_get_list($filter,$input);
        $result=$this->_make_relation($result) ;
        $result= $this->_data_recurse(0,$result);
        return $result;
    }

    /*
        lay cac con cua cha, de kam du lieu (khong hien thi)
        + $branchparent:: nhanh term muon lay (0 la cac nhanh term cua Root)
           vd:
           0
               1
                   2
                       2.1
                       2.2
                   3
               4
           ta muon lay nhanh 2 thi teid = 2

         + $optparent:
               -Neu la so nguyen: id cua nhanh Term muon loai bo, Day thuong la TH Edit Term
              - Neu la mang tuc la muon loai bo/ chi lay nhieu nhanh Term chu khong phai la loai bo 1 nhanh Term nua ( Tinh nang phan quyen chuyen muc)
              cau truc cua mang do la:
                  $parent['type'] = 1 chi lay ; 0 loai bo Term trong danh sach
                  $parent['terms']= Danh sach term ngan cach bang dau phay : 1,2,3,4...
         + $maxlevel: level Term cao nhat co the lay ( nghia la chi lay ca term  co level <  maxlevel )

         + published : lay term theo trang thai
            1: chi lay term da bat
            0: chi lay term da tat
            null: lay tat
         + type: du lieu xuat ra dang mang hay la chuoi phan cap
           0: la mang
           1: la chuoi
         + link: ap dung voi truong hop type =1 , luc do link se duoc gan vao chuoi
       CHU Y : ham nay mac dinh chi hien term co publish = 1
               published co 3 trang thai
                   null
   */

    function get_hierarchy_data($optparent=null,$branchparent=0,$published=null,$maxlevel=9999)
    {

        /*
        chu y khi lay du lieu
        Khi lay ta len nhom du lieu theo parent id de qua trinh su li phia sau duoc nhanh chong
        */
        $data=$this->_prepareData(null,$optparent,$published) ;
        //pr($data);
        $children=$this->_make_relation($data) ;
        // echo  "<br>---list Child----<br>";   print_r($children);

        $list = $this->_data_recurse($branchparent, $children, array(), $maxlevel,0,'',$data);
        //echo  "<br>---list ----<br>";        print_r($list);

        return $list;
    }
    function get_hierarchy_string($link_setting,$optparent=null,$branchparent=0,$published=null,$maxlevel=9999)
    {

        /*
        chu y khi lay du lieu
        Khi lay ta len nhom du lieu theoparent id de qua trinh su liphia sau duoc nhanh chong
        */
        $data=$this->_prepareData(null,$optparent,$published) ;
        $children=$this->_make_relation($data) ;
        // echo  "<br>---list Child----<br>";   print_r($children);
        $list = $this->_string_recurse($branchparent, $children, '', $maxlevel,0,'',$link_setting);
        //echo  "<br>---list ----<br>";        print_r($list);
        return $list;
    }

    function get_hierarchy_filter($setting,$optparent=null,$branchparent=0,$published=null,$maxlevel=9999)
    {
        /*
        chu y khi lay du lieu
        Khi lay ta len nhom du lieu theoparent id de qua trinh su liphia sau duoc nhanh chong
        */
        $data=$this->_prepareData(null,$optparent,$published) ;
        $children=$this->_make_relation($data) ;
        // echo  "<br>---list Child----<br>";   print_r($children);
        $list = $this->_filter_recurse($branchparent, $children, '', $maxlevel,0,'',$setting);
        //echo  "<br>---list ----<br>";        print_r($list);
        return $list;
    }
    /* ================================================================================================================================
                                                  Khoi ma lenh su li cua lop
      ================================================================================================================================*/


    /*
       Lay du lieu chuan bi cho boc tach
       Input:
           + truy van Term               \
           + $void: id cua voca muon lay
           + $escid:
               -Neu la so nguyen: id cua nhanh Term muon loai bo, Day thuong la TH Edit Term
              - neu la mang tuc la muon loai bo/ chi lay nhieu nhanh Term chu khong phai la loai bo 1 nhanh Term nua ( Tinh nang phan quyen chuyen muc)
              cau truc cua mang do la:
                  $parent['type'] = 1 chi lay ; 0 loai bo Term trong danh sach
                  $parent['terms']= Danh sach term ngan cach bang dau phay : 1,2,3,4...
           + $publised: trang thai term muon lay, null lay tat
       Ouput: list object term

   */
    function _prepareData($query=null,$parent=null,$active=null)
    {
        /*
             chu y khi lay du lieu nhom du lieu theo t.void ,t.parent_id,t.ordering de qua trinh su li phia sau duoc nhanh chong va hien thi dung
        */
        $key=$this->key;
        if(!$query)// neu khong truyen
        {
            $query= ' SELECT * '.
                '  FROM '.$this->table;


        }
        $where =array();
        if(!is_null($active))
            $where[] =$this->field_status.' = '.(int)$active;
        //==========
        if(!is_null($parent))
        {
            if(is_array($parent))// neu la mang tuc la muon loai bo/ chi lay nhieu nhanh Term chu khong phai la loai bo 1 nhanh Term nua ( Tinh nang phan quyen chuyen muc)
            {
                if($parent['type'] == 1)// chi lay cac term trong danh sach
                    $where[] =$key.' IN ('.$parent['list'].')';
                else
                    $where[] = $key.' NOT IN ('.$parent['list'].')';
            }
            else
            {
                $where[] =$key.' <> '.(int)$parent;  // loai bo term ( hay gap khi edit Term)
            }
        }
        //=========
        $ORDERBY=  ' ORDER BY  '.$this->field_parent_id .','.$this->field_sort_order;
        //=========
        if($where)
            $query .=' WHERE ' .implode(' AND ',$where).$ORDERBY;
        else
            $query .=$ORDERBY;
        //  echo $query;
        //$parents = $this->query($query);
        $rows = $this->db->query($query);
        $parents = $rows->result();
        // echo $this->db->last_query();
        //pr($parents);
        //====== B_Auto Translate Language  ===========
        //ko bien dich khi dang trong quan tri
        /* if($this->auto_translate && $this->area !='admin' && $this->config->item('active_multi_language','main')){
               $parents= $this->translateList($parents);
         }*/
        //====== E_Auto Translate Language  ===========
        //print_r( $parents);
        return $parents;
    }

    /*
        Boc tach quan he Term
        Input: List Object Terms
        Ouput: list array:moi phan tu chua mang danh sach cach con cua no
            VD
               Array ( [0] => Array ( [0] => stdClass Object ( [teid] => 1 [termname] => Term1 [parent_id] => 0 [level] => 0 )
                                       [1] => stdClass Object ( [teid] => 4 [termname] => Term4 [parent_id] => 0 [level] => 0 )
                                     )
                      [1] => Array ( [0] => stdClass Object ( [teid] => 2 [termname] => Term2 [parent_id] => 1 [level] => 1 ) )
                      [2] => Array ( [0] => stdClass Object ( [teid] => 3 [termname] => Term3 [parent_id] => 2 [level] => 2 ) )
                    )
           Mo ta
            parent goc (0) co 2 con Term 1 va 4
            Term 1 co 1 con la term 2
            Term 2 co 1 con la term 3
          => Mang co dang
            Term1
                - Term2
                    - Term3
            Term4
    */
    function _make_relation($parents)
    {
        if(count($parents)<1)
            return array();
        $children = array();
        // first pass - collect children
        $field_parent_id =$this->field_parent_id ;
        foreach ($parents as $t)
        {
            $pt = $t->$field_parent_id ;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $t);
            $children[$pt] = $list;

        }
        //echo  "<br>---list Child----<br>";
        //print_r($children);
        return $children;
    }

    /*
        Input:
            - Children; chua danh sach term da duoc boc tach quan he
            - id : nhanh muon lay ( cai nay khia hay hehe )
            - list : danh sach tra ve
            - maxlevel : level toi da muon lay
            - level : level hien tai cua pt trong nhanh
            (chu y quan trong day chi la level tuong ung voi Cay hien thoi, con Level
              cho biet vi tri trong pha he term thi luu trong bang CSDL
              No chi chinh xac khi truyen vao id=0 (tuc la root) )
            - indent : tao kieu dang phan cap
        id: nhanh muon lay
    */
    function _data_recurse($id, &$children,$list=array(), $maxlevel=9999, $level=0,$indent='',$data=array())
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

                //== Su ly parent
                /* $tmp->{'_parent'} =array();
                 if($data && $id_parent > 0) {
                     foreach($data as $p)
                         if($p->$fieldkey == $id_parent)
                             $tmp->{'_parent'} = $p;
                 }*/
                //== Su ly sub
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
                //== Su ly hien thi moi lien he cha con
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
    function _string_recurse($id, &$children, $output, $maxlevel=9999, $level=0,$indent='',$link_setting=array())
    {

        $key=$this->key;
        //$parentactive=$item_active;//JRequest::getInt($key);
        $field_parent_id =$this->field_parent_id ;
        $fieldtitle=$this->field_parent_name; $fieldkey = $this->key;
        //echo  "<br>level max=".$maxlevel;
        //echo  "<br>level curr=".$level;
        if (@$children[$id] && $level <= $maxlevel)
        {

            $count_li=1; $total_li=count($children[$id]);
            foreach ($children[$id] as $t)
            {
                $id = $t->$fieldkey;
                $url_cur=$linkCategory=$parent_islink=null;
                if(isset($link_setting['url_cur']))
                    $url_cur=$link_setting['url_cur'];
                if(isset($link_setting['parent_islink']))
                    $parent_islink=$link_setting['parent_islink'];


                $linkCategory =site_url($link_setting['link'].'/'.$id,$t);
                //echo  "<br>Ifieldkey=".$fieldkey;				echo  "<br>children=";print_r($t);
                //$parent_id=$id;// id cha cua no

                //$linkTerm = $link.'/'.$parent_id.'/'.$id;

                $fn="";
                $class_li='';$current_li='';$active = '';
                if($count_li % 2 ==0)
                    $class_li .= " even ";// chan
                else {
                    $class_li .=" odd ";// le
                    if($count_li ==1)
                        $class_li .=" first-child ";
                }
                if($count_li ==$total_li)
                {  $class_li .=" last-child ";    }

                if( $linkCategory==$url_cur)
                {
                    $active = ' active ';
                    $class_li .= ' active ';
                    $current_li =' id ="current" ';
                }
                if(count(@$children[$id])>0)// neu khac null ->co con
                {
                    $datanode='<a href="Javascript:;"  class="level'.$level.$active.'"><span>'.$t->$fieldtitle.'</span></a>';
                    if($parent_islink)
                    {
                        $datanode='<a href="'.$linkCategory.'" class="level'.$level.$active.'" ><span>'.$t->$fieldtitle.'</span></a>';
                    }

                    $output .= '<li class="'.$class_li.'  parent level'.$level.'"  '.$current_li.'>'.$datanode.'<ul>';
                    $fn="</ul></li>"; // neu co con   ;
                }
                else
                {
                    $output .= '<li class="'.$class_li.' level'.$level.' " '.$current_li.'><a href="'. $linkCategory.'"  class="level'.$level.$active.'" ><span>'.$t->$fieldtitle.'</span></a></li>'; // neu co con ';// i=1 la tu them vao cho do bi loi
                }

                $output = $this->_string_recurse($id, $children,$output, $maxlevel, $level+1, $indent,$link_setting);
                $output .= $fn;
                $count_li++;
            }
        }
        return $output;
    }
    function _filter_recurse($id, &$children, $output, $maxlevel=9999, $level=0,$indent='',$setting=array())
    {

        $key=$this->key;
        $field_parent_id =$this->field_parent_id ;
        $fieldtitle=$this->field_parent_name; $fieldkey = $this->key;
        //echo  "<br>level max=".$maxlevel;
        //echo  "<br>level curr=".$level;
        if (@$children[$id] && $level <= $maxlevel)
        {
            $count_li=1; $total_li=count($children[$id]);
            foreach ($children[$id] as $t)
            {
                $id = $t->$fieldkey;
                if(is_array($setting['item_current']))
                    $item_current=$setting['item_current'];
                else
                    $item_current =array($setting['item_current']);
                $fn="";
                $class_li='';$current_li='';$input='';$active='';
                if($count_li % 2 ==0)
                    $class_li .= " even ";// chan
                else {
                    $class_li .=" odd ";// le
                    if($count_li ==1)
                        $class_li .=" first-child ";
                }
                if($count_li ==$total_li)
                {  $class_li .=" last-child ";    }

                if(in_array($id,$item_current))
                {
                    $active="active";
                    $class_li .= ' active ';
                    $current_li =' id ="current" ';$type_active='active';
                    $input = '<input type="hidden" value="'.$id.'" name="'.$fieldkey.'[]">';
                }
                $a= '<a href="Javascript:;" _name="'.$fieldkey.'[]" _value="'.$t->$fieldkey.'" class="'.$active.'"><span>'.$t->$fieldtitle.'</span>'.$input.'</a>';
                if(count(@$children[$id])>0)// neu khac null ->co con
                {
                    $output .= '<li class=" filter '.$class_li.'  parent level'.$level.'"  '.$current_li.'>
                                    '.$a.'
                                    <ul>';
                    $fn='</ul></li>'; // neu co con   ;
                }
                else
                {
                    $output .= '<li  class=" filter '.$class_li.' level'.$level.' " '.$current_li.'>
                                     '.$a.'
                                </li>'; // neu co con ';// i=1 la tu them vao cho do bi loi
                }

                $output = $this->_filter_recurse($id, $children,$output, $maxlevel, $level+1, $indent,$setting);
                $output .= $fn;
                $count_li++;
            }
        }
        return $output;
    }


}