<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File_adv extends MY_Controller
{
    var $uploader;

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->load->helper('file');
        $this->load->model('file_model');
        $this->lang->load('admin/file');
        require_once APPPATH . 'libraries/UploadHandler.php';
        $this->uploader = new UploadHandler();
    }

    /**
     * Thuc hien upload file
     */
    function upload()
    {

        //== Tien hanh upload
        switch ($this->uploader->get_server_var('REQUEST_METHOD')) {
            case 'GET':
                $this->_get();
                break;
            case 'PATCH':
            case 'PUT':
            case 'POST':
                $this->_upload();
                break;
            case 'DELETE':
                $this->_del();

                break;
        }


    }

    function _upload()
    {
        // Kiem tra ma bao mat
        /*$params = array('mod', 'file_type', 'allowed_types', 'status', 'server', 'table', 'table_id', 'table_field',
            'resize', 'resize_width', 'resize_height', 'thumb', 'thumb_width', 'thumb_height', 'field',
        );
        if ( ! security_check_query($params, 'upload'))
        {
            $output = json_encode(array());
            set_output('json', $output);
        }
        // Kiem tra file
        if ( ! isset($_FILES['file']['name']))
        {
            $output = json_encode(array());
            set_output('json', $output);
        }*/
        // Xoa cac file tam thoi
        //file_del_temporary();

        if ($_FILES) {
            $rs = $this->uploader->post();
            t('session')->set_userdata('__file_adv_upload', $rs);

            return;

        }
        $completed =$this->input->get('completed') ;
        if ($completed == 'true') {
            $rs = t('session')->userdata('__file_adv_upload');
           // t('session')->unset_userdata('__file_adv_upload');
            if (!isset($rs['files']) || !$rs['files'])
                return;
            // cap nhap vao DB
            // Lay cac bien dau vao
            $mod = $this->input->get('mod');
            $file_type = $this->input->get('file_type');
            $allowed_types = $this->input->get('allowed_types');
            $status = $this->input->get('status');
            $server = $this->input->get('server');
            $table = $this->input->get('table');
            $table_id = $this->input->get('table_id');
            $table_field = $this->input->get('table_field');
            $field = $this->input->get('field');
            // Xu ly bien
            $status = ($status != config('file_public', 'main')) ? config('file_private', 'main') : $status;
            $field = (!$field) ? 'file' : $field;
            $server = config(($server) ? 'verify_yes' : 'verify_no', 'main');


            // Neu che do la single file thi xoa cac file cu
            if ($mod == 'single') {
                $input = array();
                $input['where']['table'] = $table;
                $input['where']['table_id'] = $table_id;
                $input['where']['table_field'] = $table_field;
                $list_file = model('file')->get_list($input);
                foreach ($list_file as $file) {
                    file_del($file);
                }
            }
            foreach ($rs['files'] as $file) {
                //pr($file);
                // Them vao table file
                $data = array();
                $data['url'] = $file->url;
                $data['url_thumb'] = isset($file->thumbnailUrl) ? $file->thumbnailUrl : '';
                $data['url_delete'] = $file->deleteUrl;
                $data['size'] = $file->size;
                $data['type'] = $file->type;
                $data['extension'] = $file->type;
                $data['file_name'] = $file->name;
                $data['orig_name'] = $file->name;
                $data['status'] = $status;
                $data['server'] = $server;
                $data['table'] = $table;
                $data['table_id'] = $table_id;
                $data['table_field'] = $table_field;
                $data['created'] = now();
                // pr($data);
                model('file')->create($data);
            }
        }
    }

    /**
     * Lay danh sach file
     */
    function _get()
    {
        // Cap nhat sort_order
        if ($this->input->get('act') == 'update_order') {
            $items = $this->input->post('items');
            $items = explode(',', $items);
            foreach ($items as $i => $id) {
                model('file')->update_field($id, 'sort_order', $i + 1);
            }

            $output = json_encode(array('complete' => TRUE));
            set_output('json', $output);
        }


        // Lay gia tri dau vao
        $table = $this->input->get('table');
        $table_id = $this->input->get('table_id');
        $table_field = $this->input->get('table_field');
        $file_type = $this->input->get('file_type');

        // Lay danh sach file
        $list = model('file')->get_list_of_mod($table, $table_id, $table_field);
        // pr_db($list);
        $files = [];

        $config = config('upload', 'main');
        $folder = config('file', 'main');
        $folder = $folder[0];
        $path_thumb = $config['folder'] . '/' . $folder . '/thumbnail/';
        foreach ($list as $row) {
            $row = file_add_info($row);
            $file = new stdClass();
            $file->name = $row->file_name;
            $file->size = (int)$row->size;
            $file->type = $row->type;
            $file->url = $row->_url;
            // $file->thumbnailUrl = $row->_url_thumb;
            $file->thumbnailUrl = base_url() . $path_thumb . $file->name;
            // $file->thumbnailUrl = file_get_url_name_fix(base_url($path_thumb.  $file->name), 'thumb');
            $file->deleteUrl = admin_url('file_adv/upload/') . '?file=' . $file->name . '&id=' . $row->id;
            $file->deleteType = "DELETE";
            $files[] = $file;
        }


        // $this->uploader->get();
        $this->uploader->generate_response(['files' => $files]);

    }


    /**
     * Xoa file
     */
    function _del()
    {
        $this->uploader->delete();
        $file_id = $this->input->get('id');
        if (!file_del($file_id)) {
            set_message(lang('notice_page_not_found'));
        }

        set_message(lang('notice_del_success'));
    }

    /**
     * Download file
     */
    function download()
    {
        $file_id = $this->uri->rsegment(3, '0');
        if (!file_download($file_id)) {
            set_message(lang('notice_page_not_found'));
            redirect_admin();
        }
    }

}