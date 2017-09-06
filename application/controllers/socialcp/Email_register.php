<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email_register extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->load->model('email_register_model');
        //$this->lang->load('admin/email_register');
    }

    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('del'));
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
        $this->load->helper('form');

        // Tao filter
        $filter_input = array();
        $filter_fields = array('id', 'email', 'created', 'created_to');
        $filter = $this->email_register_model->filter_create($filter_fields, $filter_input);
        $this->data['filter'] = $filter_input;

        // Lay tong so
        $total = $this->email_register_model->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = max(0, min($limit, get_limit_page_last($total, $page_size)));
        // Lay danh sach
        $input = array();
        $input['limit'] = array($limit, $page_size);
        $list = $this->email_register_model->filter_get_list($filter, $input);

        $actions = array('del');
        $list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
        foreach ($list as $row) {
            $row->_created = get_date($row->created);

            foreach ($actions as $action) {
                $row->{'_can_' . $action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_' . $action})) ? TRUE : FALSE;
            }
        }
        //export
        $filter_input_export = $filter_input;
        $filter_input_export['export'] = 1;
        $this->data['url_export'] = current_url() . '?' . url_build_query($filter_input_export);
        if ($this->input->get('export')) {
            $this->_export($list);
            return;
        } else {
            $this->data['list'] = $list;

            // Tao chia trang
            $pages_config = array();
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
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
            $this->data['action'] = current_url();
            $this->data['verify'] = config('verify', 'main');

            // Breadcrumbs
            $breadcrumbs = array();
            $breadcrumbs[] = array(admin_url('email_reagister'), lang('mod_email_register'));
            $breadcrumbs[] = array(current_url(), lang('list'));
            $this->data['breadcrumbs'] = $breadcrumbs;

            // Hien thi view
            $this->_display();
        }
    }

    function _export($list)
    {
        $headers = array(
            'stt' => lang('stt'),
            'email' => lang('email'),
            'created' => lang('created'),
        );
        $lists = array();
        $i = 1;
        foreach ($list as $row) {
            $_list = array(
                'stt' => $i,
                'email' => $row->email,
                'created' => $row->_created,
            );
            $lists[] = $_list;
            $i++;
        }
        // pr($lists);
        $full_path = 'export/email_register.xlsx';
        write_file($full_path);
        lib('phpexcel')->export($headers, $lists, './' . $full_path);
        // Khai bao du lieu tra ve
        $result['complete'] = TRUE;
        $result['location'] = base_url($full_path);
        set_output('json', json_encode($result));
        // redirect(base_url($full_path));

    }

    /*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */
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
            $info = $this->email_register_model->get_info($id);
            if (!$info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if (!$this->_can_do($info, $action)) continue;

            // Chuyen den ham duoc yeu cau
            $this->{'_' . $action}($info);
        }
    }

    /**
     * Kiem tra co the thuc hien hanh dong hay khong
     */
    function _can_do($info, $action)
    {
        switch ($action) {
            case 'view':
            case 'del': {
                return TRUE;
            }
        }

        return FALSE;
    }


    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->email_register_model->del($info->id);

        // Gui thong bao
        set_message(lang('notice_del_success'));
    }

}