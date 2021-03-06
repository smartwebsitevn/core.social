<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_notice_widget extends MY_Widget
{
    /**
     * Ham khoi dong
     */
    function __construct()
    {
        $this->lang->load('site/' . $this->_get_mod());
    }


    /**===============================
     * HANDLE LIST
     * =================================*/


    function newest($options = [], $temp = 'dropdown', $temp_options = array())
    {
        $user =user_get_account_info();
        if(!$user) return;
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'desc'));

        $filter['user_id'] = $user->id;
       // $filter['readed'] = false;
        $filter['show'] = TRUE;

        //==nread
        $this->data['total_unread'] =     model('user_notice')->filter_get_total(array_merge($filter,['readed'=>0]));

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
            $cache_name = 'w_user_notice_' . $cache;
            $list = $this->cache->file->get($cache_name);
            // Neu khong ton tai thi lay trong data va cap nhat lai cache
            if ($list === FALSE) {
                $list = model('user_notice')->filter_get_list($filter, $input);
                $list = $this->_get_list_add_info($list);
                $this->cache->file->save($cache_name, $list, 5 * 60);
            }
        } // Neu khong su dung cache thi get truc tiep tu data
        else {

            $list = model('user_notice')->filter_get_list($filter, $input);
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
        //Danh sach cach user_notice_server dang duoc bat
        /* $inputs = array();
         $inputs['order'] = array('sort_order', 'asc');
         $inputs['where']['status'] = config('status_on', 'main');
         $user_notice_servers = $this->user_notice_server_model->get_list($inputs);*/

        // Tai cac file thanh phan
        //$this->load->helper('file');

        // Xu ly danh sach
        foreach ($list as $row) {
            $row = mod('user_notice')->add_info($row);
            /*foreach (array('cat', 'tag') as $p) {
                $row->$p = model('user_notice')->info_get($p, $row->id);
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
        $this->data['pages_config'] = array_get($temp_options, 'pages_config', null);
        $this->data['load_more'] = array_get($temp_options, 'load_more', null);

        $temp = (!$temp) ? 'default' : $temp;
        $temp = 'tpl::_widget/user_notice/display/list/list_' . $temp;
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
        $temp = 'tpl::_widget/user_notice/display/pagination/' . $temp;
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
        $this->data['ajax_pagination_url'] = site_url('user_notice_list/filter_ac?' . $pages_query . '&per_page=' . $limit);
        $total = model('user_notice')->filter_get_total($filter);
        $this->data['ajax_pagination_total'] = ceil($total / $limit);

    }


}