<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->load->model('setting_model');
        $this->lang->load('admin/setting');
    }


    /*
     * ------------------------------------------------------
     *  Setting handle
     * ------------------------------------------------------
     */
    /**
     * Lay va kiem tra setting
     */
    function _check_setting()
    {
        // Thiet lap setting mac dinh
        $setting_default =$this->_get_setting_default();
       
        // Lay setting trong data
        $setting = $this->setting_model->get_group('config');

        //pr($setting);
        // Neu setting thay doi thi cap nhat lai setting vao data
        if (count($setting) != 1){//count($setting_default)) {
            $setting = extend($setting_default, $setting);
            
            $this->setting_model->del_group('config');
            $this->setting_model->set_group('config', $setting);
        }
        return $setting;
    }
    
    function _get_setting_default($type=null)
    {


        // Thiet lap setting mac dinh
        $setting_default = array();

        //===== General
        $setting_default['general'] = array(
            // General
            'logo' => '',
            'logo_admin' => '',
            'logo_slogan' => '',

            'favicon' => '',
            'no_index' => 0,
            'maintenance' => 0,
            'maintenance_notice' => '',
        );

        // thong tin lien he
        foreach (array('name','slogan', 'email', 'hotline', 'phone', 'fax', 'email', 'yahoo', 'skype', 'address', 'support', 'livechat', 'video') as $p) {
            $setting_default['general'][$p] = '';
        }
        // seo
        foreach (array('meta_desc', 'meta_key', 'meta_other', 'embed_js') as $p) {
            $setting_default['general'][$p] = '';
        }
        // mang xa hoi
        foreach (array('facebook', 'twitter', 'youtube', 'googleplus', 'linkedin', 'instagram') as $p) {
            $setting_default['general'][$p] = '';
        }
        //===== Image
        $setting_default['image'] = array(
            'upload_img_max_width' => '',
            'upload_img_max_height' => '',
            'upload_img_resize_width' => '',
            'upload_img_resize_height' => '',
            'upload_img_thumb_width' => '',
            'upload_img_thumb_height' => '',
            'upload_img_thumb1_width' => '',
            'upload_img_thumb1_height' => '',
            'upload_img_thumb2_width' => '',
            'upload_img_thumb2_height' => '',
            'upload_img_thumb3_width' => '',
            'upload_img_thumb3_height' => '',
            'upload_img_thumb4_width' => '',
            'upload_img_thumb4_height' => '',
            'upload_img_thumb5_width' => '',
            'upload_img_thumb5_height' => '',

            'upload_server_status' => '',
            'upload_server_url' => '',
            'upload_server_hostname' => '',
            'upload_server_username' => '',
            'upload_server_password' => '',
            'upload_server_save_on_local' => '',
        );

        //===== Local
        $setting_default['local'] = array(

            // Local
            'admin_language' => 0,
            'site_language' => 0,
            'timezone' => '',
            'date_format' => '',
            'invoice_pre_key'=>'',
            'invoice_pre_number'=> 0,
            'length_unit' => '',
            'weight_unit' => '',
            'file_unit' => '',
            'banned_countries' => '',
            'banned_ips' => '',

        );

        //===== Server
        $setting_default['server'] = array(
            //===== server
           // 'base_url' => 0,
            'server_ip' => 0,

            'use_ssl' => 0,
            'use_seo_url' => 0,
            'xss_protect' => 0,
            'upload_max_size' => '',
            'upload_max_size_admin' => '',
            'upload_allowed_types' => '',
            'proxy_ips' => '',

            //log
            'log_error' => 0,
            'log_access' => 0,
            'log_activity' => 0,
            'log_user_balance' => 0,
            // captcha
            'captcha_type' => 0,
            'captcha_google_api_url' => 0,
            'captcha_google_secret_key' => 0,
            'captcha_google_site_key' => 0,
        );
        foreach (array('email_protocol', 'email_from_email', 'email_from_name',
                     'email_reply_email', 'email_reply_name',
                     'nencer_mail_api_user','nencer_mail_api_pass') as $p) {
            $setting_default['server'][$p] = '';
        }

        //===== Security
        $setting_default['security'] = array(
            //===== admin
            'admin_matrix' => 0,
            //===== user
            'user_register_allow' => 0,
            'user_register_require_activation' => 0,
            'user_register_banned_countries' => '',


            'user_login_allow' => 0,
            'user_login_fail_count_max' => 0,
            'user_login_fail_block_timeout' => 0,
            'user_login_check_ip' => 0,

            'user_balance_block'  => 0,
            'user_balance_timeout_from_register' => 0,
        );
        
        $user_security_mods =  config('mods', 'mod/user_security');
        foreach ($user_security_mods as $user_security_mod)
        {
            $setting_default['user_security']['user_security_'.$user_security_mod] = '';
        }
        $setting_default['user_security']['user_security_sms_otp_message']    = '';
        $setting_default['user_security']['user_security_sms_odp_message']    = '';
        
        $setting_default['user_security']['sms_otp_max_re_send'] = '';
        $setting_default['user_security']['sms_odp_max_re_send'] = '';
        $setting_default['user_security']['sms_otp_max_send']    = '';
        
        
        //===== Connect
        foreach (array('facebook_oauth_id', 'facebook_oauth_key', 'google_oauth_id', 'google_oauth_key') as $p) {
            $setting_default['connect'][$p] = '';
        }
        //===== License
        foreach (array('license_key', 'license_domain', 'license_status', 'license_expired') as $p) {
            $setting_default['license'][$p] = '';
        }


        if($type)
            return $setting_default[$type];

        $rs=array();
        foreach($setting_default as $settings)
            foreach($settings as $k=> $v)
                $rs[$k] =$v;
        
        return $rs;
    }
    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params)
    {
        $rules = array();
        $setting_default =$this->_get_setting_default();
        foreach($setting_default as $k=>$v){
            if(in_array($k,array('favicon','logo','logo_slogan','logo_admin')))
                continue;
            elseif(in_array($k,array('name')))
                $rules[$k] = array($k, 'required|trim|xss_clean');
            elseif(in_array($k,array('email')))
                $rules[$k] = array($k, 'required|valid_email|trim|xss_clean');
            elseif(in_array($k,array('invoice_pre_key')))
                $rules[$k] = array($k, 'required|exact_length[3]|alpha|trim|xss_clean');
            else{
                $rules[$k] = array($k, 'required|trim|xss_clean');
            }
        }
       /* $rules['name'] = array('site_name', 'required|trim|xss_clean');
        $rules['email'] = array('site_email', 'required|valid_email');
        $rules['meta_desc'] = array('meta_desc', 'trim|xss_clean');
        $rules['meta_key'] = array('meta_key', 'trim|xss_clean');
        $rules['maintenance_notice'] = array('maintenance_notice', 'required|trim|xss_clean');
        $rules['order_quantity_max'] = array('order_quantity_max', 'required|trim|is_natural_no_zero');
        $rules['deposit_amount_min'] = array('deposit_amount_min', 'required|trim|callback__check_amount');
        $rules['deposit_amount_max'] = array('deposit_amount_max', 'required|trim|callback__check_amount');*/
        //$rules['banned_countries'] 				= array('banned_countries', 'required|callback__check_banned_countries');
        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra so tien
     */
    function _check_amount($value)
    {
        $value = floatval($value);
        if ($value <= 0) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Cap nhat image
     */
    function _update_image()
    {
        $id =$this->input->get('id') ;
        if (!in_array($id,array('logo','logo_slogan','logo_admin','favicon')))
            return;

        $file =model('file')->get_info_rule(array('table'=>'setting','table_id'=>$id));
        if ( ! $file)
        {
            $file = new stdClass();
            $file->id = 0;
            $file->file_name = '';
        }

        // Cap nhat du lieu vao data
        $this->setting_model->set('config-'.$id, $file->file_name);
    }
    /**
     * Tu dong kiem tra gia tri cua bien
     */
    function _autocheck($param)
    {
        $this->_set_rules($param);

        $result = array();
        $result['accept'] = $this->form_validation->run();
        $result['error'] = form_error($param);

        $output = json_encode($result);
        set_output('json', $output);
    }

    /**
     * Cau hinh website
     */
    function index()
    {
        // Lay setting
        $setting = $this->_check_setting();

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('file');
        // Cap nhat thong tin
        if ($this->input->get('act') == 'update_image')
        {
            $this->_update_image();
            exit();
        }
        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('name', 'email', 'meta_desc', 'meta_key');
            // local
            array_push($params, 'invoice_pre_key');
           // array_push($params, 'order_quantity_max', 'deposit_amount_min', 'deposit_amount_max');

            $maintenance_status = ($this->input->post('maintenance')) ? 'status_on' : 'status_off';
            if ($maintenance_status == 'status_on') {
                $params[] = 'maintenance_notice';
            }

            $this->_set_rules($params);


            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
               // pr($setting);
                // General
                foreach($setting as $k=>$v){
                    if(in_array($k,array('favicon','logo','logo_slogan','logo_admin'))){

                        $file =model('file')->get_info_rule(array('table'=>'setting','table_id'=>$k));
                        if($file)
                            $setting[$k]=$file->file_name;

                    }
                    else if(in_array($k,array('embed_js'))){
                        $setting[$k]=$_POST[$k];
                    }
                    else if(in_array($k,array('invoice_pre_key'))){
                        $s = $this->input->post($k);
                        $setting[$k]=strtoupper($s);
                    }
                    else {
                        $s = $this->input->post($k);
                        if (is_string($s))
                            $s = trim($s);
                        $setting[$k] = $s;
                    }
                }

                /*
                // Option
                $setting['deposit-status'] = config(($this->input->post('deposit_status')) ? 'status_on' : 'status_off', 'main');
                $setting['deposit-amount_min'] = currency_handle_input($this->input->post('deposit_amount_min'));
                $setting['deposit-amount_max'] = currency_handle_input($this->input->post('deposit_amount_max'));

                // Order
                $setting['order_quantity_max'] = $this->input->post('order_quantity_max');
                $setting['order-auto_active'] = $this->input->post('order_auto_active');
                $setting['order-voip-amount_min'] = currency_handle_input($this->input->post('order_voip_amount_min'));
                $setting['order-voip-amount_max'] = currency_handle_input($this->input->post('order_voip_amount_max'));
                $setting['order-topup_mobile_post-amount_min'] = currency_handle_input($this->input->post('order_topup_mobile_post_amount_min'));
                $setting['order-topup_mobile_post-amount_max'] = currency_handle_input($this->input->post('order_topup_mobile_post_amount_max'));*/


                // Cap nhat vao data
                $this->setting_model->set_group('config', $setting);

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = admin_url('setting');
                set_message(lang('notice_update_success'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            $output = json_encode($result);
            set_output('json', $output);
        }

        $file_types = array();
        $dont_allow_types = explode('|', 'php|ini|log|tpl|inc|sql|php3|php4|php5|php6|phtml|pl|py|jsp|asp|shtml|sh|cgi');
        //pr($dont_allow_types);
        foreach (get_mimes() as $k => $v) {
            if(in_array($k,$dont_allow_types)) continue;
            $file_types[] = $k;
        }

        // Khai bao cac bien cua widget upload
        $upload = array();
        $upload['mod'] = 'single';
        $upload['file_type'] = 'image';
        $upload['status'] = config('file_public', 'main');
        $upload['table'] = 'setting';
        //$upload['table_id'] 		= 'logo';
        $upload['table_field'] = 'image';
        $upload['resize'] = false;
        $upload['thumb'] = false;
        $upload['url_update'] = current_url() . '?act=update_image';

        $upload_logo = $upload_logo_slogan = $upload_logo_admin = $upload_favicon = $upload;
        //-
        $upload_logo['table_id'] = 'logo';
        $upload_logo['url_update'] .='&id=logo';
        //-
        $upload_logo_slogan['table_id'] = 'logo_slogan';
        $upload_logo_slogan['url_update'] .='&id=logo_slogan';
        //-
        $upload_logo_admin['table_id'] = 'logo_admin';
        $upload_logo_admin['url_update'] .='&id=logo_admin';

        $upload_favicon['table_id'] = 'favicon';
        $upload_favicon['url_update'] .='&id=favicon';

        // Luu cac bien gui den view
        $this->data['setting'] = $setting;
        $this->data['upload_logo'] = $upload_logo;
        $this->data['upload_logo_slogan'] = $upload_logo_slogan;
        $this->data['upload_logo_admin'] = $upload_logo_admin;
        $this->data['upload_favicon'] = $upload_favicon;


        $this->data['currency'] = currency_get_default();
        $this->data['timezones'] = config('all','timezones');// DateTimeZone::listIdentifiers();
        $this->data['languages'] = lang_get_list();
        $this->data['unit_lengths'] =config('length','units');
        $this->data['unit_weights'] =config('weight','units');
        $this->data['unit_files'] =config('file','units');

        $this->data['date_formats'] = config('date_formats');


       // $this->data['countries'] = model('country')->get();
        $this->data['countries'] = model('country')->get_grouped();
        $this->data['file_types'] = $file_types;
        $this->data['captcha_types'] = config('captcha_types');
        $this->data['mail_protocols'] = config('mail_protocols');
        
        $user_security_mods =  config('mods', 'mod/user_security');
        $this->data['user_security_mods'] = $user_security_mods;
        $this->data['user_security_types'] = config('types', 'mod/user_security');
        
        $this->data['action'] = current_url();
        if( config('language_multi', 'main'))
        $this->data['url_translate'] = admin_url("setting/translate");

        // Hien thi view
        $this->_display();
    }


    /**
     * Dich thong tin
     */
    function translate()
    {
        if(! config('language_multi', 'main'))
           redirect_admin();

        $table ='setting';
        $id='key';
        $row = (object)model('setting')->get_group('config');


        // Lay cac field can dich cua table
        $field	= $this->_model()->translate_fields;
        // Lay danh sach lang
        $langs = lang_get_list();
        $langs = array_where($langs, function($i, $lang)
        {
            return ( ! $lang->is_default);
        });

        // Tai cac file thanh phan
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Xu ly form
        if ($this->input->post('_submit'))
        {
            // Lay data
            $data = array();
            foreach ($field as $f)
            {
                $vs = $this->input->post($f);
                foreach ($vs as $l => $v)
                {
                    // Loai bo cac ban dich giong ban goc
                    $vs[$l] = (strip_tags($v) == strip_tags($row->$f)) ? '' : $v;
                }

                $data[$f] = $vs;
            }

            // Cap nhat vao data
            model('translate')->set($table, $id, $data);

            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;
            $result['location'] = current_url();
            set_message(lang('notice_update_success'));

            // Form output
            $this->_form_submit_output($result);
        }


        // Lay cac ban dich cua row
        $info = model('translate')->get($table, $id);

        foreach ($field as $f)
        {
            foreach ($langs as $l)
            {
                $info[$f][$l->id] = (empty($info[$f][$l->id])) ? $row->$f : $info[$f][$l->id];
            }
        }

        // Luu cac bien gui den view
        $this->data['action'] 	= current_url();
        $this->data['message'] 	= get_message();
        $this->data['langs'] 	= $langs;
        $this->data['field'] 	= $field;
        $this->data['info']		= $info;

        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(admin_url('page'), lang('mod_setting'));
        $breadcrumbs[] = array(current_url(), lang('mod_translate'));
        $this->data['breadcrumbs'] = $breadcrumbs;

        // Hien thi view
        $this->_display("/admin/setting/translate");
    }

}