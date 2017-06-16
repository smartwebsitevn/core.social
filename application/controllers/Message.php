<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Message extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->load->helper('message');

        $this->load->model('message_receive_model');
        $this->load->model('message_model');


        $this->lang->load('site/message');
        if (!user_is_login()) {
            redirect();
        }
        //lay thong tin nguoi bao tro
        $user = user_get_account_info();
        $this->data['user_login'] = $user;

        //Trường hợp trông nom, do người khác add hộ
        $action = strval($this->uri->rsegment(2));
        if (in_array($action, array('view', 'view_send'))) {
            $user_id = intval($this->uri->rsegment(4));
        } else {
            $user_id = intval($this->uri->rsegment(3));
        }

        $this->data['user'] = $user;

    }

    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $rules = array();
        $rules['username'] = array('username', 'required|trim|xss_clean|callback__check_username');
        $rules['pin'] = array('pin', 'required|trim|xss_clean|callback__check_pin');
        $rules['title'] = array('title', 'required|trim|xss_clean');
        $rules['content'] = array('content', 'required|trim|xss_clean|callback__check_content');

        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra content nay co ton tai hay khong
     */
    public function _check_content($value)
    {
        $message_max = 255;//intval(model('setting')->get('config-message_max'));
        if ($message_max == 0) return true;
        if (strlen($value) > $message_max) {
            $this->form_validation->set_message(__FUNCTION__, lang('message_max', $message_max));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra username nay co ton tai hay khong
     */
    public function _check_username($value)
    {
        $user = $this->data['user'];
        $usernames = array();
        $value = explode(',', $value);
        foreach ($value as $username) {
            $username = trim($username);
            if ($user->username == $username) continue;
            $where = array();
            $where['username'] = strtolower($username);
            $id = $this->user_model->get_id($where);
            if ($id) {
                $usernames[] = $id;
            }
        }
        $this->data['usernames'] = $usernames;
        if (empty($usernames)) {
            $this->form_validation->set_message(__FUNCTION__, lang('required'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Kiem tra ma bao mat
     */
    public function _check_pin($value)
    {
        $user = $this->data['user'];
        $pin = security_encode($value);
        if ($user->pin != $pin) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Danh sach
     */
    function index()
    {
        //lay thong tin tai khoan
        $user = $this->data['user'];
        $this->load->model('message_model');
        $this->load->model('message_receive_model');
        $this->load->helper('message');
        $this->load->helper('form');

        // Lay gia tri cua filter dau vao
        $filter_input = array();
        $filter_fields = array('id', 'title', 'admin_readed', 'created', 'created_to');
        foreach ($filter_fields as $f) {
            $v = $this->input->get($f);
            $filter_input[$f] = $v;
        }

        $this->data['filter'] = $filter_input;

        // Tao bien filter
        $filter = array();
        $filter['user'] = $user->id;
        foreach ($filter_input as $f => $v) {
            if (!$v) continue;

            switch ($f) {
                case 'created': {
                    $created_to = $filter_input['created_to'];
                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
                    $v = get_time_between($v);
                    if (!$v) continue;
                    break;
                }
            }
            $filter[$f] = $v;
        }

        // Lay tong so
        $total = $this->message_model->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = min($limit, get_limit_page_last($total, $page_size));
        $limit = max(0, $limit);

        // Lay danh sach
        $input = array();
        $input['limit'] = array($limit, $page_size);
        $list = $this->message_model->filter_get_list($filter, $input);
        foreach ($list as $row) {
            $row = message_add_info($row);

            $_input = array(
                'limit' => array(0, 3),
                'where' => array('message_id' => $row->id)
            );
            $receives = $this->message_receive_model->get_list_receive($_input);
            $row->receives = $receives;

            if ($row->user_execute) {
                $user_execute = model('user')->get_info($row->user_execute, 'username');
                $row->user_execute = $user_execute;
            }

        }
        $this->data['list'] = $list;

        // Tao url chinh
        $base_url = site_url('message');

        // Tao query chia trang
        $pages_query = array();
        foreach ($filter_input as $f => $v) {
            if (!$v) continue;
            $pages_query[$f] = $v;
        }
        $pages_query = http_build_query($pages_query);

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = $base_url . '?' . $pages_query;
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;

        // Luu cac bien gui den view
        $this->data['action'] = current_url();
        $this->data['statuss'] = config("verify", "main");
        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(current_url(), lang('title_message'));
        $breadcrumbs[] = array(current_url(), lang('message_sended'));
        page_info('breadcrumbs', $breadcrumbs);

        $this->data['breadcrumbs'] = $breadcrumbs;

        // Gan thong tin page
        page_info('title', lang('message_sended'));

        // Hien thi view
        if ($this->input->is_ajax_request() && $this->input->post('user_look')) {
            view('site/message/user_look/index', $this->data);
        } else {
            // Hien thi view
            $this->_display('', 'user');
        }
    }

    /**
     *hop thu nhan
     */
    function inbox()
    {
        //lay thong tin tai khoan
        $user = $this->data['user'];

        $this->load->model('message_model');
        $this->load->helper('message');
        $this->load->helper('form');

        // Lay gia tri cua filter dau vao
        $filter_input = array();
        $filter_fields = array('id', 'title', 'created', 'created_to');
        foreach ($filter_fields as $f) {
            $v = $this->input->get($f);
            $filter_input[$f] = $v;
        }

        $this->data['filter'] = $filter_input;
        // Tao bien filter
        $filter = array();
        $filter['receive'] = $user->id;
        foreach ($filter_input as $f => $v) {
            if (!$v) continue;

            switch ($f) {
                case 'created': {
                    $created_to = $filter_input['created_to'];
                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
                    $v = get_time_between($v);
                    if (!$v) continue;
                    break;
                }
            }
            $filter[$f] = $v;
        }

        // Lay tong so
        $total = $this->message_receive_model->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = min($limit, get_limit_page_last($total, $page_size));
        $limit = max(0, $limit);

        // Lay danh sach
        $input = array();
        $input['limit'] = array($limit, $page_size);
        $list = $this->message_receive_model->filter_get_list($filter, $input);
        foreach ($list as $row) {
            $row = message_add_info($row);
        }

        $this->data['list'] = $list;

        // Tao url chinh
        $base_url = site_url('message/inbox');

        // Tao query chia trang
        $pages_query = array();
        foreach ($filter_input as $f => $v) {
            if (!$v) continue;
            $pages_query[$f] = $v;
        }
        $pages_query = http_build_query($pages_query);

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = $base_url . '?' . $pages_query;
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;

        // Luu cac bien gui den view
        $this->data['action'] = current_url();

        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(current_url(), lang('title_message'));
        $breadcrumbs[] = array(current_url(), lang('message_inbox'));
        page_info('breadcrumbs', $breadcrumbs);

        $this->data['breadcrumbs'] = $breadcrumbs;

        // Gan thong tin page
        page_info('title', lang('message_inbox'));

        // Hien thi view
        if ($this->input->is_ajax_request() && $this->input->post('user_look')) {
            view('site/message/user_look/inbox', $this->data);
        } else {
            // Hien thi view
            $this->_display('', 'user');
        }
    }

    /**
     * Xem tin nhan
     */
    function view()
    {
        $user_login = $this->data['user_login'];

        $id = intval($this->uri->rsegment(3));
        $message_receive = model('message_receive')->get_info($id);

        if (!$message_receive || $this->data['user']->id != $message_receive->receive_id) {
            exit();
        }
        //cap nhat da xem
        $data = array(
            'readed' => now(),
            'user_execute' => $user_login->id,
        );
        model('message_receive')->update($id, $data);

        $message_receive = message_add_info($message_receive);
        if ($message_receive->user_execute) {
            $user_execute = model('user')->get_info($message_receive->user_execute, 'username');
            $message_receive->user_execute = $user_execute;
        }
        $this->data['message_receive'] = $message_receive;

        view('site/message/view', $this->data);
    }

    /**
     * Báo cáo spam
     */
    function spam()
    {
        $user_login = $this->data['user_login'];

        $id = intval($this->input->post('id'));
        $message_receive = model('message_receive')->get_info($id);

        if (!$message_receive || $this->data['user']->id != $message_receive->receive_id) {
            exit();
        }
        //cap nhat đã báo cáo spam
        $data = array(
            'is_spam' => 1,
            'is_spam_time' => now(),
            'user_execute' => $user_login->id,
        );

        model('message_receive')->update($id, $data);

        //cap nhat tin nhan
        $message = model('message')->get_info($message_receive->message_id);
        if (!$message) return;

        $data = array(
            'is_spam' => 1,
            'total_spam' => $message->total_spam + 1,
        );
        model('message')->update($message->id, $data);
    }

    /**
     * Xem tin nhan
     */
    function view_send()
    {
        $id = intval($this->uri->rsegment(3));
        $message = model('message')->get_info($id);
        if (!$message || $this->data['user']->id != $message->user_id) {
            exit();
        }

        $message = message_add_info($message);
        $this->data['message'] = $message;

        //lấy tất cả người nhận
        $this->load->model('message_receive_model');
        $_input = array(
            'where' => array('message_id' => $message->id)
        );
        $receives = $this->message_receive_model->get_list_receive($_input);
        $this->data['receives'] = $receives;

        if($this->input->axjax_request)
        view('site/message/view_send', $this->data);
        else
        $this->_display();
    }

    /**
     * gui tin nhan
     */
    function send()
    {
        $user = $this->data['user'];
        // tat chuc nang thanh vien gui thu cho nhau
        if (!user_is_root($user)) //if(1)
        {
            redirect('message/send_admin');
            return;
        }

        $user_login = $this->data['user_login'];

        $level_min_alldownline = setting_get('config-level_min_alldownline');
        $this->data['level_min_alldownline'] = $level_min_alldownline;

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('tran');

        $this->data['captcha'] = site_url('captcha/four');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('content', 'title', 'pin');
            if ($this->input->post('send_admin') || $this->input->post('alldownline')) {
                if ($this->input->post('username')) {
                    array_push($params, 'username');
                }
            } else {
                array_push($params, 'username');
            }

            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                $data = array();
                $data['title'] = $this->input->post('title');
                $data['content'] = $this->input->post('content');
                $data['created'] = now();
                $data['user_id'] = $user->id;
                $data['user_execute'] = $user_login->id;

                //them bang nguoi nhan tin
                $usernames = isset($this->data['usernames']) ? $this->data['usernames'] : array();
                //kiem tra xem co gui trong admin khong
                if ($this->input->post('send_admin')) {
                    array_push($usernames, 0);

                    $data['send_admin'] = 1;
                    //lay cac tai khoan ma duoc phep nhan tin nhan khi gui len cho admin
                    $users = model('user')->get_list(array('select' => 'id', 'where' => array('message_receive_admin' => 1, 'id !=' => $user->id)));
                    $users_id = array();
                    if (!empty($users)) {
                        foreach ($users as $row) {
                            $users_id[] = $row->id;
                        }
                    }
                    $usernames = array_merge($usernames, $users_id);
                }

                $message_id = 0;
                $this->message_model->create($data, $message_id);

                //kiem tra xem gui cho downline tree hay khong
                if ($user->level >= $level_min_alldownline && $this->input->post('alldownline')) {
                    //lấy tất cả downline tree của thành viên
                    $select = 'id, parent_id, name, username, node_level, node_left, node_right, level';
                    $downline_trees = lib('nested_set')->getSubTree($user, $select, false);

                    $downline_trees_id = array();
                    if (is_array($downline_trees['items'])) {
                        $downline_trees_id = array_keys($downline_trees['items']);
                    }
                    $usernames = array_merge($usernames, $downline_trees_id);
                }

                $usernames = array_unique($usernames);

                foreach ($usernames as $i => $receive_id) {
                    $data = array();
                    $data['message_id'] = $message_id;
                    $data['receive_id'] = $receive_id;
                    model('message_receive')->create($data);
                }

                // Khai bao du lieu tra ve
                $url = site_url('message');
                $result['complete'] = TRUE;
                $result['location'] = $url;
                set_message(lang('notice_message_send_success'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }

        // Luu bien gui den view
        $this->data['action'] = current_url();
        $message_max = intval(model('setting')->get('config-message_max'));
        $this->data['message_max'] = $message_max;

        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(current_url(), lang('message_send'));
        page_info('breadcrumbs', $breadcrumbs);
        $this->data['breadcrumbs'] = $breadcrumbs;

        // Gan thong tin page
        page_info('title', lang('message_send'));

        // Hien thi view
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('user_look')) {
                view('site/message/user_look/send', $this->data);
            } else {
                view('site/message/send_ajax', $this->data);
            }

        } else {

            // Hien thi view
            $this->_display('', 'user');
        }
    }

    /**
     * gui tin nhan cho admin
     */
    function send_admin()
    {
        $user = $this->data['user'];
        $user_login = $this->data['user_login'];

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('tran');

        $this->data['captcha'] = site_url('captcha/four');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('content', 'title', 'pin');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                $data = array();
                $data['title'] = $this->input->post('title');
                $data['content'] = $this->input->post('content');
                $data['created'] = now();
                $data['user_id'] = $user->id;
                $data['user_execute'] = $user_login->id;
                $data['send_admin'] = 1;
                $message_id = 0;
                $this->message_model->create($data, $message_id);
                $data = array();
                $data['message_id'] = $message_id;
                $data['receive_id'] = user_get_id_root();
                model('message_receive')->create($data);
                // Khai bao du lieu tra ve
                $url = site_url('message');
                $result['complete'] = TRUE;
                $result['location'] = $url;
                set_message(lang('notice_message_send_success'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }

        // Luu bien gui den view
        $this->data['action'] = current_url();
        $message_max = intval(model('setting')->get('config-message_max'));
        $this->data['message_max'] = $message_max;

        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(current_url(), lang('message_send'));
        page_info('breadcrumbs', $breadcrumbs);
        $this->data['breadcrumbs'] = $breadcrumbs;

        // Gan thong tin page
        page_info('title', lang('message_send'));


        // Hien thi view
        $this->_display('', 'user');

    }

}