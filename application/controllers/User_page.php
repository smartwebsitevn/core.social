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

        if (!user_is_login()) {
            redirect_login_return();
        }
        $user = user_get_account_info();
        $this->data['user'] = $user;

    }

    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('view_profile', 'invite', 'report', 'favorite', 'unfavorite', 'subscribe'));
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
        // Lay input
        $id = $this->uri->rsegment(3);
        // Xu ly id
        $id = (!is_numeric($id)) ? 0 : $id;

        // Kiem tra id
        $info = model('user')->get_info($id);
        if (!$info) return;
        $this->data['user'] = $info;

        // kiem tra che do voi cac action
        if (in_array($action, array('view_profile', 'invite', 'favorite', 'unfavorite'))) {
           /* neu mo ra thi cho xem thoa mai
            *  if ($action == 'view_profile' ) {//&& $this->data['user']->id == $id
                // Chuyen den ham duoc yeu cau
                $this->{'_' . $action}();
                return;
            }*/
            // kiem tra trang thai dang nhap hay chua, hoac khong cho phep hanh dong voi chinh minh
            if (!$this->data['user'] || $this->data['user']->id == $id) {
                $result['msg_modal'] = lang('notice_please_login_to_use_function');
                $this->_response($result);
            }
            // kiem tra che do
            if ($this->data['user_mode'] != mod('user')->config('user_type_company')) {
                $result['msg_modal'] = lang('notice_function_only_for_company');
                $this->_response($result);
            }


            switch ($action) {
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
            }
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
    public  function _invite()
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
                $re_title=[];
                foreach ($recruit_ids as $id) {
                    $recruit=mod('recruit')->get_info($id);
                    $com_contact = json_decode($recruit->info_contact);
                    //$re_title[] =t('html')->a($re->_url_view,$re->title,array('target'=>'_blank'));
                    model('recruit_profile')->company_set($company->user_id, $user->user_id, $id, 'invite', $input);
                    $email_params =array(
                        'com_invite_content' =>  $input['content'],

                        'com_name' => $company->name,
                        'com_avatar' => $company->avatar->url_thumb,
                        'com_contact_name' =>  $input['name'],
                        'com_contact_email' => $input['email'],
                        'com_contact_phone' =>  $input['phone'],
                        //'com_contact_address' => $com_contact->contact_address,
                        're_name' => $recruit->title,
                        'can_name' => $user->name,
                        'can_title' => $user->title,
                        'can_company' => $user->company_name,
                        'can_email' =>  $user->email,
                        'can_phone' =>  $user->phone,
                        'can_avatar' => $user->avatar->url_thumb,
                        'url_re_view' => $recruit->_url_view, // xem chi tiet tin tuyen dung
                        'url_com_view' => $company->_url_view,
                        'url_com_post' => site_url('company_post'),// cac tin dang tuyen
                        //'url_com_apply' => site_url('company_recruit/apply') . '?recruit_id=' . $recruit->id,
                        'url_can_view' => $user->_url_view,
                        'url_can_attach' => $user->attach->url,
                        'url_can_re_apply' => site_url('user_recruit/apply') ,// den muc quan ly viec da ung tuyen
                        'user_email' => $user->email,
                        'user_pass' =>mod('user')->encode_password($user_user->password , $user_user->id,'decode'),

                    );
                    //pr($company);
                    mod('email')->send('company_notice_invite', $user->email,$email_params  );
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

    /**
     * Yeu thich
     */
    function _favorite()
    {
        //kiem tra da luu hay chua
        $favorited = mod('company')->user_get($this->data['user']->id, $this->data['user']->user_id, 'favorite');
        if ($favorited) {
            return false;
        }
        mod('company')->user_set($this->data['user']->id, $this->data['user']->user_id, 'favorite');
        // tru trong goi
        mod('user')->setPackages(mod('user')->config('user_type_company'), array('save_brief' => -1), $this->data['user']);

        set_output('json', json_encode(array('complete' => true)));

    }

    function _unfavorite()
    {
        //kiem tra da luu hay chua
        $favorited = mod('company')->user_get($this->data['user']->id, $this->data['user']->user_id, 'favorite');
        if (!$favorited) {
            return false;
        }
        mod('company')->user_del($this->data['user']->id, $this->data['user']->user_id, 'favorite');
        set_output('json', json_encode(array('complete' => true)));

    }

    // xem thong tin ca nhan
    function _view_profile()
    {
        $user = $this->data['user'];
        $user = $this->data['user'];
        $user = mod('user')->add_info($user);
        // pr($user);

       // tam tat chuc nang nay
        //kiem tra da luu hay chua
        $viewed = mod('company')->user_get($user->id, $user->user_id, 'view_profile');
        if (!$viewed) {
            mod('company')->user_set($user->id, $user->user_id, 'view_profile');
            // tru trong goi
            mod('user')->setPackages(mod('user')->config('user_type_company'), array('view_detail_brief' => -1), $user);
        }


        $attach = $this->input->get('attach');
        if ($attach) {
            // $this->load->helper('download');
            // $data = file_get_contents($user->attach->url); // Read the file's contents
            //force_download($user->attach_name, $data);
            $this->_response(array('location' => $user->attach->url));
        }
        // neu da luu roi thi hien thong tin
        $result['msg_modal'] = widget('user')->info_private($user, array(), 'info_private_ajax', array('return' => 1));
        $result['msg_modal_title'] = 'Thông tin cá nhân';
        $this->_response($result);


    }

    public   function view($id)
    {
        // Cap nhat so luot view
       // model('user')->update_stats($id, array('count_view' => 1));
        // Xu ly thong tin
        $user= $this->data['user'];
        $page= $this->input->get('page');
        if(!in_array($page,['follow','follow_me','posts']))
            $page= 'posts';


       /* switch($page){
            case 'follow':     $this->_page_follow(); break;
            case 'follow_by':     $this->_page_follow_by(); break;
            case 'posts':
            default:
                 $this->_page_posts(); break;

        }*/
        $this->data['page'] = $page;
        $this->data['info'] = mod('user')->add_info($user);
        $this->_display($page);

    }
/*
    public  function _page_posts()
    {
        $this->_display('posts');
    }

    public  function _page_follow()
    {
        $this->_display('follow');
    }
    public  function _page_follow_by()
    {
        $this->_display('follow_by');
    }*/
}

