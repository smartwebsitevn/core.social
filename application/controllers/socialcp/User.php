<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\User\UserFactory as UserFactory;
use App\User\Model\UserModel as UserModel;
use App\User\Model\UserGroupModel as UserGroupModel;

class User extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->load->helper('user');
        $this->load->model('user_model');
        $this->lang->load('admin/user');
    }

    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array(
            'edit', 'block', 'unblock', 'admin_login', 'del',
            'verify_view', 'verify_accept', 'verify_cancel',
        ))) {
            $this->_action($method);
        } elseif (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            show_404('', FALSE);
        }
    }


    /*
     * ------------------------------------------------------
     *  Rule handle
     * ------------------------------------------------------
     */
    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params = array())
    {
        $password_lenght = model('user')->_password_lenght;

        $rules = array();
        $rules['email'] = array('email', 'required|trim|xss_clean|valid_email|callback__check_email');
        $rules['password'] = array('password', 'required|trim|xss_clean|min_length[' . $password_lenght . ']');
        $rules['password_repeat'] = array('password_repeat', 'required|trim|xss_clean|matches[password]');
        $rules['pin'] = array('pin', 'required|trim|xss_clean|min_length[' . $password_lenght . ']');
        $rules['pin_repeat'] = array('pin_repeat', 'required|trim|xss_clean|matches[pin]');
        $rules['name'] = array('name', 'required|trim|xss_clean');
        $rules['phone'] = array('phone', 'trim|xss_clean|callback__check_phone');
        $rules['address'] = array('address', 'trim|xss_clean');
        $rules['user_group'] = array('user_group', 'required|callback__check_user_group');

        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra email nay da duoc su dung chua
     */
    function _check_email($value)
    {
        $where = array();
        $where['id !='] = intval($this->uri->rsegment(3));
        $where['email'] = $value;
        $id = $this->user_model->get_id($where);

        if ($id) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }


        // kiem tra neu thay email ma khong doi pass thi bao loi
        $act = $this->_get_act();
        $id = $this->uri->rsegment(3);
        if ($act == 'edit' && $id) {
            $user_info = user_get_info($id);
            if ($user_info->email != $value) {
                $password = $this->input->post('password');
                if (!$password) {
                    $this->form_validation->set_message(__FUNCTION__, lang('notice_change_username_require_change_pass'));
                    return FALSE;
                }
            }
        }

        return TRUE;
    }

    /**
     * Kiem tra phone
     */
    public function _check_phone($value)
    {
        if($value) return true;
        $phone = handle_phone($value);

        if (!valid_phone($phone)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        $user_id = $this->uri->rsegment(3);
        $user = $this->_model()->find_user($phone);

        if ($user && $user->id != $user_id) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra user_group
     */
    function _check_user_group($value)
    {
        $this->load->model('user_group_model');

        $where = array();
        $where['id'] = $value;
        $id = $this->user_group_model->get_id($where);

        if (!$id) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Lay so tien giao dich lon nhat trong ngay qua cac payment
     */
    function _get_payments()
    {
        return [];

        // Tai file thanh phan
        $this->load->model('user_group_model');

        // Lay cac payment cua user group
        $user_group_id = $this->input->post('user_group');
        $user_group_payments = UserFactory::userGroup()->find($user_group_id)->payments;

        // Loai bo nhung payment cua user co amount bang amount cua payment trong user group
        $payments = $this->input->post('payments');
        $payments = $payments[$user_group_id];
        foreach ($payments as $payment => $amount) {
            $amount = currency_handle_input($amount);

            if (!isset($user_group_payments[$payment]) || $user_group_payments[$payment] == $amount) {
                unset($payments[$payment]);
            } else {
                $payments[$payment] = $amount;
            }
        }

        return $payments;
    }

    /**
     * Lay danh sach nhom thanh vien
     */
    function _get_list_user_group()
    {
        // Tai file thanh phan
        $this->load->model('user_group_model');
        $this->load->model('payment_model');

        // Lay group mac dinh
        $default = UserFactory::userGroup()->getForUser();
        $default_id = (isset($default->id)) ? $default->id : 0;

        // Lay danh sach nhom thanh vien
        $input = array();
        $input['select'] = 'id, name, payments';
        $input['where']['type !='] = config('user_group_client', 'main');
        $user_groups = $this->user_group_model->get_list($input);
        foreach ($user_groups as $row) {
            $row->payments = @unserialize($row->payments);
            $row->payments = (!is_array($row->payments)) ? array() : $row->payments;

            $payments = array();
            foreach ($row->payments as $payment => $amount) {
                $_row = new stdClass();
                $_row->code = $payment;
                $_row->name = $this->payment_model->get_name($payment);
                $_row->amount = floatval($amount);
                $_row->amount_default = $_row->amount;
                $payments[] = $_row;
            }
            $row->payments = $payments;

            $row->_is_active = ($row->id == $default_id) ? TRUE : FALSE;
        }

        return $user_groups;
    }


    /**
     * Cap nhat image
     */
    protected function _update_image($id, $field = null)
    {
        if (!$field)
            $field = $this->input->get('field');
        $field = $field ? $field : 'avatar';
        // Lay thong tin cua file
        $file = $this->_get_image($id, $field);
        if (!$file) {
            $file = new stdClass();
            $file->id = 0;
            $file->file_name = '';
        }

        // Cap nhat du lieu vao data
        $data = array();
        $data[$field] = $file->file_name;
        $data[$field . '_id'] = $file->id;
        //$data[$field . '_name'] = $file->file_name;
        $this->_model()->update($id, $data);
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


    /*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */
    /**
     * Them moi
     */
    function add()
    {
        // Tao fake id tam thoi de cap nhat cho file dinh kem
        $fake_id = fake_id_get('user');
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('email', 'password', 'password_repeat', 'name', 'username', 'phone', 'user_group');
            $pin = $this->input->post('pin');
            if ($pin) {
                array_push($params, 'pin', 'pin_repeat');
            }

            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                $id=0;
                $data = $this->_get_inputs($id,$fake_id);
                $data['created'] = now();
                $this->_model()->create($data, $id);
                $this->_update_infos($id, $data);

                // Cap nhat lai table_id cua image trong table file
                $this->file_model->update_table_id_of_mod('user', $fake_id, $id);

                // Xoa fake_id
                fake_id_del('user');

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = admin_url('user');
                set_message(lang('notice_add_success'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            $output = json_encode($result);
            set_output('json', $output);
        }
        $this->_create_view_data($fake_id);
        $this->data['currency'] = currency_get_default();
        $this->data['user_groups'] = $this->_get_list_user_group();
        // Hien thi view
        $this->_display('add');
    }

    /**
     * Chinh sua
     */
    function _edit($info)
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Cap nhat thong tin
        if ($this->input->get('act') == 'update_image') {
            $this->_update_image($info->id);
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
            $params = $this->_get_params();
            $this->_set_rules($params);
            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                $data = $this->_get_inputs($info->id,$info->id);
                $this->_model()->update($info->id, $data);
                $this->_update_infos($info->id, $data);
                $this->_update_image($info->id);

                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['location'] = admin_url('user');
                set_message(lang('notice_update_success'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            $output = json_encode($result);
            set_output('json', $output);
        }


        // Xu ly thong tin user
        $info->payments = @unserialize($info->payments);
        $info->payments = (!is_array($info->payments)) ? array() : $info->payments;

        // Xu ly user_group
        $user_groups = $this->_get_list_user_group();
        foreach ($user_groups as $row) {
            $row->_is_active = ($info->user_group_id == $row->id) ? TRUE : FALSE;
            if ($row->_is_active) {
                foreach ($row->payments as $payment) {
                    $payment->amount = (isset($info->payments[$payment->code])) ? $info->payments[$payment->code] : $payment->amount;
                }
            }
        }

        // Luu bien gui den view
        $this->_create_view_data($info->id, $info);


        $info = mod('user')->add_info($info);
        $info->balance = $this->user_model->balance_encrypt('decode', $info->id, $info->balance);
        $info->_balance = currency_convert_format_amount($info->balance);
        $info->user_group = $this->user_group_model->get_info($info->user_group_id, 'id, name');

        $this->data['info'] = $info;
        $this->data['user'] = UserModel::find($info->id);
        $this->data['user_groups'] = $user_groups;
        $this->data['currency'] = currency_get_default();
        $this->data['action'] = current_url(TRUE);

        $filter = array('user_id' => $info->id);
        $input = array();
        $input['limit'] = array(0, 10);
        $balances = mod('log_user_balance')->get_list($filter, $input);
        foreach ($balances as $row)
            $row = mod('log_user_balance')->add_info($row);
        $this->data['balances'] = $balances;
        //pr($this->data['balances']);
        // lich su hoat dong
        $filter = array('table' => 'user', 'table_id' => $info->id);
        $input = array();
        $input['limit'] = array(0, 10);
        $this->data['activities'] = mod('log')->get_list($filter, $input);

        // Hien thi view
        $this->_display('edit');
    }

    /**
     * Khoa tai khoan
     */
    function _block($info)
    {
        // Cap nhat du lieu
        $data = array();
        $data['blocked'] = config('verify_yes', 'main');
        $this->user_model->update($info->id, $data);

        // Gui thong bao
        set_message(lang('notice_update_success'));
    }

    /**
     * Mo lai tai khoan
     */
    function _unblock($info)
    {
        // Cap nhat du lieu
        $data = array();
        $data['blocked'] = config('verify_no', 'main');
        $this->user_model->update($info->id, $data);

        // Gui thong bao
        set_message(lang('notice_update_success'));
    }

    /**
     * Dang nhap vao tai khoan cua user
     */
    function _admin_login($info)
    {
        user_login_set($info->id);

        redirect('user');
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->user_model->del($info->id);

        // Gui thong bao
        set_message(lang('notice_del_success'));
    }

    /**
     * Xem thong tin xac thuc
     */
    function _verify_view($info)
    {
        // Tai file thanh phan
        $this->load->model('user_verify_model');
        $this->load->helper('file');

        // Lay thong tin xac thuc
        $user_verify = $this->user_verify_model->get_info($info->id);
        $user_verify->paypal_emails = @unserialize($user_verify->paypal_emails);
        $user_verify->_created = get_date($user_verify->created);
        foreach (array('image_card_front', 'image_card_back', 'image_photo') as $p) {
            $image = file_get_image_from_name($user_verify->$p);
            $user_verify->$p = $image->url;
        }

        foreach (array('verify_accept', 'verify_cancel') as $p) {
            $info->{'_can_' . $p} = user_can_do($info, $p);
            $info->{'_url_' . $p} = admin_url('user/' . $p . '/' . $info->id);
        }

        // Lay user_group verify
        $this->load->model('user_group_model');
        $user_group_verify = $this->user_group_model->get_type('verify', 'name');
        $user_group_verify = ($user_group_verify) ? $user_group_verify->name : '';
        $this->data['user_group_verify'] = $user_group_verify;

        // Luu bien gui den view
        $this->data['user'] = $info;
        $this->data['user_verify'] = $user_verify;

        // Hien thi view
        $this->load->view('admin/user/verify_view', $this->data);
    }

    /**
     * Chap nhan thong tin xac thuc
     */
    function _verify_accept($info)
    {
        // Gan trang thai xac thuc
        $data = array();
        $data['verify'] = config('user_verify_yes', 'main');

        // Gan user_group_verify
        $this->load->model('user_group_model');
        $user_group_verify = $this->user_group_model->get_type('verify', 'id');
        if ($user_group_verify) {
            $data['user_group_id'] = $user_group_verify->id;
        }

        // Cap nhat du lieu vao data
        $this->user_model->update($info->id, $data);

        // Gui thong bao
        set_message(lang('notice_update_success'));
        return TRUE;
    }

    /**
     * Huy thong tin xac thuc
     */
    function _verify_cancel($info)
    {
        // Tai file thanh phan
        $this->load->model('user_verify_model');
        $this->load->helper('file');

        // Cap nhat du lieu
        $data = array();
        $data['verify'] = config('user_verify_no', 'main');
        $this->user_model->update($info->id, $data);

        // Xoa hinh anh xac thuc
        $user_verify = $this->user_verify_model->get_info($info->id);
        foreach (array('image_card_front', 'image_card_back', 'image_photo') as $p) {
            $file = new stdClass();
            $file->file_name = $user_verify->$p;
            $file->status = config('file_public', 'main');
            file_del($file);
        }

        // Xoa thong tin xac thuc
        $this->user_verify_model->del($info->id);

        // Gui thong bao
        set_message(lang('notice_update_success'));
        return TRUE;
    }

    /**
     * Thuc hien tuy chinh
     */
    function _action($action)
    {
        // Lay input
        $ids = $this->uri->rsegment(3);
        $ids = (!$ids) ? $this->input->post('id') : $ids;
        $ids = (!is_array($ids)) ? array($ids) : $ids;

        // Thuc hien action
        foreach ($ids as $id) {
            // Xu ly id
            $id = (!is_numeric($id)) ? 0 : $id;

            // Kiem tra id
            $info = $this->user_model->get_info($id);
            if (!$info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if (!user_can_do($info, $action)) continue;

            // Chuyen den ham duoc yeu cau
            $this->{'_' . $action}($info);
        }
    }


    /*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
    /**
     * Danh sach
     */
    function index()
    {
        // Export
        if ($this->input->get('act') == 'export') {
            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;
            $result['location'] = admin_url('user/export');
            set_output('json', json_encode($result));
        }

//		$this->_list([
//			'filter' => true,
//			'filter_fields' => [
//				'id', 'invoice_id', 'service_key', 'key', 'amount', 'profit',
//				'invoice_status', 'order_status', 'created', 'created_to',
//			],
//			'input' => ['relation' => ['user_group', 'purse']],
//			'order' => true,
//			'order_fields' => ['id', 'service_key', 'order_status', 'amount', 'profit', 'created'],
//			'actions' => ['view'],
//			'actions_list' => [],
//			'display' => false,
//		]);
//
//		foreach ($this->data['list'] as &$row)
//		{
//			$row = (array) $row;
//
//			$row['purses'] = array_pull($row, 'purse');
//
//			$row = (object) $row;
//		}
//
//		$this->data['list'] = UserModel::makeCollection($this->data['list']);
//
//		$this->_display();
//
//		return;

        // Tai cac file thanh phan
        $this->load->helper('form');
        $this->load->model('user_group_model');

        // Lay config
        $verify = config('verify', 'main');
        $user_verifies = config('user_verifies', 'main');


        // Lay gia tri cua filter dau vao
        $filter_input = array();
        $filter_fields = array('id', 'key', 'email', 'gender', 'city', 'country', 'birthday_year',
            'created', 'created_to', 'blocked', 'verify', 'user_group', 'balance', 'currency');
        foreach ($filter_fields as $f) {
            $v = $this->input->get($f);

            if (
                $f == 'blocked' && !in_array($v, $verify) ||
                $f == 'verify' && !in_array($v, $user_verifies) ||
                $f == 'balance' && !in_array($v, $verify)
            ) {
                $v = '';
            }

            $filter_input[$f] = $v;
        }

        if ($filter_input['id']) {
            foreach ($filter_input as $f => $v) {
                $filter_input[$f] = ($f != 'id') ? '' : $v;
            }
        }
        $this->data['filter'] = $filter_input;

        // Tao bien filter
        $filter = array();
        foreach ($filter_input as $f => $v) {
            if (!strlen($v)) continue;

            switch ($f) {
                case 'created': {
                    $created_to = $filter_input['created_to'];
                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
                    $v = get_time_between($v);
                    $v = (!$v) ? NULL : $v;
                    break;
                }
                case 'blocked': {
                    $v = config('verify_' . $v, 'main');
                    break;
                }
                case 'verify': {
                    $v = config('user_verify_' . $v, 'main');
                    break;
                }
                case 'balance': {
                    $v = ($v == 'yes') ? TRUE : FALSE;
                    break;
                }
            }

            if ($v === NULL) continue;

            $filter[$f] = $v;
        }


        // Lay tong so
        $total = $this->user_model->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = min($limit, $total - fmod($total, $page_size));
        $limit = max(0, $limit);

        // Lay danh sach
        $input = array();
        $input['limit'] = array($limit, $page_size);
        //$input['order'] = (isset($filter['balance']) && $filter['balance']) ? array('balance_decode', 'desc') : array('id', 'desc');
        $list = $this->user_model->filter_get_list($filter, $input);

        $actions = array('edit', 'del', 'block', 'unblock', 'admin_login', 'verify_view');
        $list = admin_url_create_option($list, 'user', 'id', $actions);
        foreach ($list as $row) {
            $row = mod('user')->add_info($row);
            $row = user_add_info_other($row);
            foreach ($actions as $action) {
                $row->{'_can_' . $action} = user_can_do($row, $action);
            }
        }
//pr($list);

        $list = model('user')->relations(['user_group', 'purse'], $list);

        foreach ($list as &$row) {
            $row = (array)$row;

            $row['purses'] = array_pull($row, 'purse');

            $row = (object)$row;
        }

        $list = UserModel::makeCollection($list);


        $this->data['list'] = $list;


        // Tao query chia trang
        $pages_query = array();
        foreach ($filter_input as $f => $v) {
            if (!strlen($v)) continue;
            $pages_query[$f] = $v;
        }
        $pages_query = http_build_query($pages_query);

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = admin_url('user') . '?' . $pages_query;
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;

        // Tao action list
        $actions = array();
        foreach (array('block', 'unblock', 'del') as $v) {
            $url = admin_url('user/' . $v);
            $actions[$v] = $url;
        }
        $this->data['actions'] = $actions;

        // Luu bien gui den view
        $this->data['action'] = current_url();
        $this->data['verify'] = $verify;
        $this->data['user_verifies'] = $user_verifies;
        $this->data['user_groups'] = $this->user_group_model->get_list();
        $this->data['currency_list'] = currency_get_list();
        $this->data['url_search_email'] = admin_url('user/ac/email');
        $this->data['url_export'] = admin_url('user') . '?act=export';
        $this->data['countrys'] = model("country")->get_all();
        $citys = [];
        if (isset($filter["country"]) && $filter["country"])
            $citys = model("city")->get_list_rule(["country_id" => $filter["country"]]);
        $this->data['citys'] = $citys;

        // Hien thi view
        $this->_display();
    }

    /**
     * Export Danh sach
     */
    function export()
    {

        $input = array();
        $input['select'] = 'id,name,phone,email,balance';
        $input['order'] = array('id', 'asc');
        $list = $this->_model()->get_list($input);

        $list = model('user')->relations(['purse'], $list);

        foreach ($list as &$row) {
            $row = (array)$row;

            $row['purses'] = array_pull($row, 'purse');

            $row = (object)$row;
        }
        $list = UserModel::makeCollection($list);
        $this->data['list'] = $list;
        $this->_display('export', null);
    }

    /**
     * Tim kiem tu dong (autocomplete)
     */
    function ac()
    {
        $fields = array('email', 'name');
        $field = $this->uri->rsegment(3);
        $field = (!in_array($field, $fields)) ? $fields[0] : $field;
        $keyword = $this->input->get('term');

        $filter = array();
        $filter[$field] = $keyword;
        $input = array();
        $input['order'] = array($field, 'asc');
        $input['limit'] = array(0, config('list_auto_limit', 'main'));
        $list = $this->user_model->filter_get_list($filter, $input);

        $result = array();
        foreach ($list as $row) {
            $item = array();
            $item['id'] = $row->id;
            $item['label'] = $row->$field;
            $item['value'] = $row->$field;

            $result[] = $item;
        }

        $output = json_encode($result);
        set_output('json', $output);
    }

    /**
     * Lay quan huyen theo thanh pho
     */
    function get_citys()
    {
        $country_id = $this->input->get_post('id', TRUE);
        $result = array();
        if ($country_id) {
            $list = model("city")->get_list_rule(["country_id" => $country_id]);
            foreach ($list as $it) {
                $item = array();
                $item['id'] = $it->id;
                $item['label'] = $it->name;
                $item['value'] = $it->name;
                $result[] = $item;
            }

        }
        $output = json_encode($result);
        set_output('json', $output);
    }


    protected function _get_params()
    {
        // Gan dieu kien cho cac bien
        $params = array('email', 'name', 'phone', 'address', 'user_group');

        $password = $this->input->post('password');
        if ($password) {
            array_push($params, 'password', 'password_repeat');
        }

        $pin = $this->input->post('pin');
        if ($pin) {
            array_push($params, 'pin', 'pin_repeat');
        }

        // $params =  $this->_model()->fields;
        // array_push($params, 'image','avatar','icon', 'banner');
        return $params;
    }
    protected function _get_inputs($id=null,$fake_id=null)
    {
        $data = parent::_form_get_inputs($id,$fake_id);
        // Lay email
        $email = $this->input->post('email');

        // Lay payment
        $payments = $this->_get_payments();
        $payments = serialize($payments);

        // Luu thong tin thanh vien
        /*$data['email']			= $email;
        $data['name']			= $this->input->post('name');
        $data['phone']			= $this->input->post('phone');
        $data['address']		= $this->input->post('address');*/
        $data['verify'] = $this->input->post('verify');
        $data['activation'] = $this->input->post('activation');
        $data['blocked'] = $this->input->post('blocked');
        $data['user_group_id'] = $this->input->post('user_group');
        $data['payments'] = $payments;
        //echo $password;
        if ($password=$this->input->post('password')) {
            $data['password'] = security_encode($password, strtolower($email));
        }
        //pr($data);
        if ($pin=$this->input->post('pin')) {
            $data['pin'] = security_encode($pin);
        }

        foreach (model('user')->_info_key as $p) {
            $data[$p] = $this->input->post($p);
        }
        foreach (model('user')->_info_genneral as $p) {
            $v = $this->input->post($p);
            if (in_array($p, array('desc')))
                $v = handle_content($v, 'input');
            $data[$p] = $v;
        }
        foreach (model('user')->_info_id as $p) {
            $data[$p] = $this->input->post($p);
        }
        foreach (model('user')->_info_social as $p) {
            $data[$p] = $this->input->post($p);
        }
        foreach (model('user')->_info_card as $p) {
            $data[$p] = $this->input->post($p);
        }
       // pr( get_time_from_date($data['adsed_begin']));
        if ($data['adsed_begin'])
            $data['adsed_begin'] = get_time_from_date($data['adsed_begin']);
        if ($data['adsed_end'])
            $data['adsed_end'] = get_time_from_date($data['adsed_end']);



        return $data;
    }

    protected function _update_infos($id, $data)
    {


    }
    protected function _create_view_data($id, $info = null)
    {
        parent::_form_create_view($id, $info);

    }

}