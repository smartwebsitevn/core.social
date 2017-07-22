<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\User\UserFactory;

class User_widget extends MY_Widget
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        // Tai cac file thanh phan
        $this->lang->load('site/user');
    }

    /**
     * Bang dieu khien cua account
     */
    function account_panel()
    {
        // Tai cac file thanh phan
        $this->load->helper('user');
        $this->load->model('user_model');

        // Bien luu thong tin user da dang nhap hay chua
        $is_login = user_is_login();
        $this->data['is_login'] = $is_login;

        // Tao cac lien ket
        $user = user_get_account_info();
        $user = user_add_info($user);
        $user = site_create_url('account', $user);
        $user = UserFactory::auth()->user($user);
        // Luu cac bien gui den view
        $this->data['user'] = $user;
        $this->data['action_login'] = site_url('user/login') . '?fast=true';

        // Hien thi view
        $this->load->view('tpl::_widget/user/panel', $this->data);
    }

    /**
     * Bang dieu khien cua thanh vien
     */
    function user_panel($item_cur = '')
    {

        // Lay thong tin user hien tai
        $user = user_get_account_info();
        $user = user_add_info($user);
        $user = site_create_url('account', $user);
        $user = UserFactory::auth()->user($user);

        /*$menu = config('menu', 'widget/user');
        $menu_lang = config('menu_lang', 'widget/user');
        $menu_icon_group = config('menu_icon_group', 'widget/user');
        $items_url = config('menu_url', 'widget/user');*/

        // Tao menu
        $menu = array(
            'user' => site_url('user'),
            //'user_bank' => site_url('user_bank'),
            //'affiliate' => site_url('affiliate'),
            //'message' => site_url('message'),
            'user_edit'             => site_url('user/edit'),
            //'user_verify'	        => site_url('user/verify'),
            //'deposit' => site_url('deposit_card'),
            //'withdraw' => site_url('withdraw'),

            //'renew_plan' => site_url('buy-vip'),
            //'renew_voucher'       => site_url('voucher'),
            //'user_ordered' => site_url('my-ordered'),
            'user_favorited' => site_url('my-favorited'),

        );
        if (mod("user")->setting('turn_off_function_order')) {
            unset($menu['user_favorited']);
        }

        $menu['tran'] = site_url('invoice_order');

        if (!user_can_do($user, 'verify') && !user_can_do($user, 'verify_edit')) {
            unset($menu['user_verify']);
        }

        /*if(mod("user")->setting('premium_turn_off_function_renew_plan')){
            foreach(array('renew_plan','renew_voucher','movie_ordered','deposit','tran') as $k){
                unset($menu[$k]);
            }
        }*/
        /*foreach (array('renew_plan', 'renew_voucher', 'user_owner', 'lesson_owner') as $k) {
            if (mod("user")->setting('premium_turn_off_function_' . $k)) {
                unset($menu[$k]);
            }
        }*/



        // Lay item cur
        /*if (!$item_cur) {
            $controller = $this->uri->rsegment(1);
            $method = $this->uri->rsegment(2);
            // var_dump($controller,$method);
            $item_cur = $controller;
            if ($item_cur == 'user' && ($method == 'edit' || $method == 'verify')) {
                $item_cur = $item_cur . '_' . $method;
            } else if ($item_cur == 'tran' && ($method == 'deposit')) {
                $item_cur = 'deposit';
            } else if ($item_cur == 'lesson_list') {
                $item_cur = 'lesson_owner';
            } else if ($item_cur == 'user_list') {
                $item_cur = 'user_owner';
            } else if ($item_cur == 'user' && $method == 'test_result') {
                $item_cur = 'test_result';
            } else if ($item_cur == 'user_management' && $method == 'user_tip_management') {
                $item_cur = 'user_tip_management';
            } else if ($item_cur == 'user_management' && $method == 'lesson_tip_management') {
                $item_cur = 'lesson_tip_management';
            } else if ($item_cur == 'user_management' && ($method == 'task_list_management' || $method == 'view_task_list')) {
                $item_cur = 'task_list_management';
            } else if ($item_cur == 'invoice_order' && $method == 'index') {
                $item_cur = 'tran';
            }
        }*/

        // Luu bien gui den view
        $this->data['user'] = $user;
        $this->data['menu_items'] = $menu;
        $this->data['item_cur'] = $item_cur;

        // Hien thi view
        $this->load->view('tpl::_widget/user/user_panel', $this->data);
    }



    /*
   * Thông báo cho thành viên
   */
    function notify()
    {
        if (!user_is_login()) return;

        $user = user_get_account_info();
        $user = user_add_info($user);
        $this->data['user'] = $user;
        //lay pin hien tai cua thanh vien
        $balance_pin = model('user')->balance_get($user->id, 'balance_pin');

        $ms = array();
        if ($user->_status != 'active') {
            $status = lang('user_status') . ': <b>' . lang('user_status_' . $user->_status) . '</b>';
            if ($user->_status == 'delete') {
                $user_del = model('user_del')->get($user->id);
                if ($user_del && $user_del->status == 'completed') {
                    $user_del->confim_time = $user_del->confim_time + 48 * 60 * 60;
                    $status .= ' - <span class="kkcountdown-1" data-time="' . $user_del->confim_time . '" style="color:red"></span> - (<a href="' . site_url('user/undel') . '" class="confim">Hủy xóa</a>)';
                }

            }
            $ms[] = $status;
        }
        if ($user->activation == 0) {
            $ms[] = '<b>Tài khoản chưa được kích hoạt, vui lòng đăng nhập vào email để kích hoạt tài khoản</b>';
        }

        $get_total_processing = model('tran_ph')->get_total_processing($user->id);
        if ($get_total_processing == 1) //nếu chỉ còn 1 ph lót
        {
            $ms[] = '<b>Đăng ký thêm 1 PH để được PH lần 2</b>';
        }
        if ($balance_pin <= 0) {
            $ms[] = '<b>Hãy mua pin để duy trì tài khoản. Tài khoản sẽ bị xóa nếu sau 48h không có pin trong tài khoản</b>';
        }

        $is_holiday = check_day_holiday();
        if ($is_holiday) {
            $ms[] = '<b>' . lang('holiday_notice') . '</b>';
        }
        $this->data['ms'] = $ms;

        // Hien thi view
        $this->load->view('tpl::_widget/user/notify', $this->data);
    }

    /*
       * Menu cho thành viên
       */
    public function menu($current = null, $temp = '')
    {
        $this->data = array('current' => $current);
        $temp = (!$temp) ? 'account' : $temp;
        $temp = 'tpl::_widget/user/menu/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }





    /**
     * Yeu thich
     */

    function owner($type, $temp = '')
    {
        $user = user_get_account_info();
        $total = 0;
        switch ($type) {
            case 'favorited':
                if ($user) {
                    // $can_do = true;
                    $total = model('user_to_favorite')->get_total(['user_id' => $user->id]);
                } else {
                    $list = mod('user')->guest_owner_get($type);
                    $total = count($list);
                }
                break;
        }

        $this->data['total'] = $total;
        $temp = (!$temp) ? 'favorited' : $temp;
        $temp = 'tpl::_widget/user/owner/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }





    /**===============================
     * HANDLE FILTER
     * =================================*/
    function filter($filter = array(), $temp = '', $temp_options = array())
    {
        //== lay ra thong so loc da luu
        $total_rows = mod('user')->sess_data_get('list_total_rows');
        $filter_input = mod('user')->sess_data_get('list_filter_input');
        $sort_orders = mod('user')->sess_data_get('list_sort_orders');
        $sort_order = mod('user')->sess_data_get('list_sort_order');
        //pr($filter_input);
        if ($filter_input) {
            $filter = array_merge($filter, $filter_input);
        }


        // loc theo tag
        $where = array();
        $input['where']["table"] = 'user';
        //$input['where']["table_cat"] = 'user';
        $input['where']["status"] = 1;
        $input['where']["feature"] = 1;
        $input['order'] = ['tag.id', 'desc'];// ['tag.id', 'desc'] ['tag.count_view', 'desc']['tag.id', 'random'],
        $input['limit'] = array(0, 5);
        $input['join'] = array(array('tag_value tv', 'tv.tag_id = tag.id'));
        $tags = model('tag')->get_list($input);
        //pr_db($tags);
        // $tags = model('tag')->get_list(["status"=>1,"feature"=>1,'']);

        $this->data['tags'] = $tags;
        $this->data['action'] = current_url();
        $this->data['filter'] = $filter;
        $this->data['sort_order'] = $sort_order;
        $this->data['sort_orders'] = $sort_orders;
        $this->data['total_rows'] = $total_rows;


        $this->data['user_groups'] = model('user_group')->filter_get_list(['show' => 1]);

        // loc theo cac loai danh muc
        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }
        // Lay danh sach country, city
        $this->data['countrys'] = model('country')->filter_get_list(['show' => 1]);
        // $this->data['countrys'] = model('country')->get_grouped();
        $this->data['citys'] = model('city')->filter_get_list(['show' => 1,'country_id'=>230]);
        // lay cac loai danh muc
        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }
        // lay cac loai range
        $range_types = mod('range')->get_range_types();
        foreach ($range_types as $t) {
            $this->data['range_type_' . $t] = model('range')->get_type($t);
        }
        $temp = (!$temp) ? 'filter' : $temp;
        $temp = 'tpl::_widget/user/filter/filter_' . $temp;
        return $this->_display_temp($temp, $temp_options);

    }


    /**===============================
     * HANDLE LIST
     * =================================*/

    /**
     * Hien thi danh sach cung the loai
     */
    function same_cat($cat_id = null, $options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $feature = array_get($options, 'feature', false);
        $user_id = array_get($options, 'user_id', false);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'random'));

        // Create list
        if ($cat_id)
            $filter['cat_id'] = $cat_id;
        if ($user_id)
            $filter['id!'] = $user_id;
        if ($feature)
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



    function feature($options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);

        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'random'));

        // Get list
        $filter['feature'] = TRUE;
        //$this->data['url'] = site_url('user_list/home') . '?feature=1' . $type;

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

    function newest($options = [], $temp = '', $temp_options = array())
    {
        return;
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'desc'));


        //$this->data['url'] = site_url('user_list/home') . '?order=id|desc' . $type;

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

    function viewest($options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('view_total', 'desc'));
        // Get list
        $input = array();
        // $this->data['url'] = site_url('user_list/home') . '?order=view_total|desc' . $type;
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
            $cache_name = 'w_user_' . $cache;
            $list = $this->cache->file->get($cache_name);
            // Neu khong ton tai thi lay trong data va cap nhat lai cache
            if ($list === FALSE) {
                $list = model('user')->filter_get_list($filter, $input);
                $list = $this->_get_list_add_info($list);
                $this->cache->file->save($cache_name, $list, 5 * 60);
            }
        } // Neu khong su dung cache thi get truc tiep tu data
        else {

            $list = model('user')->filter_get_list($filter, $input);
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
        //Danh sach cach user_server dang duoc bat
        /* $inputs = array();
         $inputs['order'] = array('sort_order', 'asc');
         $inputs['where']['status'] = config('status_on', 'main');
         $user_servers = $this->user_server_model->get_list($inputs);*/

        // Tai cac file thanh phan
        //$this->load->helper('file');

        // Xu ly danh sach
        foreach ($list as $row) {
            $row = mod('user')->add_info($row);
            /*foreach (array('cat', 'tag') as $p) {
                $row->$p = model('user')->info_get($p, $row->id);
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
        $temp = 'tpl::_widget/user/display/list/list_' . $temp;
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
        $temp = 'tpl::_widget/user/display/pagination/' . $temp;
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
        $this->data['ajax_pagination_url'] = site_url('user_list/filter_ac?' . $pages_query . '&per_page=' . $limit);
        $total = model('user')->filter_get_total($filter);
        $this->data['ajax_pagination_total'] = ceil($total / $limit);

    }
    /**===============================
     * HANDLE ACTION
     * =================================*/
    /**
     * Dang ky theo doi
     */
    function action_subscribe($user, $temp = '')
    {
        $user_current = user_get_account_info();

        $id = $user->id;
        $can_do = false;
        $subscribed = false;
        if ($user_current) {
            $can_do = true;

            $data = array();
            $data ['action'] = 'subscribe';
            $data ['table'] = 'user';
            $data ['table_id'] =$id;
            $subscribed = mod('user_storage')->get($user_current->id, $data);
        }

        $this->data['can_do'] = $can_do;
        $this->data['user'] = $user;
        $this->data['user_current'] = $user_current;
        $this->data['subscribed'] = $subscribed;
        $this->data['url_subscribe'] =$user->_url_subscribe;
        $this->data['url_subscribe_del'] = $user->_url_subscribe_del;

        $temp = (!$temp) ? 'subscribe' : $temp;
        $temp = 'tpl::_widget/user/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));

    }

    /**
     * Yeu thich
     */
    function action_favorite($user, $temp = '')
    {
        return;
        $id = $user->id;
        $can_do = true;
        $favorited = false;
        $user = user_get_account_info();
        if ($user) {
            // $can_do = true;
            $favorited = model('user_to_favorite')->check_exits(array('user_id' => $id, 'user_id' => $user->id));
        } else {
            $favorieds = $list = mod('user')->guest_owner_get("favorited");;
            // pr($favorieds);
            if (in_array($id, $favorieds)) {
                $favorited = TRUE;
            }
        }

        $this->data['can_do'] = $can_do;
        $this->data['user'] = $user;
        $this->data['favorited'] = $favorited;

        $this->data['url_favorite'] = site_url('user/favorite/' . $id);
        $this->data['url_favorite_del'] = site_url('user/favorite_del/' . $id);


        $temp = (!$temp) ? 'favorite' : $temp;
        $temp = 'tpl::_widget/user/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }


    /**
     * Danh gia
     */
    function action_raty($user, $temp = '')
    {
        $id = $user->id;
        $can_do = false;
        $rated = false;
        $user = user_get_account_info();
        if ($user) {
            $can_do = true;
            $rated = model('user_to_raty')->check_exits(array('user_id' => $id, 'user_id' => $user->id));
        }


        $this->data['can_do'] = $can_do;
        $this->data['user'] = $user;
        $this->data['rated'] = $rated;

        $this->data['url_raty'] = site_url('user/raty/' . $id);


        $temp = (!$temp) ? 'raty' : $temp;
        $temp = 'tpl::_widget/user/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }


    /**
     * Bao cao
     */
    function action_report($user, $temp = '')
    {
        $id = $user->id;
        $can_do = false;
        $reported = false;
        $user = user_get_account_info();
        if ($user) {
            $can_do = true;
            $reported = model('user_to_report')->check_exits(array('user_id' => $id, 'user_id' => $user->id));
        }

        $this->data['user'] = $user;
        $this->data['can_do'] = $can_do;
        $this->data['reported'] = $reported;
        $this->data['captcha'] = site_url('captcha');
        $this->data['url_report'] = site_url('user/report/' . $id);
        // Hien thi view
        $temp = (!$temp) ? 'report' : $temp;
        $temp = 'tpl::_widget/user/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }




    /**
     * Action khac
     */
    function action_message($row,$temp = '')
    {
        $can_do = true;
        $this->data['can_do'] = $can_do;

        $this->data['url_message'] = $row->_url_message;
        // Hien thi view
        $temp = (!$temp) ? 'message' : $temp;
        $temp = 'tpl::_widget/user/action/'.$temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    function action_share($row,$temp = '')
    {
        $this->data['url_share'] = $row->_url_view;
        // Hien thi view
        $temp = (!$temp) ? 'share' : $temp;
        $temp = 'tpl::_widget/user/action/'.$temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    function action_close($temp = '')
    {
        // Hien thi view
        $temp = (!$temp) ? 'close' : $temp;
        $temp = 'tpl::_widget/user/action/'.$temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    function action_toggle_light($temp = '')
    {

        // Hien thi view
        $temp = (!$temp) ? 'toggle_light' : $temp;
        $temp = 'tpl::_widget/user/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
}

