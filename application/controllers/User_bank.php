<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_bank extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();

        if (!user_is_login()) {
            redirect_login_return();
        }
        $user = user_get_account_info();
        $this->data['user'] = $user;

        $this->lang->load('site/user_bank');
    }


    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array('edit', 'del', 'confim'))) {
            $this->_action($method);
        } elseif (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            show_404('', FALSE);
        }
    }

    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $confim = mod('user_security')->param();
        $rules = array();
        $rules['bank'] = array('bank', 'required|trim|callback__check_bank');
        $rules['city'] = array('city', 'required|trim|callback__check_city');
        $rules['bank_branch'] = array('bank_branch', 'required|trim|xss_clean');
        $rules['bank_account'] = array('bank_account', 'required|trim|xss_clean');
        $rules['bank_account_name'] = array('bank_account_name', 'required|trim|xss_clean');
        $rules['pin'] = array('pin', 'required|trim|xss_clean|callback__check_pin');
        $rules[$confim] = array('security_code', 'required|trim|callback__check_key_confim');
        $rules['security_code'] = array('security_code', 'required|trim|callback__check_security_code');

        $this->form_validation->set_rules_params($params, $rules);
    }

    function _check_key_confim($value)
    {
        if (mod('user_security')->valid($this->data['key_confim']))
            return true;
        $this->form_validation->set_message(__FUNCTION__, mod('user_security')->getErrorMessage());
        return false;
    }

    /**
     * Kiem tra type
     */
    public function _check_bank($value)
    {
        $value = intval($value);
        $bank = model('bank')->get_info($value);
        if (!$bank) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return false;
        }

        if ($bank->status != 1 || $bank->use_in_withdraw != 1) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return false;
        }

        $user = $this->data['user'];
        $filter = array();
        $filter['user'] = $user->id;
        $filter['bank'] = $value;
        $user_bank = model('user_bank')->filter_get_list($filter);
        if (count($user_bank) > 0) {
            $this->form_validation->set_message(__FUNCTION__, 'Bạn đã có tài khoản ngân hàng này');
            return false;
        }

        return TRUE;
    }

    /**
     * Kiem tra city
     */
    public function _check_city($value)
    {
        if (!model('city')->get_info($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return false;
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
     * Kiem tra ma bao mat
     */
    public function _check_security_code($value)
    {
        if (!lib('captcha')->check($value, 'four')) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Thanh vien thuc hien hanh dong
     */
    protected function _action($action)
    {
        $user = $this->data['user'];
        // Lay input
        $ids = $this->uri->rsegment(3);
        $ids = (!$ids) ? $this->input->post('id') : $ids;
        $ids = (!is_array($ids)) ? array($ids) : $ids;

        // Thuc hien action
        foreach ($ids as $id) {
            // Xu ly id
            $id = (!is_numeric($id)) ? 0 : $id;

            // Kiem tra id
            $info = model('user_bank')->get_info($id);
            if (!$info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if ($user->id != $info->user_id) continue;

            // Chuyen den ham duoc yeu cau
            $this->{'_' . $action}($info);
        }
    }

    /**
     * Danh sach
     */
    function index()
    {
        $user = $this->data['user'];
        $filter = array();
        $filter['user'] = $user->id;
        $list = model('user_bank')->filter_get_list($filter);
        foreach ($list as $row) {
            $row->bank = model('bank')->get_info($row->bank_id, 'name');
            $row->city = model('city')->get_info($row->city_id, 'name');
        }
        $this->data['list'] = $list;

        // Breadcrumbs
        page_info('breadcrumbs', array(current_url(), lang('title_user_bank')));

        // Gan thong tin page
        page_info('title', lang('title_user_bank'));

        // Hien thi view
        $this->_display();
    }

    /**
     * Them moi
     */
    function add()
    {
        $user = $this->data['user'];

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        $this->data['captcha'] = site_url('captcha/four');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('bank', 'city', 'bank_branch', 'bank_account', 'bank_account_name', 'security_code');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                // Xoa captcha
                $this->captcha_library->del('four');

                // Cap nhat vao data
                $data = array();
                $data['user_id'] = $user->id;
                $data['bank_id'] = $this->input->post('bank');
                $data['city_id'] = $this->input->post('city');
                $data['bank_branch'] = $this->input->post('bank_branch');
                $data['bank_account'] = $this->input->post('bank_account');
                $data['bank_account_name'] = $this->input->post('bank_account_name');
                $data['created'] = now();
                $data['last_update'] = now();
                $data['status'] = mod('order')->status('pending');
                $id = 0;
                model('user_bank')->create($data, $id);

                // Khai bao du lieu tra ve
                if ($this->input->get('action') == 'withdraw') {
                    $url = site_url('withdraw/send');
                } else {
                    $url = site_url('user_bank');
                }
                $result['complete'] = TRUE;
                $result['location'] = $url;
                // url xac thuc
                $user_security_type = setting_get('config-user_security_user_bank');
                if ($data['status'] == mod('order')->status('pending') && in_array($user_security_type, config('types', 'mod/user_security')))
                    $result['location'] = site_url('user_bank/confim/' . $id);
                //set_message(lang('notice_add_success'));
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
        $banks = model('bank')->get_list(['where' => [
            'status' => 1,
            'use_in_withdraw' => 1,
        ]]);

        $this->data['banks'] = $banks;
        $this->data['citys'] = model('city')->get_list_rule(["country_id" => 230]);
        $message = get_message();
        $this->data['message'] = $message;


        // Hien thi view
        $this->_display();
    }

    /**
     * Chinh sua
     */
    function _edit($info)
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }
        $this->data['captcha'] = site_url('captcha/four');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('city', 'bank_branch', 'bank_account', 'bank_account_name', 'security_code');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                // Xoa captcha
                $this->captcha_library->del('four');

                // Cap nhat vao data
                $data = array();
                $data['city_id'] = $this->input->post('city');
                $data['bank_branch'] = $this->input->post('bank_branch');
                $data['bank_account'] = $this->input->post('bank_account');
                $data['bank_account_name'] = $this->input->post('bank_account_name');
                $data['last_update'] = now();
                //neu co thay doi thong tin
                $confim = false;
                foreach (array('bank_branch', 'city', 'bank_account', 'bank_account_name') as $p) {
                    $d = ($p == 'city') ? $p . '_id' : $p;
                    if ($info->{$d} != $this->input->post($p)) {
                        $data['status'] = mod('order')->status('pending');
                        $confim = true;
                    }
                }
                model('user_bank')->update($info->id, $data);

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = site_url('user_bank');
                $user_security_type = setting_get('config-user_security_user_bank');
                if ($confim && in_array($user_security_type, config('types', 'mod/user_security')))
                    $result['location'] = site_url('user_bank/confim/' . $info->id);

                //set_message(lang('notice_update_success'));
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
        $info->bank = model('bank')->get_info($info->bank_id, 'name');
        $this->data['info'] = $info;
        $banks = model('bank')->get_list(['where' => [
            'status' => 1,
            'use_in_withdraw' => 1,
        ]]);

        $this->data['banks'] = $banks;
        $this->data['citys'] = model('city')->get_list_rule(["country_id"=>230]);

        // Hien thi view
        $this->_display();
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        model('user_bank')->del($info->id);

        // Gui thong bao
        set_message(lang('notice_del_success'));
        redirect(site_url('user_bank'));
    }

    function _confim($info)
    {
        if ($info->status == mod('order')->status('completed'))
            redirect(site_url('user_bank'));

        // gui ma sms
        mod('user_security')->send('user_bank');
        $this->data['key_confim'] = 'user_bank';
        $this->data['user_bank_param'] = mod('user_security')->param();
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array($this->data['user_bank_param']);
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {

                // Cap nhat vao data
                $data = array();
                $data['status'] = mod('order')->status('completed');
                model('user_bank')->update($info->id, $data);

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = site_url('user_bank');

                set_message(lang('confimComplate'));
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
        $info->bank = model('bank')->get_info($info->bank_id, 'name');
        $this->data['info'] = $info;
        $banks = model('bank')->get_list(['where' => [
            'status' => 1,
            'use_in_withdraw' => 1,
        ]]);

        $this->data['banks'] = $banks;
        $this->data['citys'] = model('city')->get_list();

        // Hien thi view
        $this->_display();
    }

}
