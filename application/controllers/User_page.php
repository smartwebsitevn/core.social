<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_page extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('site/user');
        $this->load->language('site/user_list');


    }

    protected function _get_mod()
    {
        return 'user';
    }

    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('view', 'view_profile', 'view_attach', 'invite', 'report', /*'favorite', 'favorite_del', */
            'subscribe', 'subscribe_del'));
    }

    /*
     * ------------------------------------------------------
     *  Rules params
     * ------------------------------------------------------
     */
    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {

        // Main
        $rules = array();
        $rules['recruit_id'] = array('recruit_id', 'callback__check_recruit');
        $rules['name'] = array('name', 'required|trim|xss_clean');
        $rules['email'] = array('email', 'required|trim|xss_clean|valid_email');
        $rules['phone'] = array('phone', 'required|trim|xss_clean|callback__check_phone');
        $rules['address'] = array('address', 'required|trim|xss_clean');
        $rules['content'] = array('content', 'required|trim|min_length[10]|max_length[255]|xss_clean');
        $rules['security_code'] = array('security_code', 'required|captcha[four]');
        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem recruit
     */
    public function _check_recruit()
    {
        $v = $this->_get_recruit();
        if (!$v) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_please_choice_recruit_for_invite'));
            return FALSE;
        }
        return TRUE;
    }

    public function _get_recruit()
    {
        $recruit_ids = $this->input->post('recruit_id');
        $user_id = $this->data['user']->id;
        $list = array();
        if ($recruit_ids) {
            foreach ($recruit_ids as $id) {
                // kiem tra xem ti nay co phai cua cong ty dang
                if (model('recruit')->check_exits(array('user_id' => $user_id, 'id' => $id)))
                    $list[] = $id;
            }
        }
        return $list;
    }

    /**
     * Kiem tra phone
     */
    public function _check_phone($value)
    {
        $phone = handle_phone($value);

        if (!valid_phone($phone)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        return TRUE;
    }

    /*
       * ------------------------------------------------------
       *  Action handle
       * ------------------------------------------------------
       */

    protected function _action($action)
    {
        /*$dont_check_login = array('report');
        if (!in_array($action, $dont_check_login)) {

            if (!user_is_login()) {
                // $this->_response(array('msg_modal' => lang('notice_please_login_to_use_function')));
                // return;
                $result["modal_box"] = "modal-login-require";
                $this->_response($result);
            }
        }*/
        // Lay input
        $id = $this->uri->rsegment(3);
        // Xu ly id
        $id = (!is_numeric($id)) ? 0 : $id;

        // Kiem tra id
        $info = model('user')->get_info($id);
        if (!$info) return;
        $this->data['user'] = $info;
        $this->data['user_current'] = user_get_account_info();
        // kiem tra che do voi cac action
        if (in_array($action, array('view_profile', 'invite',/* 'favorite', 'favorite_del',*/
            'subscribe', 'subscribe_del'))) {
            /* neu mo ra thi cho xem thoa mai
            *  if ($action == 'view_profile' ) {//&& $this->data['user']->id == $id
                // Chuyen den ham duoc yeu cau
                $this->{'_' . $action}();
                return;
            }*/

            // kiem tra trang thai dang nhap hay chua, hoac khong cho phep hanh dong voi chinh minh
            if (!$info) {
                $result['msg_modal'] = lang('notice_please_login_to_use_function');
                $this->_response($result);
            }

            /*if ( $info->id == $id) {
                $result['msg_modal'] = lang('notice_dont_do_this_action');
                $this->_response($result);
            }*/

            /*switch ($action) {
                case 'favorite':
                    $key = 'save_brief';
                    break;
                case 'invite':
                    $key = 'inviting_user';
                    break;
                case 'view_profile':
                    $key = 'view_detail_brief';
                    break;
            }
            // kiem tra hanh dong trong goi
            $data_check = array(
                'type' => 'company', 'key' => $key, 'user' => $this->data['user'], 'return' => 1
            );
            $check = mod('user')->checkPackages($data_check);
            if (!$check) {
                $result['msg_modal'] = lang('notice_please_upgrade_account_to_use_function');
                $this->_response($result);
            }*/
        }
        //======================================


        // Kiem tra co the thuc hien hanh dong nay khong
        //if ( !  $this->_mod()->can_do($info, $action)) return;

        // Chuyen den ham duoc yeu cau
        $this->{'_' . $action}();
    }


    /**
     * Moi du tuyen
     */
    public function _invite()
    {
        // kiem tra co cho phep nop don
        /*if (!$this->_mod()->setting('invite_allow')) {
            $this->_redirect();
        }*/
        $form = array();
        $form['validation']['params'] = array( /*'name','email', 'phone',*/
            'content', 'recruit_id');
        $form['submit'] = function ($params) {
            //lib('captcha')->del('four');
            $input = array();
            foreach (array('name', 'email', 'phone', 'content') as $f) {
                $v = $this->input->post($f);
                if (!$v) $v = '';
                $input[$f] = $v;
            }
            $recruit_ids = $this->_get_recruit();

            if ($recruit_ids) {
                $user = $this->data['user'];
                $user_id = $this->data['user']->id;
                $company = mod('company')->get_info($user_id);
                $user = mod('user')->add_info($user, 1);
                $user_user = mod('user')->get_info($user->user_id);
                $re_title = [];
                foreach ($recruit_ids as $id) {
                    $recruit = mod('recruit')->get_info($id);
                    $com_contact = json_decode($recruit->info_contact);
                    //$re_title[] =t('html')->a($re->_url_view,$re->title,array('target'=>'_blank'));
                    model('recruit_profile')->company_set($company->user_id, $user->user_id, $id, 'invite', $input);
                    $email_params = array(
                        'com_invite_content' => $input['content'],

                        'com_name' => $company->name,
                        'com_avatar' => $company->avatar->url_thumb,
                        'com_contact_name' => $input['name'],
                        'com_contact_email' => $input['email'],
                        'com_contact_phone' => $input['phone'],
                        //'com_contact_address' => $com_contact->contact_address,
                        're_name' => $recruit->title,
                        'can_name' => $user->name,
                        'can_title' => $user->title,
                        'can_company' => $user->company_name,
                        'can_email' => $user->email,
                        'can_phone' => $user->phone,
                        'can_avatar' => $user->avatar->url_thumb,
                        'url_re_view' => $recruit->_url_view, // xem chi tiet tin tuyen dung
                        'url_com_view' => $company->_url_view,
                        'url_com_post' => site_url('company_post'),// cac tin dang tuyen
                        //'url_com_apply' => site_url('company_recruit/apply') . '?recruit_id=' . $recruit->id,
                        'url_can_view' => $user->_url_view,
                        'url_can_attach' => $user->attach->url,
                        'url_can_re_apply' => site_url('user_recruit/apply'),// den muc quan ly viec da ung tuyen
                        'user_email' => $user->email,
                        'user_pass' => mod('user')->encode_password($user_user->password, $user_user->id, 'decode'),

                    );
                    //pr($company);
                    mod('email')->send('company_notice_invite', $user->email, $email_params);
                }

                // tru trong goi
                mod('user')->setPackages(mod('user')->config('user_type_company'), array('inviting_user' => -1), $this->data['user']);

                set_message(lang('notice_invite_user_success'));


                return $user->_url_view;
            }


        };
        /*$form['form'] = function () {
            $this->_apply_view();
        };*/
        $this->_form($form);
    }

    /**
     * Bao cao vi pham
     */
    function _report()
    {
        $info = $this->data['user'];
        $info = mod('user')->url($info);

        $form = array();
        $form['validation']['params'] = array('email', 'phone', 'content',);
        $form['submit'] = function () use ($info) {
            //lib('captcha')->del('four');
            $input = array();
            $fields = array('email', 'phone', 'content',);
            foreach ($fields as $f) {
                $v = $this->input->post($f);
                if (!$v) $v = '';
                $input[$f] = $v;
            }
            $input['user_id'] = $info->id;
            $input['ip'] = $this->input->ip_address();;
            $input['created'] = now();
            model('report_user')->create($input);
            set_message(lang('notice_register_success'));
            return $info->_url_view;
        };
        /*$form['form'] = function () {
            $this->_apply_view();
        };*/
        $this->_form($form);
    }


    function _subscribe()
    {
        $user_current = $this->data['user_current'];
        $user_current = mod('user')->add_info_url($user_current);
        $user_current_id = $user_current->id;
        $user_id = $this->data['user']->id;

        if ($user_current_id == $user_id) {
            $this->_response(array('msg_toast' => 'Error'));
        }
        //kiem tra da luu hay chua
        $data = array();
        $data ['action'] = 'subscribe';
        $data ['table'] = 'user';
        $data ['table_id'] = $user_id;

        $subscribed = mod('user_storage')->get($user_current_id, $data);
        if ($subscribed) {
            $this->_response(array('msg_toast' => lang('notice_user_subscribe_success')));
        }
        //them vao table user_storage
        $data ['created'] = now();
        mod('user_storage')->set($user_current_id, $data);
        model('user')->update_stats(['id' => $user_current_id], ['follow_total' => 1]);
        model('user')->update_stats(['id' => $user_id], ['follow_by_total' => 1]);

        // gui thong bao
        mod('user_notice')->send($user_id, '<b>' . $user_current->name . '</b> đã theo dõi bạn', ['url' => $user_current->_url_view]);

        $this->_response(array('msg_toast' => lang('notice_user_subscribe_success')));
    }

    function _subscribe_del()
    {
        //kiem tra da luu hay chua
        $user_current_id = $this->data['user_current']->id;
        $user_id = $this->data['user']->id;
        if ($user_current_id == $user_id) {
            $this->_response(array('msg_toast' => lang('notice_dont_do_this_action')));
        }

        $data = array();
        $data ['action'] = 'subscribe';
        $data ['table'] = 'user';
        $data ['table_id'] = $user_id;
        $subscribed = mod('user_storage')->get($user_current_id, $data);
        if (!$subscribed) {
            $this->_response(array('msg_toast' => 'Error'));
        }
        mod('user_storage')->del($user_current_id, $data);
        model('user')->update_stats(['id' => $user_current_id], ['follow_total' => -1]);
        model('user')->update_stats(['id' => $user_id], ['follow_by_total' => -1]);

        $this->_response(array('msg_toast' => lang('notice_user_subscribe_del_succcess')));
    }

    // xem thong tin ca nhan
    function _view_profile()
    {
        $user = $this->data['user'];
        $user = mod('user')->add_info($user);
        $result['msg_modal'] = t('view')->load('tpl::_widget/user/info/contact', ['info' => $user], true);//// widget('user')->info_private($user, array(), 'info_private_ajax', array('return' => 1));
        $result['msg_modal_title'] = 'Thông Tin Thành Viên';
        $this->_response($result);


    }

// xem thong tin ca nhan
    function _view_attach()
    {
        $user = $this->data['user'];
        $user = mod('user')->add_info($user);

        // tam tat chuc nang nay
        //kiem tra da luu hay chua
        /* $viewed = mod('company')->cancidate_get($user->id, $cancidate->user_id, 'view_profile');
         if (!$viewed) {
             mod('company')->cancidate_set($user->id, $cancidate->user_id, 'view_profile');
             // tru trong goi
             mod('user')->setPackages(mod('user')->config('user_type_company'), array('view_detail_brief' => -1), $user);
         }*/


        if ($user->attach) {
            // file_download($user->attach_id);
            $this->_response(array('location' => $user->attach->url));
            return;
        }
        return;


    }

    public function index()
    {
        if (!user_is_login()) {
            redirect();
        }
        $user = user_get_account_info();
        $this->data['user'] = $user;
        $page = $this->input->get('page');
        if (!in_array($page, ['posts', 'posts_draft', 'posts_save', 'follow', 'follow_by', 'notice', 'message'])) {
            $page = 'posts';
        }
        $this->{'_my_' . $page}();
        $this->data['page'] = $page;
        $this->data['info'] = mod('user')->add_info($user);


        // Khai bao cac bien cua widget upload
        $widget_upload = array();
        $widget_upload['mod'] = 'single';
        $widget_upload['file_type'] = 'image';
        $widget_upload['status'] = config('file_public', 'main');
        $widget_upload['resize'] = TRUE;
        $widget_upload['thumb'] = TRUE;

        $widget_upload['table'] = $this->_get_mod();
        $widget_upload['table_id'] = $user->id;
        //- up anh banner
        $widget_upload['url_get'] = site_url('user_account/edit') . '?_act=load_file&field=banner';
        //$widget_upload['url_update'] = ($user->id > 0) ? $this->_url('edit') . '?act=update_image&field=banner' : null;
        $widget_upload['table_field'] = 'banner';
        $this->data['upload_banner'] = $widget_upload;

        $this->_display('my_' . $page);
    }

    public function _my_posts()
    {
        $user = $this->data['user'];
        // Filter set
        $filter = array();
        $filter['user_id'] = $user->id;
        $created = $this->input->get('created');

        if ($created) {
            $created = explode('|', escape($created));
            if (count($created) == 2) {
                $filter['created'] = $created[0];
                $filter['created_to'] = $created[1];

            }
        }
        $this->data['temp'] = 'owner_default';
        $this->_post_create_list([], $filter);
    }

    public function _my_posts_draft()
    {

        $user = $this->data['user'];
        // Filter set
        $filter = array();
        $filter['user_id'] = $user->id;
        $filter['is_draft'] = 1;
        $this->data['temp'] = 'owner_default';

        $this->_post_create_list([], $filter);
    }

    public function _my_posts_save()
    {
        $user = $this->data['user'];
        // Filter set
        $filter = array();
        // $filter['user_id'] = $user->id;
        $created = $this->input->get('created');

        if ($created) {
            $created = explode('|', escape($created));
            if (count($created) == 2) {
                $input['where']['f.created >='] = $created[0];
                $input['where']['f.created <='] = $created[1];

            }
        }

        $input['where']['f.user_id'] = $user->id;
        $input['join'] = array(array('product_to_favorite f', 'f.product_id = product.id'));
        $this->data['temp'] = 'owner_save';
        $this->_post_create_list($input, $filter);
    }


    public function _my_follow()
    {
        $user = $this->data['user'];
        $input['where']['us.action'] = 'subscribe';
        $input['where']['us.table'] = 'user';
        $input['where']['us.user_id'] = $user->id;
        $input['where']['us.deleted'] = 0;

        $input['join'] = array(array('user_storage us', 'us.table_id = user.id'));
        $filter = array();
        $this->_user_create_list($input, $filter);
    }

    public function _my_follow_by()
    {
        $user = $this->data['user'];
        $input['where']['us.action'] = 'subscribe';
        $input['where']['us.table'] = 'user';
        $input['where']['us.table_id'] = $user->id;
        $input['where']['us.deleted'] = 0;

        $input['join'] = array(array('user_storage us', 'us.user_id = user.id'));
        $filter = array();
        $this->_user_create_list($input, $filter);

    }
    public function _my_notice()
    {
        $user = $this->data['user'];
        // Filter set
        $filter = array();
        $filter['user_id'] = $user->id;
        $this->data['temp'] = 'owner_default';
        $this->_notice_create_list([], $filter);
    }
    public function _my_message()
    {
        $user = $this->data['user'];
        // Filter set
        $filter = array();
        $filter['user_id'] = $user->id;
        $this->data['temp'] = 'owner_default';
        $this->_message_create_list([], $filter);
    }

    //==============================================================================
    //==============================================================================
    //==============================================================================

    public function _view()
    {
        $page = $this->input->get('page');
        if (!in_array($page, ['follow', 'follow_by', 'posts','info'])) {
            $page = 'posts';
        }
        $this->{'_view_' . $page}();

        $this->data['page'] = $page;
        $this->data['info'] = mod('user')->add_info($this->data['user']);
        //$this->data['user_current'] = mod('user')->add_info( $this->data['user_current']);
        $this->_display('view_' . $page);
    }

    public function _view_posts()
    {
        $user = $this->data['user'];
        // Filter set
        $filter = array();
        $filter['show'] = 1;
        $filter['user_id'] = $user->id;

        $created = $this->input->get('created');

        if ($created) {
            $created = explode('|', escape($created));
            if (count($created) == 2) {
                $filter['created'] = $created[0];
                $filter['created_to'] = $created[1];

            }
        }
        $this->_post_create_list([], $filter);

    }


    public function _view_follow()
    {
        $user = $this->data['user'];
        $input['where']['us.action'] = 'subscribe';
        $input['where']['us.table'] = 'user';
        $input['where']['us.user_id'] = $user->id;
        $input['where']['us.deleted'] = 0;

        $input['join'] = array(array('user_storage us', 'us.table_id = user.id'));
        $filter = array();
        $this->_user_create_list($input, $filter);
    }

    public function _view_follow_by()
    {
        $user = $this->data['user'];
        $input['where']['us.action'] = 'subscribe';
        $input['where']['us.table'] = 'user';
        $input['where']['us.table_id'] = $user->id;
        $input['where']['us.deleted'] = 0;
        $input['join'] = array(array('user_storage us', 'us.user_id = user.id'));
        $filter = array();
        $this->_user_create_list($input, $filter);

    }

    public function _view_info()
    {
        return;
    }

    //====================== Tao danh sach hien thi ===========================
    private function _post_create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('product')->fields_filter);
        $mod_filter = mod('product')->create_filter($filter_fields, $filter_input);
        $filter = array_merge($filter, $mod_filter);
        $filter['types'] = $this->input->get('types');
        $filter_input['types'] = $filter['types'];
        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if (isset($filter['name']) && $filter['name']) {
            unset($filter['name']);
            $filter['%name'] = $filter_fields['name'] = trim($key);
        }
        if ($point=$this->input->get('point')) {
            $filter['point_total_gte'] =$point;
        }
        $filter['types'] = $this->input->get('types');
       // pr($filter);


        // lay thong tin cua cac khoang tim kiem
        foreach (array('price',) as $range) {
            if (isset($filter[$range])) {
                if (is_array($filter[$range])) {
                    foreach ($filter[$range] as $key => $row) {
                        $filter[$range . '_range'][$key] = model('range')->get($row, $range);
                    }
                } else {
                    $filter[$range . '_range'] = model('range')->get($filter[$range], $range);
                }
                unset($filter[$range]);
            }
        }

        //pr($filter);
        //pr($input);
        // Gan filter
        //$filter['show'] = 1;
        $input['select'] = 'product.*';
        //== Lay tong so
        if (!isset($input['limit'])) {
            $total = model('product')->filter_get_total($filter, $input);
            $page_size = 17;// config('list_limit', 'main');

            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }
        //== Sort Order
        $sort_orders = array(
            'id|desc',
            'is_feature|desc',
            'point_total|desc',
            //'price|asc',
            //'price|desc',
            // 'view_total|desc',
            /*'count_buy|desc',
            'new|desc',

            'rate|desc',
            'name|asc',*/
        );
        $order = $this->input->get("order", true);
        if ($order && in_array($order, $sort_orders)) {
            $orderex = explode('|', $order);
        } else {
            $orderex = explode('|', $sort_orders[0]);
        }
        if (!isset($input['order'])) {
            $input['order'] = array('product.' . $orderex[0], $orderex[1]);
            $filter_input['order']=$order;
        }


        $list = model('product')->filter_get_list($filter, $input);
     // pr($filter,0);        pr_db($list);
        foreach ($list as $row) {
            $row = mod('product')->add_info($row, 1);
        }
        // Tao chia trang
        $pages_config = array();
        if (isset($total)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
            // pr( $pages_config['base_url'] );
            // $pages_config['base_url'] = current_url(1);
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
        }

        $this->data['pages_config'] = $pages_config;
        $this->data['total'] = $total;
        $this->data['list'] = $list;
        $this->data['filter'] = $filter_input;
        $this->data['sort_orders'] = $sort_orders;
        $this->data['sort_order'] = $order;
        $this->data['action'] = current_url();

        //===== Ajax list====
        $this->_post_create_list_ajax();

        // luu lai thong so loc va ket qua
        mod('product')->sess_data_set('list_filter', $filter);// phuc vu loc du lieu
        mod('product')->sess_data_set('list_filter_input', $filter_input);// phuc vu hien thi
        mod('product')->sess_data_set('list_sort_orders', $sort_orders);// phuc vu hien thi
        mod('product')->sess_data_set('list_sort_order', $order);// phuc vu hien thi
        mod('product')->sess_data_set('list_total_rows', $total);// phuc vu hien thi

    }

    /*
 * ------------------------------------------------------
 *  Ajax handle
 * ------------------------------------------------------
 */
    private function _post_create_list_ajax()
    {
        if ($this->input->is_ajax_request()) {

            $style_display = isset($this->data['temp']) ? $this->data['temp'] : 'default';

            //$temp = $this->input->get('temp');
            $temp = $this->input->get('layout');
            if (!in_array($temp, ['block', 'grid'])) {
                $temp = $style_display;
            }

            $temp = $temp ? $temp : $style_display;
            //$temp ='default';
            $load_more = $this->input->get("load_more", false);

            $response = [
                'status' => true,
                'total' => number_format($this->data['total']),
                'content' => widget('product')->display_list(
                    $this->data['list'], $temp,
                    array(
                        'return_data' => 1,
                        'pages_config' => $this->data['pages_config'],
                        'load_more' => $load_more)
                ),

            ];
            //= su ly hien thi bo loc dong
            if (isset($this->data['filter']['type_cat_id'])) {
                $type_cat_id = $this->data['filter']['type_cat_id'];
                $response['filter'] = widget('type')->filter_types(
                    $type_cat_id, $this->data['filter'], '', ['return' => 1]
                );
            } else {
                $response['filter'] = '';
            }

            echo json_encode($response);
            exit;
        }
    }

    private function _post_create_list_ajax_()
    {
        if ($this->input->is_ajax_request()) {


            //= su ly hien thi danh sach theo danh muc
            $category = $style_display = '';
            if (isset($this->data['category'])) {
                $category = $this->data['category'];
            } else {
                $cat_id = $this->input->get('cat_id');
                if ($cat_id)
                    $category = model("product_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            //$temp = $this->input->get('temp');
            $temp = $this->input->get('layout');
            if (!in_array($temp, ['block', 'grid'])) {
                $temp = $style_display;
            }

            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);

            $response = [
                'status' => true,
                'total' => number_format($this->data['total']),
                'content' => widget('product')->display_list(
                    $this->data['list'], $temp,
                    array(
                        'return_data' => 1,
                        'pages_config' => $this->data['pages_config'],
                        'load_more' => $load_more)
                ),

            ];
            //= su ly hien thi bo loc dong
            if (isset($this->data['filter']['type_cat_id'])) {
                $type_cat_id = $this->data['filter']['type_cat_id'];
                $response['filter'] = widget('type')->filter_types(
                    $type_cat_id, $this->data['filter'], '', ['return' => 1]
                );
            }

            echo json_encode($response);
            exit;
        }
    }

    private function _user_create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('user')->fields_filter);
        $mod_filter = mod('user')->create_filter($filter_fields, $filter_input);
        $filter = array_merge($filter, $mod_filter);
        // pr($filter_input);

        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if (isset($filter['name']) && $filter['name']) {
            $filter['%name'] = trim($key);
            unset($filter['name']);

        }
        if ($point=$this->input->get('point')) {
            $filter['point_total_gte'] =$point;
        }
        // lay thong tin cua cac khoang tim kiem
        foreach (array('price',) as $range) {
            if (isset($filter[$range])) {
                if (is_array($filter[$range])) {
                    foreach ($filter[$range] as $key => $row) {
                        $filter[$range . '_range'][$key] = model('range')->get($row, $range);
                    }
                } else {
                    $filter[$range . '_range'] = model('range')->get($filter[$range], $range);
                }
                unset($filter[$range]);
            }
        }
        //pr($filter);
        //pr($input);
        // Gan filter
        $filter['show'] = 1;
        $input['select'] = 'user.*';

        //== Lay tong so
        if (!isset($input['limit'])) {
            $total = model('user')->filter_get_total($filter, $input);
            $page_size = config('list_limit', 'main');

            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }
        //== Sort Order
        $sort_orders = array(
            'id|desc',
            'point_total|desc',
            'post_total|desc',
            'count_view|desc',

            /*'count_buy|desc',
            'new|desc',
            'feature|desc',

            'name|asc',*/
        );
        $order = $this->input->get("order", true);
        if ($order && in_array($order, $sort_orders)) {
            $orderex = explode('|', $order);
        } else {
            $orderex = explode('|', $sort_orders[0]);
        }
        if (!isset($input['order'])) {
            $input['order'] = array($orderex[0], $orderex[1]);
            $filter_input['order']=$order;
        }
        $list = model('user')->filter_get_list($filter, $input);
        // pr($filter,0);         pr_db($list);
        foreach ($list as $row) {
            $row = mod('user')->add_info($row);
        }
        // Tao chia trang
        $pages_config = array();
        if (isset($total)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
            // pr( $pages_config['base_url'] );
            // $pages_config['base_url'] = current_url(1);
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
        }

        $this->data['pages_config'] = $pages_config;
        $this->data['total'] = $total;
        $this->data['list'] = $list;
        $this->data['filter'] = $filter_input;
        $this->data['sort_orders'] = $sort_orders;
        $this->data['sort_order'] = $order;
        $this->data['action'] = current_url();

        //===== Ajax list====
        $this->_user_create_list_ajax();

        // luu lai thong so loc va ket qua
        mod('user')->sess_data_set('list_filter', $filter);// phuc vu loc du lieu
        mod('user')->sess_data_set('list_filter_input', $filter_input);// phuc vu hien thi
        mod('user')->sess_data_set('list_sort_orders', $sort_orders);// phuc vu hien thi
        mod('user')->sess_data_set('list_sort_order', $order);// phuc vu hien thi
        mod('user')->sess_data_set('list_total_rows', $total);// phuc vu hien thi

    }

    /*
 * ------------------------------------------------------
 *  Ajax handle
 * ------------------------------------------------------
 */
    private function _user_create_list_ajax()
    {
        if ($this->input->is_ajax_request()) {
            //= su ly hien thi danh sach theo danh muc
            $style_display = isset($this->data['temp']) ? $this->data['temp'] : 'default';

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);
            echo json_encode(
                array(
                    'status' => true,
                    'content' => widget('user')->display_list(
                        $this->data['list'], $temp,
                        array(
                            'return_data' => 1,
                            'pages_config' => $this->data['pages_config'],
                            'load_more' => $load_more)
                    ),
                    'total' => number_format($this->data['total']))
            );
            exit;
        }
    }

    private function _notice_create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('user_notice')->fields_filter);
        $mod_filter = mod('user_notice')->create_filter($filter_fields, $filter_input);
        $filter = array_merge( $filter,$mod_filter);
        // pr($filter_input);

        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if ($key) {
            $filter['%name'] = $filter_fields['name'] = $key;
        }
        // Gan filter
        $filter['show'] = 1;

        //== Lay tong so
        if (!isset($input['limit'])) {
            $total = model('user_notice')->filter_get_total($filter, $input);
            // pr_db();
            $page_size = config('list_limit', 'main');

            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }


        $list = model('user_notice')->filter_get_list($filter, $input);
        //pr_db($list);
        foreach ($list as $row) {
            $row = mod('user_notice')->add_info($row);
            if(!$row->readed)
                $row = model('user_notice')->update_field($row->id,'readed',1);

        }

        // Tao chia trang
        $pages_config = array();
        if (isset($total)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
        }

        $this->data['pages_config'] = $pages_config;
        $this->data['total'] = $total;
        $this->data['page'] = 'notice';
        $this->data['list'] = $list;
        $this->data['filter'] = $filter_input;
        $this->data['action'] = current_url();

        //===== Ajax list====
        $this->_notice_create_list_ajax();


    }

    private function _notice_create_list_ajax()
    {
        if ($this->input->is_ajax_request()) {
            //= su ly hien thi danh sach theo danh muc
            $category = $style_display = '';
            if (isset($this->data['category'])) {
                $category = $this->data['category'];
            } else {
                $cat_id = $this->input->get('cat_id');
                if ($cat_id)
                    $category = model("user_notice_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);
            echo json_encode(
                array(
                    'status' => true,
                    'content' => widget('user_notice')->display_list(
                        $this->data['list'], $temp,
                        array(
                            'return_data' => 1,
                            'pages_config' => $this->data['pages_config'],
                            'load_more' => $load_more)
                    ),
                    'total' => number_format($this->data['total']))
            );
            exit;
        }
    }
}

