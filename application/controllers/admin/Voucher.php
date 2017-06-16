<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Voucher extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->lang->load('admin/voucher');
        $this->data['types'] = config('voucher_types');
        //$this->data['products'] = model('product')->get_list();
        //$this->data['lessons'] = model('lesson')->get_list();
    }

    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array('view', 'edit', 'del',))) {
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
        if (!is_array($params)) {
            $params = array($params);
        }
        $rules = array();
        $rules['name'] = array('name', 'required|trim|xss_clean');
        $rules['comment'] = array('comment', 'trim|xss_clean');
        $rules['type'] = array('type', 'required|trim|xss_clean|callback__check_type');
        $rules['number'] = array('number', 'required|trim|xss_clean|is_natural_no_zero');
        $rules['pre_voucher'] = array('pre_voucher', 'trim|xss_clean|max_length[3]');

        $rules['expired'] = array('expired', 'required|trim|xss_clean|callback__check_date');
        $rules['admin_id'] = array('apply_for_admin', 'trim|xss_clean|callback__check_admin');
        $rules['user_id'] = array('apply_for_user', 'trim|xss_clean|callback__check_user');
        //$rules['product_id'] = array('apply_for_product', 'trim|xss_clean|callback__check_product');
        //$rules['lesson_id'] = array('apply_for_lesson', 'trim|xss_clean|callback__check_lesson');

        $type = $this->input->post('type');
        if ($type == 'vip') {
            $rules['time'] = array('time', 'required|trim|xss_clean|is_natural_no_zero');
        } elseif ($type == 'coupon') {
            $discount_type = $this->input->post('discount_type');
            $rules['discount_type'] = array('discount_type', 'required|trim|xss_clean|is_natural_no_zero');


            if($discount_type ==1)// co dinh
                $rules['discount'] = array('discount', 'required|trim|xss_clean|numeric');

            else //%
                $rules['discount'] = array('discount', 'required|trim|greater_than[0]|less_than[100]|xss_clean|numeric');


        } elseif ($type == 'buyout') {

            //$rules['buyout'] = array('buyout', 'trim|xss_clean|callback__check_buyout');
            if($this->input->post('buyout_apply') ==1)
            $rules['product_id'] = array('apply_for_product', 'trim|required|xss_clean|callback__check_product');
            else
            $rules['lesson_id'] = array('apply_for_lesson', 'trim|required|xss_clean|callback__check_lesson');
        }

        $this->form_validation->set_rules_params($params, $rules);

    }
    /**
     * Kiem tra mua tron
     */
    function _check_buyout()
    {
        $products =$this->input->post('product_id');
        $lessons =$this->input->post('lesson_id');

        if (!$products || !$lessons) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_buyout_value_require'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra tinh hop le
     */
    function _check_product($value)
    {
        if (!model('product')->check_id($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Kiem tra tinh hop le
     */
    function _check_lesson($value)
    {
        if (!model('lesson')->check_id($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Kiem tra tinh hop le cua ype
     */
    function _check_type($value)
    {
        if (!in_array($value,$this->data['types'])) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra tinh hop le cua exp date
     */
    function _check_date($value)
    {
        if (get_time_from_date($value) <= now()) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Kiem tra email nay co ton tai hay khong
     */
    function _check_user($v)
    {
        if(!$v)
            return TRUE;
        if (!$this->_get_user_id())
        {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Lay gia tri cua user_id
     */
    function _get_user_id()
    {
        $email = strval($this->input->post('user_id'));
        $where = array();
        $where['email'] = $email;
        $id = model('user')->get_id($where);
        return $id?$id:0;

    }
    /**
     * Kiem tra email nay co ton tai hay khong
     */
    function _check_admin($v)
    {
        if(!$v)
            return TRUE;
        if (!$this->_get_admin_id())
        {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }
    function _get_admin_id()
    {
        $id = strval($this->input->post('admin_id'));

        $where = array();
        $where['username'] = $id;
        $id = model('admin')->get_id($where);
        //pr_db();
        return $id?$id:0;
    }
    /*
            * ------------------------------------------------------
            *  Prepare data handle
            * ------------------------------------------------------
            */

    function _fields()
    {
        $fields = array('type', /*'name',*/ 'comment', 'expired', 'admin_id', 'user_id',/* 'lesson_id', 'product_id'*/);
        return $fields;
    }

    function _fields_setting($key)
    {
        $fields = array();
        //===== giam gia
        $fields['coupon'] = array('discount', 'discount_type','product_id','lesson_id');
        // VIP gia han vip
        $fields['vip'] = array('time');
        // mua dut
        $fields['buyout'] = array('product_id','lesson_id');
        return isset($fields[$key]) ? $fields[$key] : null;

    }
    function _params_setting($key)
    {
        $params = array();
        // mua dut
        $params['buyout'] = array('buyout');
        return isset($params[$key]) ? $params[$key] : null;

    }
    protected function _get_params()
    {

        $params = $this->_fields();
        $key = $this->input->post('type');
        if ($key) {
            $fields_setting = $this->_fields_setting($key);
            if ($fields_setting)
                $params = array_merge($params, $fields_setting);

            $params_setting = $this->_params_setting($key);
            if ($params_setting)
                $params = array_merge($params, $params_setting);
        }

        return $params;
    }

    /**
     * Lay input
     */
    protected function _get_inputs()
    {
        $data = array();
        $fields = $this->_fields();
        foreach ($fields as $f) {
            $v = $this->input->post($f);
            $data[$f] = $v;
        }

        if ($settings = $this->_fields_setting($data['type'])) {
            $tmp = [];
            foreach ($settings as $f) {
                $v = $this->input->post($f);
                $tmp[$f] = $v;
            }
            if($data['type'] == 'buyout'){
                // chi cho chon 1 trong 2, reset lai cai kia
                if($this->input->post('buyout_apply') == 1)
                    $tmp['lesson_id'] = '';
                else
                    $tmp['product_id'] ='';

            }
            $data['setting'] = json_encode($tmp);
        }
        $data['expired'] = get_time_from_date($data['expired']);
        $data['admin_id'] = $this->_get_admin_id();
        $data['user_id'] = $this->_get_user_id();
       // pr($data['setting'] );
        return $data;
    }

    /**
     * Tao data gui den view
     *
     * @param int $id
     */
    protected function _create_view_data()
    {


        $this->data['action'] = current_url();
        $this->data['url_search_user'] = admin_url('user/ac');
        $this->data['url_search_admin'] = admin_url('admin/ac');

    }
    /*
     * ------------------------------------------------------
     *  Actions
     * ------------------------------------------------------
     */
    /**
     * get_form
     */

    public function get_form()
    {

        $id = $this->input->post('id', TRUE);
        $type= $this->input->post('type', TRUE);

        if (!$type) {
            return;
        }

        if ($id) {
            $info = $this->_mod()->get_info($id);
            if ($info->type == $type)
                $this->data['setting'] = json_decode($info->setting);
        }

        if($type == 'coupon' || $type == 'buyout'){
            $this->data['products'] = model('product')->get_list();
            $this->data['lessons'] = model('lesson')->get_list();

        }
        echo view('tpl::voucher/type/' . $type, $this->data, true);

    }

    /**
     * Them moi
     */
    public function add()
    {
        $form = array();
        $params = $this->_get_params();
        array_push($params, 'number');
        array_push($params, 'pre_voucher');
        $form['validation']['params'] =$params;

        $form['submit'] = function () {
            // Lay input
            $data = $this->_get_inputs();
            $data['created'] = now();
            $data['status'] = config('voucher_status_unused', 'main');
            $key = $this->input->post('pre_voucher');
            $randoms=[];
            // Them du lieu vao data
            for ($i = 1; $i <= $this->input->post('number'); $i++) {
                // usleep(1 * 1000000);
                // $random = rand(111111, 999999) . rand(999999, 111111);
                $random=$key . random_string('numeric',3).now().random_string('numeric',3);
                $random = strtoupper($random);
                $randoms[]=  $random;
            }
            array_unique($randoms);
            if($randoms){
                foreach($randoms as $random){
                    $data['key'] =  $random;
                    $this->_model()->create($data);
                }
                set_message(lang('notice_add_success'));
            }
            return $this->_url();
        };

        $form['form'] = function () {
            $this->_create_view_data();
            $this->_display('form');
        };

        $this->_form($form);
    }
    /**
     * Chinh sua
     */
    protected function _edit($info)
    {
        $info = $this->_mod()->add_info($info, 1);
        $this->data['info'] = $info;
        //pr($info);
        // Form
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($info) {
            $data = $this->_get_inputs();
            $this->_model()->update($info->id, $data);
            set_message(lang('notice_update_success'));
            return $this->_url();
        };

        $form['form'] = function () use ($info) {
            $this->_create_view_data($info);
            $this->_display('form');
        };

        $this->_form($form);
    }

    /**
     * Test
     */
    function _view($info)
    {
        $info->setting= json_decode($info->setting);
        $this->data['info'] = $info ;
        $this->_display('view');
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->_model()->del($info->id);
        // Gui thong bao
        set_message(lang('notice_del_success'));
    }

    /*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */
    /**
     * Thuc hien tuy chinh
     */
    function action()
    {
        // Lay input
        $action = $this->uri->rsegment(3);
        $id = $this->uri->rsegment(4);
        $id = (!is_numeric($id)) ? 0 : $id;

        // Xu ly thuc hien action list
        if ($action == '_list') {
            $action = $this->input->post('action');
            $ids = $this->input->post('ids');
            $this->_action_list($action, $ids);
        }

        // Kiem tra id
        $info = $this->_model()->get_info($id);
        if (!$info) {
            $this->session->set_flashdata('flash_message', array('warning', lang('notice_page_not_found')));
            redirect_admin('voucher');
        }

        // Chuyen den ham duoc yeu cau
        $this->{'_' . $action}($info);
    }

    /**
     * Thuc hien hanh dong voi danh sach
     */
    function _action_list($action, $ids)
    {
        $result = array();

        // Thuc hien hanh dong
        $ids = (!is_array($ids)) ? array() : $ids;
        foreach ($ids as $id) {
            // Lay thong tin
            $info = $this->_model()->get_info($id);
            if (!$info) {
                continue;
            }

            // Chuyen den ham duoc yeu cau
            $this->{'_' . $action}($info);
        }

        // Khai bao du lieu tra ve
        if (count($ids)) {
            $result['complete'] = TRUE;
        }

        $output = json_encode($result);
        set_output('json', $output);
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
        // Tai cac file thanh phan
        $this->load->helper('site');
        $this->load->helper('form');


        //Lay config
        $statuss = config('status');

        // Lay gia tri cua filter dau vao
        $filter_input = array();
        $filter_fields = array('id', 'type', 'key', 'expired','commission', 'status', 'comment', 'created', 'created_to');

        $filter = $this->_mod()->create_filter($filter_fields, $filter_input);
        $this->data['filter'] = $filter_input;
        // Tao bien filter

        //export
        $input = array();
        if (!$this->input->get('export')) {
            // Lay tong so
            $total = $this->_model()->filter_get_total($filter);
            $page_size = $this->config->item('list_limit', 'main');
            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            // Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }
        $list = $this->_model()->filter_get_list($filter, $input);
        //pr_db();

        $actions = array('view','edit','del',);
        $list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
        foreach ($list as $row) {
            $row = $this->_mod()->add_info($row, 1);
            $row->_status = ($row->status == config('status_off', 'main')) ? 'Chưa sử dụng' : 'Đã sử dụng';
            $row->_expired = get_date($row->expired);
            $row->_created = get_date($row->created);
           // pr( get_date($row->expired,'full'),0);
            $row->_day_left_format = days_left($row->expired,$day_left) ;
            $row->_day_left =$day_left ;
           // pr($row->_day_left,0);
           // echo '<br>';pr($row->_day_left_format);
            foreach ($actions as $action) {
                $row->{'_can_' . $action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_' . $action})) ? TRUE : FALSE;
            }
            if($row->status){
                $row->{'_can_view' } =1;
                $row->{'_can_edit' } =0;
            }
            else{
                $row->{'_can_view' } =0;
                $row->{'_can_edit' } =1;
            }

        }

        $list = admin_url_create_option($list, __CLASS__ . '/action', 'id', $actions);
        $this->data['list'] = $list;


        // Tao query chia trang
        $pages_query = array();
        foreach ($filter_input as $f => $v) {
            if (!$v) continue;
            $pages_query[$f] = $v;
        }

        $pages_query_export = $pages_query;
        $pages_query_export['export'] = 1;
        $pages_query_export = http_build_query($pages_query_export);

        $pages_query = http_build_query($pages_query);

        //export
        $this->data['url_export'] = current_url() . '?' . $pages_query_export;
        if ($this->input->get('export')) {
            $this->_export($list);
            return;
            //$this->load->view("admin/voucher/export", $this->data);
        } else {
            // Tao chia trang
            $pages_config = array();
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = admin_url('voucher') . '?' . $pages_query;
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
            $this->data['pages_config'] = $pages_config;

            // Tao action list
            $actions = array();
            foreach (array('del') as $v) {
                $url = admin_url(strtolower(__CLASS__) . '/' . $v);
                if (!admin_permission_url($url)) continue;

                $actions[$v] = $url;
            }
            $this->data['actions'] = $actions;
            // Luu bien gui den view
            $this->data['statuss'] = $statuss;
            $this->data['action'] = current_url();
            $this->data['actions_url'] = admin_url('voucher/action/_list');

            // Breadcrumbs
            $breadcrumbs = array();
            $breadcrumbs[] = array(admin_url('voucher'), lang('mod_voucher'));
            $breadcrumbs[] = array('', lang('list'));
            $this->data['breadcrumbs'] = $breadcrumbs;


            $this->_display();

        }
    }

    function _export($list)
    {
        $headers = array(
            'stt'   => lang('stt'),
            'type'   => lang('type'),
            'key'   => lang('key'),
            'status'   => lang('status'),
            'expired'   => lang('expired'),
        );
        $lists = array();
        $i = 1;
        foreach ($list as $row) {

            $_list = array(
                'stt'   => $i,
                'type'   => $row->type,
                'key'   => $row->key,
                'status'   => $row->status?'đã dùng':'chưa dùng',
                'expired'   =>  $row->_expired.' : ' .(($row->_day_left > 0)?'Còn '.$row->_day_left.' ngày':'Hết hạn'),
            );
            $lists[] = $_list;
            $i++;
        }

        $full_path = 'export/voucher.xlsx';

        write_file($full_path);
        lib('phpexcel')->export($headers, $lists, './'.$full_path);
        // Khai bao du lieu tra ve
        $result['complete'] = TRUE;
        $result['location'] = base_url($full_path);
        set_output('json', json_encode($result));
        // redirect(base_url($full_path));

    }

}