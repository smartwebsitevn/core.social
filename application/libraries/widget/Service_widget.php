<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_widget extends MY_Widget
{
    /**
     * Ham khoi dong
     */
    function __construct()
    {
        $this->lang->load('site/'.$this->_get_mod());
    }



    /**===============================
     * HANDLE FILTER
     * =================================*/
    function filter($filters=array(),$temp = '', $temp_options = array())
    {
        // sap xep
        $this->data['sort_orders'] = array('id|desc', 'view_total|desc', 'feature|desc', 'name|desc', 'year|desc', 'imdb|desc', 'rate|desc');
        // loc theo cac loai danh muc
        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }
        // loc theo tag
        $where = array();
        $input['where']["table"] = 'service';
        //$input['where']["table_cat"] = 'service';
        $input['where']["status"] = 1;
        $input['where']["feature"] = 1;
        $input['order'] = ['tag.id', 'desc'] ;// ['tag.id', 'desc'] ['tag.count_view', 'desc']['tag.id', 'random'],
        $input['limit'] = array(0, 5);
        $input['join'] =array(array('tag_value tv','tv.tag_id = tag.id'));
        $tags = model('tag')->get_list($input);
        //pr_db($tags);
       // $tags = model('tag')->get_list(["status"=>1,"feature"=>1,'']);
        $this->data['tags'] =$tags;


        $this->data['action'] = current_url();
        $this->data['filters'] = $filters;

        $temp = (!$temp) ? 'filter' : $temp;
        $temp = 'tpl::_widget/service/filter/filter_' . $temp;
        // Su ly hien thi temp hay tra ve du lieu
        return $this->_display_temp($temp,$temp_options);
    }


    /**===============================
     * HANDLE LIST
     * =================================*/

    /**
     * Hien thi danh sach cung the loai
     */
    function same_cat($cat_id=null,$options=[],$temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $feature= array_get($options, 'feature', false);
        $service_id = array_get($options, 'service_id', false);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order',  array('id', 'random'));

        // Create list
        if($cat_id)
            $filter['cat_id'] = $cat_id;
        if($service_id)
             $filter['id!'] = $service_id;
        if($feature)
            $filter['feature'] = 1;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu

        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);

    }

    function slide_show($options=[],$temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order',  array('id', 'desc'));

        $filter['slide'] = TRUE;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);

    }
    function feature($options=[], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order',  array('id', 'random'));

        // Get list
        $filter['feature'] = TRUE;
        //$this->data['url'] = site_url('service_list/home') . '?feature=1' . $type;

        // ==
        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);


        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);

    }
    function newest($options=[], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order',  array('updated', 'desc'));


        //$this->data['url'] = site_url('service_list/home') . '?order=id|desc' . $type;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);
        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }
    function viewest($options=[], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);

        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order',  array('view_total', 'desc'));
        // Get list
        $input = array();
       // $this->data['url'] = site_url('service_list/home') . '?order=view_total|desc' . $type;
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);
        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }

    /**
     * Tao danh sach hien thi
     */
    function get_list($filter, $input, $cache = FALSE)
    {
        // Gan filter
        $filter['show'] = '1';
        // Neu su dung cache
        if ($cache) {
            // Tai cac file thanh phan
            $this->load->driver('cache');
            // Lay du lieu trong cache
            $cache_name = 'w_service_' . $cache;
            $list = $this->cache->file->get($cache_name);
            // Neu khong ton tai thi lay trong data va cap nhat lai cache
            if ($list === FALSE) {
                $list = model('service')->filter_get_list($filter, $input);
                $list = $this->_get_list_add_info($list);
                $this->cache->file->save($cache_name, $list, 5 * 60);
            }
        } // Neu khong su dung cache thi get truc tiep tu data
        else {

            $list = model('service')->filter_get_list($filter, $input);
           // pr_db();
            $list = $this->_get_list_add_info($list);
        }
        $this->_ajax_pagination($filter, $input['limit'][1]);

        return $list;
    }

    /*
     * Them thuoc tinh vao Khoa hoc
     */
    function _get_list_add_info($list = array())
    {
        //Danh sach cach service_server dang duoc bat
        /* $inputs = array();
         $inputs['order'] = array('sort_order', 'asc');
         $inputs['where']['status'] = config('status_on', 'main');
         $service_servers = $this->service_server_model->get_list($inputs);*/

        // Tai cac file thanh phan
        //$this->load->helper('file');

        // Xu ly danh sach
        foreach ($list as $row) {
            $row = mod('service')->add_info($row);
            /*foreach (array('cat', 'tag') as $p) {
                $row->$p = model('service')->info_get($p, $row->id);
                foreach ($row->$p as $r) {
                    $r = site_create_url($p, $r);
                }
                // Get cat_ids
                if ($p == 'cat') {
                    $row->_cat_id = array();
                    foreach ($row->cat as $r) {
                        $row->_cat_id[] = $r->id;
                    }
                }
            }*/
        }

        return $list;
    }



    function display_list($list, $temp = '', $temp_options = array())
    {
        $this->data['list'] = $list;
        $pages_config = array_get($temp_options, 'pages_config', null);
        if ($pages_config)
            $this->data['pages_config'] = $pages_config;

        $temp = (!$temp) ? 'default' : $temp;
        $temp = 'tpl::_widget/service/display/list/list_' . $temp;
        // Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
        else
            $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    function display_pagination($pages_config = array(), $temp = '', $temp_options = array())
    {
        $this->data['pages_config'] = $pages_config;

        $temp = (!$temp) ? 'default' : $temp;
        $temp = 'tpl::_widget/service/display/pagination/' . $temp;
        // Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
        else
            $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    function _ajax_pagination($filter, $limit)
    {
        if (isset($filter['hide']))
            unset($filter['hide']);
        $pages_query = http_build_query($filter);
        $this->data['ajax_pagination'] = true;
        $this->data['ajax_pagination_url'] = site_url('service_list/filter_ac?' . $pages_query . '&per_page=' . $limit);
        $total = model('service')->filter_get_total($filter);
        $this->data['ajax_pagination_total'] = ceil($total / $limit);

    }



}