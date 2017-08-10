<?php use App\User\UserFactory;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_account extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('user');
        $this->lang->load('site/user');

        if (!user_is_login()) {
            redirect_login_return();
        }
        $user = user_get_account_info();
        $this->data['user'] = $user;
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
        return call_user_func_array(array($this,  '_action_user'), array($method));
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
        // Lay config
        $length = model('user')->_password_lenght;

        // Main
        $rules = array();
        $rules['email'] = array('email', 'required|trim|xss_clean|valid_email|max_length[30]|callback__check_email');
        $rules['email_valid'] = array('email', 'required|trim|xss_clean|valid_email|max_length[30]|callback__check_email_valid');
        $rules['email_activation'] = array('email', 'required|trim|xss_clean|valid_email|max_length[30]|callback__check_email_activation');
        $rules['email_edit'] = array('email', 'required|trim|xss_clean|valid_email|callback__check_email_edit');
        $rules['username_edit'] = array('username', 'required|trim|xss_clean|callback__check_username_edit');
        $rules['phone_edit'] = array('phone', 'required|trim|xss_clean|callback__check_phone_edit');

        $rules['password'] = array('password', 'required|trim|xss_clean|min_length[' . $length . ']');
        $rules['password_repeat'] = array('password_repeat', 'required|trim|xss_clean|matches[password]');
        $rules['password_old'] = array('password_old', 'required|trim|xss_clean|callback__check_password_old');
        $rules['pin'] = array('pin', 'required|trim|xss_clean|min_length[' . $length . ']|max_length[30]');
        $rules['pin_confirm'] = array('pin_confirm', 'required|trim|xss_clean|matches[pin]');

        $rules['username'] = array('username', 'required|trim|xss_clean|alpha_dash|min_length[5]|max_length[30]|filter_html|callback__check_username');
        $rules['name'] = array('full_name', 'required|trim|min_length[5]|max_length[30]|filter_html|xss_clean');
        $rules['profession'] = array('profession', 'required|trim|min_length[5]|max_length[30]|filter_html|xss_clean');
        $rules['phone'] = array('phone', 'required|trim|xss_clean|callback__check_phone|max_length[15]|xss_clean');
        $rules['address'] = array('address', 'trim|max_length[255]|filter_html|xss_clean');
        $rules['security_code'] = array('security_code', 'required|captcha[four]');
        $rules['rule'] = array('', 'callback__check_rule');


        //info
        $rules ['gender'] = array (lang ( 'gender' ), 'trim|xss_clean|callback__check_gender' );
        $rules ['birthday'] = array (lang ( 'birthday' ), 'trim|xss_clean|callback__check_birthday' );
        $rules ['country'] = array (lang ( 'country' ), 'trim|xss_clean|callback__check_country' );
        $rules ['city'] = array (lang ( 'city' ), 'trim|xss_clean|callback__check_city' );


        // Verify
        $rules['card_no'] = array('card_no', 'required|trim|xss_clean');
        $rules['card_place'] = array('card_place', 'required|trim|xss_clean');
        $rules['card_date'] = array('card_date', 'required|trim|xss_clean');
        $rules['paypal_emails'] = array('paypal_emails', 'required|trim|xss_clean|callback__check_paypal_emails');
        $rules['image_card_front'] = array('image_card_front', 'callback__check_image_card_front');
        $rules['image_card_back'] = array('image_card_front', 'callback__check_image_card_back');
        $rules['image_photo'] = array('image_card_front', 'callback__check_image_photo');


        $rules['affiliate'] 		    = array('affiliate', 'trim|xss_clean|callback__check_affiliate');
        // Upgrade
      /*  $rules['user_upgrade'] = array('user_upgrade', 'trim|xss_clean|callback__check_user_upgrade');

        $rules['parent'] = array('parent', 'trim|xss_clean|callback__check_parent');
        $rules['node_parent'] = array('node_parent', 'required|trim|xss_clean|callback__check_node_parent');
        $rules['country'] = array('country', 'required|trim|xss_clean|callback__check_country');

        $rules['node_parent_add'] = array('node_parent', 'trim|xss_clean|callback__check_node_parent_add');*/



        $this->form_validation->set_rules_params($params, $rules);
    }

    public function _check_name1($value)
    {
        if (preg_match('#(?<=<)\w+(?=[^<]*?>)#', $value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Kiem tra email nay da duoc su dung chua
     */
    public function _check_email($value)
    {
        if (model('user')->has_user($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra email nay da duoc su dung chua
     */
    public function _check_email_edit($value)
    {
        $user = user_get_account_info();

        if (model('user')->has_user($value) && $value != $user->email) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra username nay da duoc su dung chua
     */
    public function _check_username_edit($value)
    {
        $user = user_get_account_info();

        if (model('user')->has_user($value) && $value != $user->username) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra phone nay da duoc su dung chua
     */
    public function _check_phone_edit($value)
    {
        $user = user_get_account_info();

        $phone = handle_phone($value);

        if (!valid_phone($phone)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        if (model('user')->has_user($value) && $value != $user->phone) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra email nay co ton tai hay khong
     */
    public function _check_email_valid($value)
    {
        if (!model('user')->get_id(array('email' => $value))) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra email nay co ton tai va da duoc kich hoat hay chua
     */
    public function _check_email_activation($value)
    {
        // Lay thong tin user
        $user = model('user')->get_info_rule(array('email' => $value), 'activation');

        // Neu user khong ton tai
        if (!$user) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        // Neu user da duoc kich hoat
        if ($user->activation) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_account_already_activation'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra mat khau cu
     */
    public function _check_password_old($value)
    {
        if (!mod('user')->is_password_current($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra username nay da duoc su dung chua
     */
    public function _check_username($value)
    {
        if (model('user')->has_user($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Kiem tra nguoi gioi thieu ton tai hay khong
     */
    function _check_affiliate($value)
    {
        if (!$value) return true;

        $user = model('user')->find_user(strtolower($value));
        if (!$user) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        if ( $user->blocked == config('verify_yes', 'main')  ) {
            $this->form_validation->set_message(__FUNCTION__, lang('user_parent_not_activation'));
            return FALSE;
        }
        if (mod('user')->setting('register_require_activation') && $user->email_activation == config('verify_no', 'main')) {
            $this->form_validation->set_message(__FUNCTION__, lang('user_parent_not_activation'));
            return FALSE;
        }

        return TRUE;
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

        if (model('user')->has_user($phone)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra rule
     */
    public function _check_rule($value)
    {
        if (!$value) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_not_agree_rule'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra ngay thang
     */
    function _check_date($value)
    {
        $value=get_time_from_date($value);
        if (!$value) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra ngay thang
     */
    function _check_birthday($value)
    {
        if(!$value) return true;
        if (!get_time_from_date($value)) {

            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        $value=year_age($value);
        if ($value<6 || $value>150) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_birthday_invalid'));
            return FALSE;
        }

        return TRUE;
    }
    public function _check_gender($value) {
        if (! in_array ( $value, [0,1,2] )) {
            $this->Form_validation->set_message ( __FUNCTION__, lang ( 'notice_value_invalid' ) );
            return FALSE;
        }
        return TRUE;
    }
    public function _check_country($value) {

        if (empty ( $value )) {
            return TRUE;
        }
        $city = model("country")->get_info ( $value );
        if (! $city) {
            $this->Form_validation->set_message ( __FUNCTION__, lang ( 'notice_value_invalid' ) );
            return FALSE;
        }

        return TRUE;
    }
    public function _check_city($value) {

        if (empty ( $value )) {
            return TRUE;
        }
        $city = model("city")->get_info ( $value );
        if (! $city) {
            $this->Form_validation->set_message ( __FUNCTION__, lang ( 'notice_value_invalid' ) );
            return FALSE;
        }

        return TRUE;
    }
    public function _check_distric($value) {

        if (empty ( $value )) {
            return TRUE;
        }
        $city =  model("distric")->get_info ( $value );
        if (! $city) {
            $this->Form_validation->set_message ( __FUNCTION__, lang ( 'notice_value_invalid' ) );
            return FALSE;
        }

        return TRUE;
    }



    /**
     * Kiem tra hinh anh xac thuc
     */
    function _check_image_card_front($value)
    {
        return $this->_check_image_verify('image_card_front');
    }

    function _check_image_card_back($value)
    {
        return $this->_check_image_verify('image_card_back');
    }

    function _check_image_photo($value)
    {
        return $this->_check_image_verify('image_photo');
    }

    function _check_image_verify($param)
    {
        // Tai file thanh phan
        $this->load->helper('file');

        // Tao config upload
        $upload_config = config('upload', 'main');
        $upload_config['upload_path'] = $upload_config['path'] . $upload_config['folder'] . '/public';
        $upload_config['allowed_types'] = $upload_config['img']['allowed_types'];

        // Thuc hien upload file
        $this->load->library('upload', $upload_config);
        if ($this->upload->do_upload($param)) {
            $upload_data = $this->upload->data();

            // Lay thong tin user
            $user = user_get_account_info();
            $user_verify = $this->user_verify_model->get_info($user->id, $param);

            // Xoa file cu neu co
            if (!empty($user_verify->$param)) {
                $file = new stdClass();
                $file->file_name = $user_verify->$param;
                $file->status = config('file_public', 'main');
                file_del($file);
            }

            // Cap nhat vao data
            $data = array();
            $data[$param] = $upload_data['file_name'];
            $this->user_verify_model->set_info($user->id, $data);

            return TRUE;
        } else {
            $upload_error = $this->upload->display_errors();

            $this->form_validation->set_message('_check_' . $param, $upload_error);
            return FALSE;
        }
    }

    /* ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */
    /**
     * Thuc hien hanh dong voi thanh vien
     */
    public function action()
    {
        // Kiem tra ma bao mat
        if (!security_check_query(array('id', 'act', 'exp'), 'user')) {
            set_message('warning', lang('notice_page_not_found'));
            redirect();
        }

        // Lay du lieu dau vao
        $id = $this->input->get('id');
        $act = $this->input->get('act');
        $exp = $this->input->get('exp');

        // Kiem tra exp
        if ($exp < now()) {
            set_message('warning', lang('notice_page_not_found'));
            redirect();
        }

        // Kiem tra act
        $acts = array('forgot', 'activation');
        if (!in_array($act, $acts)) {
            set_message('warning', lang('notice_page_not_found'));
            redirect();
        }

        // Kiem tra user_id
        $id = (!is_numeric($id)) ? 0 : $id;
        $user = model('user')->get_info($id);
        if (!$user) {
            set_message('warning', lang('notice_page_not_found'));
            redirect();
        }

        // Xac thuc action trong data
        if (
            ($user->action != $act) ||
            ($user->action_time + config('url_action_expire', 'main') < now())
        ) {
            set_message('warning', lang('notice_page_not_found'));
            redirect();
        }

        // Reset action trong data
        $data = array();
        $data['action'] = '';
        $data['action_time'] = 0;
        model('user')->update($user->id, $data);

        // Chuyen den ham tuong ung
        $this->{'_' . $act . '_action'}($user);
    }

    // --------------------------------------------------------------------

    /**
     * Tao url action cua thanh vien
     */
    protected function _action_create_url($id, $act)
    {
        $params = array();
        $params['id'] = $id;
        $params['act'] = $act;
        $params['exp'] = now() + config('url_action_expire', 'main');

        $query = security_create_query($params, 'user');
        $url = site_url('user/action') . '?' . $query;

        return $url;
    }

    // --------------------------------------------------------------------

    /**
     * Thanh vien thuc hien hanh dong
     */
    protected function _action_user($action)
    {
        // Neu chua dang nhap
        if (!user_is_login()) {
            redirect_login_return();
        }

        // Lay thong tin cua thanh vien
        $user = user_get_account_info();

        // Chuyen den ham duoc yeu cau
        $this->{'_' . $action}($user);
    }


    /*
     * ------------------------------------------------------
     *  User action handle
     * ------------------------------------------------------
     */
    /**
     * Trang dieu khien chinh
     */
    protected function _index($user)
    {
        /*if(!$user->email || !$user->username || !$user->phone)
        {
            set_message(lang('you_need_to_fully_update_information'));
            redirect(site_url('user/edit'));
        }*/

         $user = UserFactory::auth()->user();
        //pr($user);
        //neu dang ky tai khoan bang sms va chua cap nhat email lan nao
        $user->can_edit_email = false;
        if ($user->register_sms == 1 && $user->edit_email == 0) {
            $user->can_edit_email = true;
        }

        $user->can_edit_phone = false;
        if ($user->register_sms != 1 && $user->edit_phone == 0) {
            $user->can_edit_phone = true;
        }

        $user->can_edit_username = false;
        if (($user->register_sms == 1 || $user->register_api == 1) && $user->edit_username == 0) {
            $user->can_edit_username = true;
        }
      //  $this->data['user'] = $user;
        page_info('title', lang('title_user_info'));
        $this->_edit_view($user);

        $this->_display();
    }


    /*
     * ------------------------------------------------------
     *  Edit
     * ------------------------------------------------------
     */

    /**
     * Chinh sua thong tin
     */
    protected function _edit($user)
    {
        // Cap nhat thong tin
        if ($this->input->get('act') == 'update_image') {
            $this->_edit_update_image($user->id);
            return;
        }

        $form = array();

        //neu dang ky tai khoan bang sms va chua cap nhat email lan nao
        $user->can_edit_email = false;
        if ($user->register_sms == 1 && $user->edit_email == 0) {
            $user->can_edit_email = true;
        }

        $user->can_edit_phone = false;
        if ($user->register_sms != 1 && $user->edit_phone == 0) {
            $user->can_edit_phone = true;
        }

        $user->can_edit_username = false;
        if (($user->register_sms == 1 || $user->register_api == 1) && $user->edit_username == 0) {
            $user->can_edit_username = true;
        }

        $form['validation']['params'] = $this->_edit_params($user);

        $form['submit'] = function ($params) use ($user) {

            return $this->_edit_submit($user);
        };

        $form['form'] = function () use ($user) {
            page_info('title', lang('title_user_edit'));
            $this->_edit_view($user);
            $this->_display();

        };

        $this->_form($form);
    }
    /**
     * Edit view
     */
    protected function _edit_view($user)
    {
        $user = mod("user")->add_info($user);
        $user =user_add_info_other($user);
        $user = mod('user')->url($user);

        $this->data['action'] = current_url();
        $this->data['user'] = $user;
        $this->data['password_lenght'] = model('user')->_password_lenght;


        // Khai bao cac bien cua widget upload
        $widget_upload = array();
        $widget_upload['mod'] = 'single';
        $widget_upload['file_type'] = 'image';
        $widget_upload['status'] = config('file_public', 'main');
        $widget_upload['resize'] = TRUE;
        $widget_upload['thumb'] = TRUE;

        $widget_upload['table'] = $this->_get_mod();
        $widget_upload['table_id'] = $user->id;
        //- up anh avatar
        $widget_upload['url_update'] = ($user->id > 0) ? $this->_url('edit') . '?act=update_image&field=avatar' : null;
        $widget_upload['table_field'] = 'avatar';
        $this->data['upload_avatar'] = $widget_upload;

        //- up file dinh kem
        /*$widget_upload['file_type'] 	= 'file';
        $widget_upload['table_field'] = 'attach';
        $widget_upload['url_update']	= ($user->id > 0) ? current_url().'?act=update_image&field=attach' : null;
        $widget_upload['allowed_types'] =  'pdf|doc|docx';
        $this->data['upload_files'] 	= $widget_upload;*/
        $this->data['countrys'] = model("country")->get_all();
        $this->data['citys'] = model("city")->get_list_rule(["country_id"=>$user->country]);;
        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }

    }

    protected function _edit_params($user)
    {
        $type = $this->input->post('_type');
        if (!$type) return;
        $params = $this->_edit_fields($type);
       // $params [] = 'no';


        //neu dang ky tai khoan bang sms va chua cap nhat email lan nao
        if ($user->can_edit_email) {
            array_push($params, 'email_edit');
        }
        if ($user->can_edit_username) {
            array_push($params, 'username_edit');
        }
        if ($user->can_edit_phone) {
            array_push($params, 'phone_edit');
        }

        if ($this->input->post('password')) {
            array_push($params, 'password', 'password_repeat');
        }

        return $params;
    }


    protected function _edit_fields($type)
    {
        // Thiet lap setting mac dinh
        $fields = array();
        $fields['info'] = array(
            'type', 'name',  /*'email', 'phone',*/ 'address','gender','birthday',
            'job','country','city',   'working_country', 'working_city',
            "website",'profession','desc','facebook','twitter',
        );
        $fields['password'] = array('password','password_old','password_repeat');
        return isset($fields[$type]) ? $fields[$type] : array();

    }





    /**
     * Lay input
     */
    protected function _edit_get_inputs($type)
    {
        $data = array();
        $fields = $this->_edit_fields($type);
        foreach ($fields as $f) {
            $v = $this->input->post($f);
            if (is_array($v)){
                $v= array_unique($v);
                $v = implode(',', $v);
            }
            $data[$f] = $v;
        }
        // anh
        /* $image = $this->_get_image();
         if ($image)
         {
             $data['image_id']	= $image->id;
             $data['image_name']	= $image->file_name;
         }*/
        // pr($data);
        return $data;
    }
    /**
     * Edit params
     */

    /**
     * Edit submit
     */

    protected function _edit_submit($user)
    {
        $type = $this->input->post('_type');
        if (!$type) return;

        $data = $this->_edit_get_inputs($type);
        //pr($data);
        $can_confirm = false;
        if ($data['name'] != $user->name /*|| $data['address'] != $user->address*/) {
            $can_confirm = true;
        }

        $email = $user->email;
        if ($user->can_edit_email && $email != $this->input->post('email_edit')) {
            $can_confirm = true;
            $email = $this->input->post('email_edit');
            $data['email'] = $email;
            $data['edit_email'] = '1';
            $data['password'] = mod('user')->encode_password($this->input->post('password_old'), $email);
        }

        if ($user->can_edit_username && $user->username != $this->input->post('username_edit')) {
            $can_confirm = true;
            $username = $this->input->post('username_edit');
            $data['username'] = $username;
            $data['edit_username'] = '1';
        }
        if ($user->can_edit_phone && $user->phone != $this->input->post('phone_edit')) {
            $can_confirm = true;
            $phone = $this->input->post('phone_edit');
            $data['phone'] = $phone;
            $data['edit_phone'] = '1';
        }

        //neu dang ky tai khoan bang sms va chua cap nhat email lan nao
        if ($password = $this->input->post('password')) {
            $can_confirm = true;
            $data['password'] = mod('user')->encode_password($password, $email);
        }
        // thong tin khac
       /* if($data['birthday']){

        $birthday =explode('-',$data['birthday']);
        $data['birthday_year'] =$birthday[2];
        }*/

        $user_security_type = setting_get('config-user_security_user_edit');
        if (in_array($user_security_type, config('types', 'mod/user_security')) && $can_confirm) {
            t('session')->set_userdata('user_edit', $data);

            mod('user_security')->send('user_edit');

            return $this->_url('edit_confirm');
        } else {
            // Cap nhat vao data
            model('user')->update($user->id, $data);

            set_message(lang('notice_update_success'));

            return $this->_url();
        }

    }

    protected function _edit_confirm($user)
    {
        $data = t('session')->userdata('user_edit');
        if (!$data) {
            redirect(site_url('user/edit'));
        }

        $this->data['key_confirm'] = 'user_edit';
        $this->data['user_edit_param'] = mod('user_security')->param();

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array($this->data['user_edit_param']);
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {

                // Cap nhat vao data
                model('user')->update($user->id, $data);

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = site_url('user/edit');

                set_message(lang('confirmComplate'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }

        // Hien thi view
        $this->_display();
    }

    /**
     * Cap nhat image
     */
    protected function _edit_update_image($id)
    {
        $field = $this->input->get('field');
        // Lay thong tin cua file
        $file = $this->_get_image($id, $field);
        if (!$file) {
            $file = new stdClass();
            $file->id = 0;
            $file->file_name = '';
        }

        // Cap nhat du lieu vao data
        $data = array();
        // $data[$field.'_id']	= $file->id;
        // $data[$field.'_name']	= $file->file_name;
        $data[$field] = $file->file_name;
        model('user')->update($id, $data);
        pr_db();
    }


    protected function _edit_update_infos($id, $data)
    {
        // Cap nhat vao blog info
        foreach ($this->_model()->table_info_adv as $p) {
            if (in_array($p, array('lang')))
                $_p = 'cat_' . $p . '_id';
            elseif (in_array($p, array('job', 'job_cat', 'job_city'))) {
                $_p = $p . '_id';

            } elseif (in_array($p, array('user_school', 'user_specialize', 'user_meetwork', 'user_skill')))
                $_p = str_replace('user', 'cat_u', $p) . '_id';

            // Them vao table info
            if (isset($data[$_p]) && $data[$_p]) {
                if (is_array($data[$_p]))
                    $values = $data[$_p];
                else
                    $values = explode(',', $data[$_p]);
                if (count($values) > 0) {
                    $this->_model()->info_set($p, $id, $values);
                }

            }

        }


    }


    protected function _edit_update_verify($id)
    {
        // neu da xac thuc th thoi
        if($this->data['cancidate']->verify)
            return;

        $settings = $this->data['recruit_settings'];
        if ($settings['mode_verify_cancidate'] == 'auto') {
            // lay thong tin moi nhat
            $info = $this->_mod()->get_info($id);
            // neu chua cap nhan ten, linh vuc thi mien
            foreach (array('title','name','phone','job_id') as  $f ) {
                if(!$info->$f) return;
            }
            $completed= mod('cancidate')->caculate_complete_info($info);
            // neu hoan thanh ho so tren 50% thi tu dong xac thuc
            if($completed > 50)
                $this->_model()->update_field($id,'verify',1);
        }
    }



    /*
       * ------------------------------------------------------
       *  Prepare data handle
       * ------------------------------------------------------
       */

    /**
     * Lay image
     */
    protected function _get_image($id, $field = 'image')
    {
        $image = model('file')->get_info_of_mod($this->_get_mod(), $id, $field, 'id, file_name');
        return $image;
    }

    /**
     * Lay input dang add them noi dung rieng
     */
    protected function _get_data_addtext_inputs($type,$info,$act)
    {
        $data =$this->input->post('text');
        $type = 'cat_' . $type . '_id';// chuyen ve ten truong giong trong CSDL
        // pr( $data_type);
        if (empty($info->$type))// neu chua co du lieu
            $list = array();
        else {
            // echo $type;           pr($info);
            if (!is_array($info->$type) && !is_object($info->$type))
                $list = json_decode($info->$type);
            else
                $list = $info->$type;

        }

        /*  [id] => 39
            [name] => Giám Sát Tác Gi?
            [sort_order] => 1
            [priority] => 1
            [rating] =>
        )*/

        $list = object_to_array($list);
        if ($act == 'add_text') {
            $tmp =array();
            $tmp['id']=random_string('md5');
            $tmp['name']=$data[lang_get_default()->id];
            $tmp['sort_order']=0;
            $tmp['priority']=0;
            $tmp['rating']=3;
            $tmp['content']=$data;

            array_push($list, $tmp);
        }
        /*elseif ($act == 'updatetext') {
            $id = $this->input->post('_id');
            if (isset($list[$id])) {
                $list[$id] = $data;
            }
        }*/

        // pr($list);
        return $list;

    }
    // su ly du lieu dang rating
    protected function _get_data_multi_inputs($type, $info, $act)
    {
        $data = array();
        $fields = $this->_fields($type);
        $data_type= 0; //0: la mang du lieu don ,1 la mang du lieu cac thanh phan
        foreach ($fields as $f) {
            $v = $this->input->post($f);
            if(is_array($v))
            {
                foreach ($v as $_v)
                    $data[][$f] = $_v;
                $data_type =1;
            }
            else
                $data[$f] = $v;
            // pr($langs);
        }

        // pr($data);
        $type = 'cat_' . $type . '_id';// chuyen ve ten truong giong trong CSDL

        // pr( $data_type);
        if (empty($info->$type))// neu chua co du lieu
            $list = array();
        else {
            // echo $type;           pr($info);
            if (!is_array($info->$type) && !is_object($info->$type))
                $list = json_decode($info->$type);
            else
                $list = $info->$type;

        }
        $list = object_to_array($list);
        if ($act == 'add') {
            if($data_type)
                $list=  array_merge($list, $data);
            else
                array_push($list, $data);

        }
        /*elseif ($act == 'update') {

            $id = $this->input->post('_id');
            if (isset($list[$id])) {
                $list[$id] = $data;
            }
        }*/
        // pr($list,0);
        // loc bo id trung
        $list = array_unique_key($list, 'id');
        // pr($list,1);
        // sap sep du lieu , du lieu rieng dc day xuong duoi cung
        $list = array_values(array_sort($list, function ($value) {
            return !is_numeric($value['id']);
        }));

        // pr($list);
        return $list;
    }

    // du lieu la 1 danh sach ma nguoi dung chon moi
    protected function _get_data_multi_special_inputs($type, $info, $act)
    {
        $data = array();
        $fields = $this->_fields($type);

        $new_list = array();
        if ($act == 'add') {
            foreach ($fields as $f) {
                $new_list = $this->input->post($f);
            }
        }
        // Tai dung lai du lieu cu
        $type = 'cat_' . $type . '_id';// chuyen ve ten truong giong trong CSDL

        // pr( $type);
        if (empty($info->$type))// neu chua co du lieu
            $list = array();
        else {

            if (!is_array($info->$type) && !is_object($info->$type))
                $list = json_decode($info->$type);
            else
                $list = $info->$type;

        }
        // pr($list);
        // Bat dau su ly voi su thay doi

        $list = object_to_array($list);

        if ($act == 'add') {
            $tmp_list = array(); // tao danh sach tam chua su ket hop cua 2 danh sach
            // duyet qua danh sach moi va tao 1 danh sach dua tren danh sach cu
            foreach ($new_list as $id) {
                $exist = false;
                foreach ($list as $it) {
                    if ($it['id'] == $id)// neu danh sach cu co phan tu nay
                    {
                        $exist = true;
                        $tmp_list[] = $it;// sao chep phan tu cu vao danh sach moi
                        break;// thoat len vong lap tren
                    }
                }
                // neu phan tu chua ton tai, thi tao moi
                if (!$exist) {
                    $tmp_list[] = array('id' => $id, 'rating' => 3);
                }
            }
            // sap sep du lieu , du lieu rieng dc day xuong duoi cung
            $tmp_list = array_values(array_sort($tmp_list, function ($value) {
                return !is_numeric($value['id']);
            }));
            $list = $tmp_list;
        } elseif ($act == 'update') {

            $id = $this->input->post('_id');
            if (isset($list[$id])) {
                $list[$id] = $data;
            }
        }

        //pr($list);
        return $list;
    }

    /**
     * Lay input jobs
     */
    protected function _get_data_job_inputs()
    {

        $data = $jobs = $job_cats = array();

        // $ids = $this->input->post('jobs');
        $ids =$this->data['jobs'];
        foreach ($ids as $id) {
            $job = model('job')->get_info($id, 'cat_id');

            if ($job) {
                $jobs[] = $id;
                $job_cats[] = $job->cat_id;
            }
        }

        $jobs = array_unique($jobs);
        $job_cats = array_unique($job_cats);

        //pr($jobs,0);
        //pr($job_cats);
        if ($jobs) {
            $data['job_id'] = implode(',', $jobs);
        }
        if ($job_cats) {
            $data['job_cat_id'] = implode(',', $job_cats);
        }
        return $data;
    }
    /**
     * Lay input jobs
     */
    protected function _get_data_ads_job_inputs()
    {
        $data = $jobs =$job_cat = array();
        $ids =$this->data['jobs'];// $this->input->post('jobs');
        if ($ids) {
            foreach ($ids as $id) {
                $job = model('job')->get_info($id, 'cat_id');
                if ($job) {
                    $jobs[] = $id;
                    $job_cat[]=$job->cat_id;
                }
            }
            $jobs = array_unique($jobs);
            $job_cat = array_unique($job_cat);
            // kiem tra so job toi da co the them
            $setting = $this->data['recruit_settings'];
            if (count($jobs) > $setting['ads_cancidate_max_number_job'])
                $this->_response(array('msg_alert' => 'B?n ch? ???c ch?n t?i ?a ' . $setting['ads_cancidate_max_number_job'] . ' l?nh v?c'));
            //========================================



            if ($jobs) {
                // neu co linh vuc thi bat chuc nang quang cao len dong thoi lay thoi gian quang cao tu goi
                $cancidate_packages =$this->data['cancidate_packages'];
                // pr($cancidate_packages);
                $data['ads_job_id'] = implode(',', $jobs);
                $data['ads_job_cat_id'] = implode(',', $job_cat);
                $data['ads_status'] =1;
                if($cancidate_packages['ads_company'] == -1){
                    $data['ads_time'] =-1;
                    $data['ads_expired'] =-1;
                }
                else{
                    //pr($cancidate_packages);
                    $data['ads_time'] =$cancidate_packages['ads_company'];
                    $data['ads_expired'] =$cancidate_packages['_ads_company'];
                }


            }
        }
        else{
            $data['ads_job_id']='';
            $data['ads_status'] =0;
            $data['ads_time'] =0;
            $data['ads_expired'] =0;
        }
        //pr($data);
        return $data;
    }

    // su ly du lieu dang danh sach, nhieu ngon nu
    protected function _get_data_list_inputs($type, $info, $act)
    {
        $data = array();
        $fields = $this->_fields($type);
        foreach ($fields as $f) {
            $vs = $this->input->post($f);
            if (is_array($vs)) {
                foreach ($vs as $l => $v) {
                    $data[$l][$f] = $v;
                }
            }
            // pr($langs);
        }
        // pr( $data_type);
        if (empty($info->$type))// neu chua co du lieu
            $list = array();
        else {
            $list =json_decode($info->$type);
        }
        // pr($data);
        if ($act == 'add') {
            if($list)
                array_push($list, $data);
            else
                $list[] =$data;
        }
        elseif ($act == 'update') {

            $id = $this->input->post('_id');
            if (isset($list[$id])) {
                $list[$id] = $data;
            }
        }

        // pr( $list);

        return $list;
    }

    protected function _get_content_inputs($type)
    {
        $data = array();
        $fields = $this->_content_fields($type);
        foreach ($fields as $f) {
            $vs = $this->input->post($f);
            //echo $f; pr($vs,0);
            if (is_array($vs))
                foreach ($vs as $l => $v) {
                    //if(!$v) continue;
                    $data[$l][$f] = $v;
                }

        }
        /*  if ($type != 'infos_intro')
              foreach ($data as $l => $d) {
                  $data[$l]['name'] = $d['first_name'] . ' ' . $d['last_name'];
              }*/
        // pr($data);
        return $data;
    }

    protected function _get_letter_content_inputs($type)
    {
        $data = array();
        $fields = $this->_fields($type);
        foreach ($fields as $f) {
            $vs = $this->input->post($f);
            if (is_array($vs)) {
                foreach ($vs as $l => $v) {
                    $data[$l][$f] = $v;
                }
            }
            // pr($langs);
        }
        // pr($data);
        return $data;
    }

    function _get_jobs()
    {
        // Kiem tra format
        $ids = $this->input->post('jobs');
        //pr($infos);
        $result = array();

        foreach ($ids as $id) {
            $id = model('job')->check_id($id);

            if ($id) {
                $result[] = $id;
            }
        }

        return $result;
    }


}