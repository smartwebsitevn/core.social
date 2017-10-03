<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File extends MY_Controller
{

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
    }

    /**
     * Thuc hien upload file
     */
    function upload()
    {
        // Kiem tra ma bao mat
        $params = array('mod', 'file_type', 'allowed_types', 'status', 'server', 'table', 'table_id', 'table_field',
            'resize', 'resize_width', 'resize_height', 'thumb', 'thumb_width', 'thumb_height', 'field',
        );
        if (!security_check_query($params, 'upload')) {
            $output = json_encode(array());
            set_output('json', $output);
        }

        // Kiem tra file
        if (!isset($_FILES['file']['name'])) {
            $output = json_encode(array());
            set_output('json', $output);
        }

        // Xoa cac file tam thoi
        file_del_temporary();


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

        $allowed_types = explode('|', $allowed_types);
        foreach ($allowed_types as $i => $v) {
            $v = trim($v);
            if (
                !$v ||
                preg_match('#[^a-z0-9]#i', $v) ||
                preg_match('#(php|phtml)#i', $v)
            ) {
                unset($allowed_types[$i]);
            }
        }


        // Lay folder upload file
        $folders = config('file', 'main');
        $folder = $folders[$status];

        // Tao config upload
        $config = config('upload', 'main');
        $path_base = $config['path'] . $config['folder'] . '/' . $folder;
        $path = $path_base;//.'/'.$table;
        /*if(!is_dir($path)) {
            $this->load->helper('directory');
            directory_create($path_base,$table);
        }*/
        //pr($config);
        $config['upload_path'] = $path;
        $config['max_size'] = $config['max_size_admin'];
        $config['file_name'] = file_create_new_name($_FILES[$field]['name']);
        $config['allowed_types'] = ($file_type == 'image') ? $config['img']['allowed_types'] : $config['allowed_types'];
        /*if (count($allowed_types)) {
            $config['allowed_types'] = implode('|', $allowed_types);
        }*/

        // Thuc hien upload file
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($field)) {
            // Neu che do la single file thi xoa cac file cu
            if ($mod == 'single') {
                $input = array();
                $input['where']['table'] = $table;
                $input['where']['table_id'] = $table_id;
                $input['where']['table_field'] = $table_field;
                $list_file = $this->file_model->get_list($input);
                foreach ($list_file as $file) {
                    file_del($file);
                }
            }
            // Lay thong tin cua file vua upload
            $upload_data = $this->upload->data();
            // Them vao table file
            $data = array();
            $data['file_name'] = $upload_data['file_name'];
            $data['orig_name'] = $upload_data['client_name'];
            $data['status'] = $status;
            $data['server'] = config(($server) ? 'verify_yes' : 'verify_no', 'main');
            $data['table'] = $table;
            $data['table_id'] = $table_id;
            $data['table_field'] = $table_field;
            //$data['user_id'] 	= 1;

            $data['size'] = $upload_data['file_size'];
            $data['type'] = $upload_data['file_type'];
            $data['extension'] = substr($upload_data['file_ext'], -3);
            $data['is_image'] = $upload_data['is_image'];
           /* if ($data['is_image']) {
                $data_image = ['width' => $upload_data['image_width'], 'height' => $upload_data['image_height'], 'type' => $upload_data['image_type']];
                $data['data'] = json_encode($data_image);
            }*/
            $data['created'] = now();
            //pr($data);
            $this->file_model->create($data);

            if ($data['is_image']) {
                //  $table
                // Tao thumb cho hinh anh
                if ($this->input->get('thumb')) {
                    $thumb_size = array();
                    $thumb_size['width'] = $this->input->get('thumb_width');
                    $thumb_size['height'] = $this->input->get('thumb_height');
                    $thumb_size = (!$thumb_size['width']) ? array() : $thumb_size;
                    file_create_thumb($upload_data['full_path'], $thumb_size);
                }

                // Resize hinh anh
                if ($this->input->get('resize')) {
                    $resize_size = array();
                    $resize_size['width'] = $this->input->get('resize_width');
                    $resize_size['height'] = $this->input->get('resize_height');
                    $resize_size = (!$resize_size['width']) ? array() : $resize_size;
                    file_resize($upload_data['full_path'], $resize_size, TRUE);

                }
            }

            // Chuyen file len server luu tru
            if ($server) {
                $name_fix = array();
                if ($this->input->get('thumb')) {
                    array_push($name_fix, 'thumb');
                }

                file_upload_server($upload_data['file_name'], $status, $name_fix);
            }

            // Khai bao du lieu tra ve
            $output = json_encode(array('complete' => TRUE));
            set_output('json', $output);
        }

        $output = json_encode(array());
        set_output('json', $output);
    }

    /**
     * Lay danh sach file
     */
    function index()
    {
        // Cap nhat sort_order
        if ($this->input->get('act') == 'update_order') {
            $items = $this->input->post('items');
            $items = explode(',', $items);
            foreach ($items as $i => $id) {
                $this->file_model->update_field($id, 'sort_order', $i + 1);
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
        $list = $this->file_model->get_list_of_mod($table, $table_id, $table_field);
        foreach ($list as $row) {
            $row = file_add_info($row);
            $row->_url_modify = admin_url('media/modify') . '?image=' . $row->file_name;
        }
        $list = admin_url_create_option($list, __CLASS__, 'id', array('del'));
        //pr($list);
        $this->data['list'] = $list;

        // Luu cac bien gui den view
        $this->data['message'] = get_message();
        $this->data['url_update_order'] = current_url() . '?act=update_order';
        $this->data['sort'] = (int)$this->input->get('sort');

        // Hien thi view
        $temp = ($file_type == 'image') ? 'index_image' : 'index';
        $this->load->view('admin/file/' . $temp, $this->data);
    }

    /**
     * Lay thong tin cua 1 file
     */
    function get()
    {
        // Lay gia tri dau vao
        $table = $this->input->get('table');
        $table_id = $this->input->get('table_id');
        $table_field = $this->input->get('table_field');

        // Lay thong tin cua file
        $where = array();
        $where['table'] = $table;
        $where['table_id'] = $table_id;
        $where['table_field'] = $table_field;
        $info = $this->file_model->get_info_rule($where);
        $info = file_add_info($info);

        $info = (empty($info)) ? new stdClass() : $info;
        $info->id = (isset($info->id)) ? $info->id : 0;
        $info->_url = (isset($info->_url)) ? $info->_url : public_url('img/no_image.png');
        $info->_url_download = admin_url('file/download/' . $info->id);
        $info->_url_del = admin_url('file/del/' . $info->id);
        $info->_url_modify = '';
        if (isset($info->file_name))
            $info->_url_modify = admin_url('media/modify') . '?image=' . $info->file_name;
        // Lay dung luong cua file
        if (isset($info->_path)) {
            $file_info = get_file_info($info->_path);
            $info->_size = (isset($file_info['size'])) ? byte_format($file_info['size']) : '';
        }

        // Loai bo cac bien khong can thiet
        foreach (array('_path', '_path_thumb', 'created', 'status', 'table', 'table_id', 'table_field', 'user_id') as $p) {
            if (isset($info->$p)) {
                unset($info->$p);
            }
        }

        // Tra lai ket qua
        $output = json_encode($info);
        set_output('json', $output);
    }

    /**
     * Xoa file
     */
    function del()
    {
        $file_id = $this->uri->rsegment(3, '0');
        if (!file_del($file_id)) {
            set_message(lang('notice_page_not_found'));
        }

        set_message(lang('notice_del_success'));
        $this->_response();

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