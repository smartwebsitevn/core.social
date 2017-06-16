<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Server extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->lang->load('admin/server');

        //Lay config
        $this->data['server_types'] = mod("product")->config('server_types');
        $this->data['secret_types'] = mod("product")->config('secret_types');
        $this->data['player_types'] = mod("product")->config('player_types');
        $this->data['wowza_streaming_types'] = mod("product")->config('wowza_streaming_types');
        $this->data['nc_streaming_types'] = mod("product")->config('nc_streaming_types');

    }

    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array('test', 'edit', 'del',))) {
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
    function _set_rules($params = array())
    {
        if (!is_array($params)) {
            $params = array($params);
        }

        $rules = array();
        $rules['name'] = array('name', 'required|trim|xss_clean');
        $rules['key'] = array('key', 'required|trim|callback__check_key');
        $rules['player'] = array('player', 'required|trim|callback__check_player');
        $rules['url'] 		= array('url', 'required|trim|xss_clean');
        $rules['sort_order'] = array('sort_order', 'is_natural|less_than[11]');

        if ($this->input->post('key') == 'dedicated') {
            //==dedicated setting
            $rules['ip'] = array('dedicated_ip', 'required|trim|xss_clean');
            $rules['user'] = array('dedicated_user', 'required|trim|xss_clean');
            $rules['password'] = array('dedicated_password', 'required|trim|xss_clean');
            $rules['target_folder'] = array('dedicated_target_folder', 'required|trim|xss_clean');

            if ($this->input->post('secret_status')) {

                $rules['secret_type'] = array('dedicated_secret_type', 'required|trim|callback__check_secret_type');
                $rules['secret'] = array('dedicated_secret', 'required|trim|xss_clean');
                $rules['expire'] = array('dedicated_document_root', 'required|trim|xss_clean|is_natural_no_zero');
                //$rules['document_root'] = array('server_secret', 'required|trim|xss_clean');
                $rules['uri_prefix'] = array('dedicated_uri_prefix', 'required|trim|xss_clean');
                $rules['port'] = array('dedicated_port', 'required|trim|xss_clean');
            }
        }
        if ($this->input->post('key') == 'wowza') {
            //==wowza setting
            $rules['ip'] = array('wowza_ip', 'required|trim|xss_clean');
            $rules['port'] = array('wowza_port', 'required|trim|xss_clean');
            $rules['application'] = array('wowza_application', 'required|trim|xss_clean');
            $rules['streaming'] = array('wowza_streaming', 'required|trim|xss_clean');
            $rules['live'] = array('wowza_live', 'required|trim|xss_clean');
        }
        if ($this->input->post('key') == 'nc') {
            //==wowza setting
            $rules['streaming'] = array('nc_streaming', 'required|trim|xss_clean');
            $rules['secret'] = array('nc_secret', 'required|trim|xss_clean');
            $rules['time'] = array('wowza_live', 'required|trim|xss_clean');
        }
        $this->form_validation->set_rules_params($params, $rules);

    }

    /**
     * Kiem tra gia tri key co hop le
     */
    function _check_key($value)
    {
        if (!in_array($value, $this->data['server_types'])) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_already_exists'));
            return FALSE;
        }

        return TRUE;
    }


    /**
     * Kiem tra player
     */
    function _check_player($value)
    {
        if (!in_array($value, $this->data['player_types'])) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_already_exists'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra gia tri cua ma so nap tien
     */
    function _check_url($value)
    {
        $where = array();
        $where['id !='] = $this->uri->rsegment(4);
        $where['url'] = trim($value, '/');
        $id = $this->_model()->get_id($where);
        if ($id) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_already_exists'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra loai ma hoa
     */
    function _check_secret_type($value)
    {
        if (!in_array($value, $this->data['secret_types'])) {
            $this->form_validation->set_message(__FUNCTION__, $this->lang->line('notice_already_exists'));
            return FALSE;
        }

        return TRUE;
    }

    public function get_form()
    {
        $id = $this->input->post('id', TRUE);
        $key = $this->input->post('key', TRUE);

        if (!$key) {
            return;
        }
        if ($id) {
            $info= $this->_mod()->get_info($id);
            if($info->key == $key)
                 $this->data['setting'] = $info->setting;
        }

        echo $this->load->view('admin/server/server/' . $key, $this->data, true);

    }

    /*
            * ------------------------------------------------------
            *  Prepare data handle
            * ------------------------------------------------------
            */

    function _fields()
    {
        $fields = array(
            'name', 'key', 'player', 'player_mobile', 'url', 'sort_order',
            'status',
        );
        return $fields;
    }

    function _fields_setting($key)
    {
        $fields = array();
        //===== Dedicate
        $fields['dedicated'] = array('ip','port', 'user', 'password',
            'secret_status', 'secret_type', 'secret', 'expire', 'target_folder',  'uri_prefix', /*'document_root'*/);

        $fields['wowza'] = array(/*'ip',*/'port','application', 'streaming', 'token', 'query_prefix',);
        $fields['nc'] = array( 'streaming', 'secret', 'time',);
        return isset($fields[$key]) ? $fields[$key] : null;


    }

    /**
     * Lay id xu ly hien tai
     *
     * @return int
     */
    protected function _get_id_cur()
    {
        return ($this->uri->rsegment(2) == 'add')
            ? fake_id_get($this->_get_mod())
            : $this->uri->rsegment(3);
    }

    protected function _get_params()
    {
        $params = $this->_fields();
        //array_push($params,'image', 'banner');
        $key = $this->input->post('key');
        if ($key) {
            $setting_param = $this->_fields_setting($key);
            if($setting_param)
             $params = array_merge($params, $setting_param);
            //pr($params);
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

        if($settings = $this->_fields_setting($data['key'])){
            $tmp=[];
            foreach ($settings as $f) {
                $v = $this->input->post($f);
                $tmp[$f] = $v;
            }
            $data['setting']= json_encode($tmp);
        }

        //pr($data);
        return $data;
    }

    /**
     * Tao data gui den view
     *
     * @param int $id
     */
    protected function _create_view_data($id, $info = null)
    {


        $this->data['action'] = current_url();
        $this->data['url_tag'] = $this->_url('ac_info/tag');


    }

    /**
     * Them moi
     */
    public function add()
    {
        $fake_id = 0;// $this->_get_id_cur();

        $form = array();

        $form['validation']['params'] = $this->_get_params();

        $form['submit'] = function () use ($fake_id) {

            // Lay input
            $data = $this->_get_inputs();
            $data['created'] = now();
            // Cap nhat vao data
            $id = 0;
            $this->_model()->create($data, $id);
            set_message(lang('notice_add_success'));
            return $this->_url();
        };

        $form['form'] = function () use ($fake_id) {
            $this->_create_view_data($fake_id);
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
            $this->_create_view_data($info->id, $info);
            $this->_display('form');
        };

        $this->_form($form);
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->_model()->del($info->id);
        // Gui thong bao
        $this->session->set_flashdata('flash_message', array('success', $this->lang->line('notice_del_success')));
        return TRUE;
    }

    /**
     * Test
     */
    function _test($info)
    {

        $file ='sample.mp4';
        switch($info->key){
            case 'wowza':
               $link = mod("product")->process_link_wowza($file, $info);
                break;
            case 'nc':
                $link = mod("product")->process_link_nc($file, $info);
                break;
        }
        $this->data['link'] = $link ;
        $this->_display();
    }
    /**
     * Thuc hien tuy chinh
     */
    protected function _action($action)
    {
        // Lay input

        $ids = $this->uri->rsegment(3);
        $ids = ( ! $ids) ? $this->input->post('id') : $ids;

        // Thuc hien action
        foreach ((array) $ids as $id)
        {
            // Xu ly id
            $id = ( ! is_numeric($id)) ? 0 : $id;

            // Kiem tra id
            $info = $this->_model()->get_info($id);
            if ( ! $info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            //if ( ! $this->_mod()->can_do($info, $action)) continue;

            // Chuyen den ham duoc yeu cau
            $this->{'_'.$action}($info);
        }
    }




    /**
     * Danh sach
     */
    public function index()
    {
        $list = array();
        $list['page'] = false;
        $list['sort'] = true;
        $list['display'] = false;
        $this->_list($list);
        foreach($this->data['list'] as $row){
            if(in_array($row->key,array('wowza','nc'))){
                $row->_can_test =1;
                $row->_url_test =$this->_url('test/'.$row->id);
            }
        }

        $this->_display();
    }


}