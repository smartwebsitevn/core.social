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
            //'product_ordered' => site_url('my-ordered'),
            'product_favorited' => site_url('my-favorited'),

        );
        if (mod("product")->setting('turn_off_function_order')) {
            unset($menu['product_favorited']);
        }

        $menu['tran'] = site_url('invoice_order');

        if (!user_can_do($user, 'verify') && !user_can_do($user, 'verify_edit')) {
            unset($menu['user_verify']);
        }

        /*if(mod("product")->setting('premium_turn_off_function_renew_plan')){
            foreach(array('renew_plan','renew_voucher','movie_ordered','deposit','tran') as $k){
                unset($menu[$k]);
            }
        }*/
        /*foreach (array('renew_plan', 'renew_voucher', 'product_owner', 'lesson_owner') as $k) {
            if (mod("product")->setting('premium_turn_off_function_' . $k)) {
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
            } else if ($item_cur == 'product_list') {
                $item_cur = 'product_owner';
            } else if ($item_cur == 'user' && $method == 'test_result') {
                $item_cur = 'test_result';
            } else if ($item_cur == 'product_management' && $method == 'product_tip_management') {
                $item_cur = 'product_tip_management';
            } else if ($item_cur == 'product_management' && $method == 'lesson_tip_management') {
                $item_cur = 'lesson_tip_management';
            } else if ($item_cur == 'product_management' && ($method == 'task_list_management' || $method == 'view_task_list')) {
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


    /**
     * Bang dieu khien cua thanh vien
     */
    function newest($size = 5)
    {
        $this->lang->load('admin/user');
        $input = array();
        $input['limit'] = array(0, $size);
        $list = model('user')->get_list($input);

        // Luu bien gui den view
        $this->data['list'] = $list;

        $this->data['user_total'] = model('user')->get_total();

        // Hien thi view
        $this->load->view('tpl::_widget/user/newest', $this->data);
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
}

