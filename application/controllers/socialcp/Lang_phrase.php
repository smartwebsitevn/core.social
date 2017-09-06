<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lang_phrase extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->load->model('lang_phrase_model');
        $this->lang->load('admin/lang');

    }

    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array( 'del'));
    }


    /*
     * ------------------------------------------------------
     *  Rules params
     * ------------------------------------------------------
     */
    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params)
    {
        $rules = array();
        $rules['lang'] = array('name', 'callback__check_lang');
        $rules['phrases'] = array('phrase', 'callback__check_phrases');
        $rules['file'] = array('phrase', 'callback__check_file');
        $rules['key'] = array('key', 'required');
        $rules['value'] = array('value', 'required|trim|xss_clean');

        $this->form_validation->set_rules_params($params, $rules);
    }
    /**
     * Check key
     */
    function _check_key($value)
    {
        if ($this->_model()->check_exits(array('key'=>$value))) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_exists','Key ['.$value.'] '));
            return FALSE;
        }

        return TRUE;
    }
    /**
     * Check lang
     */
    function _check_lang($value)
    {
        if (!model('lang')->check_id($value)) {
            exit('die');
        }

        return TRUE;
    }

    function _check_file()
    {
        //== Kiem tra dinh dang file
        $langs = $this->data['langs'];

        $file = $_FILES['file'];
        $dot  = strrpos($file['name'], '.') + 1;
        $file_type_ext = strtolower(substr($file['name'], $dot));
        //echo   $file_type_ext;
        if (!in_array($file_type_ext, array("xls", "xlsx"))) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        if (!$file['error'] == UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        
        $list = lib('phpexcel')->read_file($file['tmp_name']);
        if(empty($list))
        {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        //lay vi tri cua lang trong file excel
        $i = 4; //vi tri bat dau cua ngon ngu dau tien
        $langs_keys = array();
        foreach ($langs as $l)
        {
            $langs_keys[$l->id]  = $i;
            $i++;
        }
        $phrases = array();


       // pr($langs_keys);
         $files_lang =[];
        foreach ($list as $row)
        {
            if(count($row) != (count($langs) + 4)) break;
            $tranlates = array();
            $file=null;
            // kiem tra file lang co trong csdl hay khong
            if(!isset($files_lang[$row[2]])){
                $f = model("lang_file")->get($row[2]);
                if($f ) {
                    $files_lang[$row[2]]=$f;
                    $file =$f;
                };
            }
            else{
                $file=  $files_lang[$row[2]];
            }
            if(!$file) continue;
            // kiem tra file id va key nay co trong csdl hay khong
            $key_phrase = model("lang_phrase")->get_info_rule(["file_id"=>$file->id,'key'=>$row[3]]);
            //pr_db();
            if(!$key_phrase)
                continue;

            // neu co thi tien hanh luu lai de import
            foreach ($langs as $l)
            {
                $tranlates[$l->directory] = $row[$langs_keys[$l->id]];
            }
           // $phrases[$row[1]] = $tranlates;
            $phrases[$key_phrase->id] = $tranlates;

        }

        $this->data['_phrases'] = $phrases;
        return true;
    }
    function _check_file_()
    {
        //== Kiem tra dinh dang file
        $langs = $this->data['langs'];

        $file = $_FILES['file'];
        $dot  = strrpos($file['name'], '.') + 1;
        $file_type_ext = strtolower(substr($file['name'], $dot));
        //echo   $file_type_ext;
        if (!in_array($file_type_ext, array("xls", "xlsx"))) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        if (!$file['error'] == UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        $list = lib('phpexcel')->read_file($file['tmp_name']);
        if(empty($list))
        {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        //lay vi tri cua lang trong file excel
        $i = 4; //vi tri bat dau cua ngon ngu dau tien
        $langs_keys = array();
        foreach ($langs as $l)
        {
            $langs_keys[$l->id]  = $i;
            $i++;
        }
        $phrases = array();
        foreach ($list as $row)
        {
            if(count($row) != (count($langs) + 4)) break;
            $tranlates = array();
            foreach ($langs as $l)
            {
                $tranlates[$l->directory] = $row[$langs_keys[$l->id]];
            }
             $phrases[$row[1]] = $tranlates;

        }

        $this->data['_phrases'] = $phrases;
        return true;
    }
    /**
     * Check cac phrase
     */
    function _check_phrases()
    {
        if (!$this->_get_phrases()) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_phrases_required'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Lay cac phrase translate
     */
    function _set_phrases($phrases,$values=array())
    {
        $langs = $this->data['langs'];
 
        $lang_file_updated = array();
        // Cap nhap ban dich vao CSDL
        foreach ($phrases as $phrase_id => $t) {
            // Lay thong tin ban dich
            $phrase = model('lang_phrase')->get_info($phrase_id);
            if(!$phrase)
                continue;
            $lang_file_updated[] = $phrase->file_id;
            $data = array();
            // if (isset($values[$phrase_id]))
            //   $data['value'] = $values[$phrase_id];
            //$data['translate'] = $t;
            foreach ($langs as $l)
            {
                $data[$l->directory] = isset($t[$l->directory]) ? $t[$l->directory] : '';
            }
            model('lang_phrase')->update($phrase_id, $data);
        }
        if(!$lang_file_updated)
            return;
        // loc bo cac file trung nhau
        $lang_file_updated = array_unique($lang_file_updated);
        // Lay cac file co su thay doi ban dich va luu lai cache
        foreach ($lang_file_updated as $file_id) {
            // lay thong tin file
            $file = model('lang_file')->get_info($file_id);
            foreach ($langs as $lang)
            {
                $lang_phrases = model('lang_phrase')->lang_get_translates($lang->directory, $file_id);
                lang_set_cache($lang->directory, $file->file, $lang_phrases);
            }
        }
    }
    /**
     * Lay cac phrase translate
     */
    function _get_phrases()
    {
        $phrases = $this->input->post('phrases');
        $result = array();
        foreach ($phrases as $phrase_id => $t) {
            if (empty($t))
                return false;

            if (!model('lang_phrase')->check_id($phrase_id))
                return false;

            $result[$phrase_id] = $t;
        }
        return $result;
    }

    /**
     * Lay cac value goc
     */
    function _get_values()
    {

        $phrases = $this->input->post('values');
        if (!$phrases)
            return;
        $result = array();
        foreach ($phrases as $phrase_id => $t) {
            $result[$phrase_id] = $t;
        }
        return $result;
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
     * Kiem tra co the thuc hien hanh dong hay khong
     */
    function _can_do($info, $action)
    {
        switch ($action) {
            case 'sync':
            case 'add_key':
            case 'edit':
            case 'phrase':
            {
                return TRUE;
            }
            case 'del': {
                // kiem tra neu file thuoc additional lang moi cho xoa
                if($info->_file->file == 'additional_lang.php')
                    return TRUE;
                else
                    return FALSE;
            }

        }

        return FALSE;
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

            $info->_file = model('lang_file')->get_info($info->file_id);
            // Kiem tra co the thuc hien hanh dong nay khong
            if ( !$this->_can_do($info, $action)) continue;

            // Chuyen den ham duoc yeu cau
            $this->{'_'.$action}($info);
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
        // Get
        if ($this->input->get('act') == 'export') {
            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;
            $result['location'] = admin_url('lang_phrase/export');
            set_output('json', json_encode($result));
        }

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('site');
        $this->load->helper('form');
        
         $langs = model('lang')->get_list_active();
         $this->data['langs'] = $langs;
         
        // Xu ly form
        if ($this->input->post('_submit')) {

            // Gan dieu kien cho cac bien
            $params = array('phrases');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {

                $lang_id = $this->input->post('lang');
                $phrases = $this->_get_phrases();

                // phuc vu cho chinh sua file lang goc
               // $values = $this->_get_values();
                $this->_set_phrases($phrases);


                // Khai bao ket qua tra ve
                $result['complete'] = TRUE;
                //$result['location'] = admin_url('cat');
                set_message(lang('notice_update_success'));
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            $output = json_encode($result);
            set_output('json', $output);
        }


        // Tao filter
        $filter_input = array();
        $filter_fields = array('file','file_name', 'key', 'value', 'translate','translate_empty', 'created', 'created_to');
        $filter = $this->lang_phrase_model->filter_create($filter_fields, $filter_input);



        $this->data['filter'] = $filter_input;
        //pr($filter_input);
        // Lay tong so
        $total = $this->lang_phrase_model->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

        // Lay danh sach
        $input = array();
        $input['limit'] = array($limit, $page_size);

        $list = $this->lang_phrase_model->filter_get_list($filter, $input);
        //pr_db();
        $actions = array('phrase', 'sync','del', );
        $list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
        static $files = array();
        foreach ($list as $row) {
            if (!isset($files[$row->file_id]))
                $files[$row->file_id] = model('lang_file')->get_info($row->file_id);

            $row->_file = $files[$row->file_id];
            foreach ($actions as $action) {
                $row->{'_can_' . $action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_' . $action})) ? TRUE : FALSE;

            }
        }
        //pr($list);
        $this->data['list'] = $list;

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;


        // Luu bien gui den view
        $this->data['action'] = current_url();

        
        $this->data['url_export'] = admin_url('lang_phrase') . '?act=export';
        $this->data['url_import'] = admin_url('lang_phrase/import');
        $this->data['url_add'] = admin_url('lang_phrase/add');

        // Breadcrumbs
        /*$breadcrumbs = array();
        $breadcrumbs[] = array(admin_url('page'), lang('mod_page'));
        $breadcrumbs[] = array(current_url(), lang('list'));
        $this->data['breadcrumbs'] = $breadcrumbs;*/

        // Hien thi view
        $this->_display();
    }

    /**
     * Export Danh sach
     */
    function export()
    {
        
       
        $langs = model('lang')->get_list_active();
        $this->data['langs'] = $langs;
        
        $headers = array(
            'stt'   => lang('stt'), 
            'id'    => lang('id'), 
            'file'  => lang('file'),
            'key'   => lang('key'),
        );
      
        foreach ($langs as $l)
        {
            $headers[$l->directory]  = $l->name;
        }
        
        // Lay danh sach
        $filter = array();
        $input = array();
        //$input['limit'] = array(0, 20);
        $input['order'] = array('file_id,key', 'asc');
        $list = $this->lang_phrase_model->filter_get_list($filter, $input);
        $lists = array();
        static $files = array();
        $i = 1;
        foreach ($list as $row) {
            if (!isset($files[$row->file_id]))
                $files[$row->file_id] = model('lang_file')->get_info($row->file_id, 'file');
        
            $row->_file = $files[$row->file_id];
        
            $_list = array(
                'stt'   => $i, 
                'id'    => $row->id, 
                'file'  => isset($row->_file->file) ? $row->_file->file : '',
                'key'   => $row->key,
            );
            foreach ($langs as $l)
            {
                $_list[$l->directory]  = isset($row->{$l->directory}) ? $row->{$l->directory} : '';
            }
            $lists[] = $_list;
            $i++;
        }
        $full_path = 'export/lang_phrase.xlsx';

        write_file($full_path);
        lib('phpexcel')->export($headers, $lists, './'.$full_path);
        redirect(base_url($full_path));
    }

    public function import()
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        $langs = model('lang')->get_list_active();
        $this->data['langs'] = $langs;
        
        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('file');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                $phrases =$this->data['_phrases'];
                //pr($phrases);
                $this->_set_phrases($phrases);
                $result['complete'] = TRUE;
                $result['location'] = admin_url('lang_phrase');
                set_message(lang('notice_import_success'));

            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }
        //==== Render View
        
        $this->_display();
    }
    /**
     * Them moi key
     */
    public function add()
    {
        $form = array();
        $form['validation']['params'] = array('key','value');

        $form['submit'] = function()
        {
            $langs = model('lang')->get_list_active();
            // lay id file additional_lang.php
            $file= model('lang_file')->get_info_rule(array('file'=>'additional_lang.php'));
            if(!$file){
                set_message(lang('notice_can_not_do'));
                $this->_url();
            }
            // Lay input
            $data = array();
            $data['file_id'] = $file->id;
            $data['key'] = $this->input->post('key');
            $data['value'] = $this->input->post('value');
            //$data['translate'] =   $data['value'];
            foreach($langs as $lang){
                //$data['lang_id'] =   $lang->id;
                $data[$lang->directory] = $data['value'];
            }
            $this->_model()->create($data);

            foreach($langs as $lang){
                // luu ban dich vao cache
                $lang_phrases = model('lang_phrase')->lang_get_translates($lang->directory, $file->id);
                lang_set_cache($lang->directory, $file->file, $lang_phrases);
            }

            set_message(lang('notice_add_success'));

            return $this->_url();
        };

        $form['form'] = function()
        {
            $this->_display();
        };

        $this->_form($form);
    }
    
    /**
     * Xoa
     */
    protected function _del($info)
    {
        // khi xoa 1 key  ta se xoa dong thoi o cac ngon ngu khac
        $this->_model()->del_rule(array('file_id'=>$info->file_id, 'key'=>$info->key,));
        $langs = model('lang')->get_list_active();
        // cap nhap lai cache
        foreach($langs as $lang){
            $lang_phrases = model('lang_phrase')->lang_get_translates($lang->directory, $info->file_id);
            if($lang_phrases)
            {
                lang_set_cache($lang->directory, $info->_file->file, $lang_phrases);
            }
        }
        set_message(lang('notice_del_success'));
    }

}