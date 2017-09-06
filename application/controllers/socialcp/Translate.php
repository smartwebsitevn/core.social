<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->load->model('translate_model');
        $this->lang->load('admin/translate');
    }

    /**
     * Remap method
     */
    function _remap($method)
    {
        if (in_array($method, array('index', 'quick'))) {
            $this->_translate($method);
        } elseif (method_exists($this, $method)) {
            $this->{$method}();
        } else {
            show_404('', FALSE);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Dich thong tin row cua table
     */
    function table()
    {
        // Lay input
        $table = $this->uri->rsegment(3);
        $id = $this->uri->rsegment(4);

        // Tai file cua table
        $this->load->model($table . '_model');
        switch ($table) {
            case 'menu':
            case 'menu_item':
                $this->lang->load('admin/menu');
                break;
            case 'news':
                $this->lang->load('admin/menu');
                break;
            case 'default':
                $this->lang->load('admin/' . $table);
                break;
        }


        // Kiem tra id
        $row = $this->{$table . '_model'}->translate_get_info($id);
        if (!$row) {
            redirect_admin();
        }

        // Lay cac field can dich cua table
        $field = $this->{$table . '_model'}->translate_fields;

        // Lay danh sach lang
        $langs = lang_get_list();
        $langs = array_where($langs, function ($i, $lang) {
            return (!$lang->is_default);
        });


        // Hien thi view
        switch ($table) {
            case 'widget':
                $wid = $this->widget_model->get_info($id);
                $wid = (object)$this->widget_model->handle_data_output((array)$wid);

                $module = $wid->module;
                // Lay danh sach cac bien setting
                $setting_params = $this->module->{$module}->widget_get_config();
                $this->data['setting_params'] = $setting_params[$wid->widget]['setting'];

                foreach ($this->data['setting_params'] as $k => $r) {
                    // them vao field ca dich
                    if (isset($r['translate']) && $r['translate']) {
                        $field[] = $k;
                        // neu chua co thi gan ngon ngu vao
                        if (!isset($row->$k)) {
                            $row->$k = $wid->setting[$k];
                        }
                    }
                }
                break;
        }


        // Tai cac file thanh phan
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Lay data
            $data = array();
            foreach ($field as $f) {
                $vs = $this->input->post($f);
                foreach ($vs as $l => $v) {
                    // Loai bo cac ban dich giong ban goc
                    $vs[$l] = (strip_tags($v) == strip_tags($row->$f)) ? '' : $v;
                    if ($f == 'url') {
                        $vs[$l] = convert_vi_to_en($vs[$l]);
                    }
                }
                $data[$f] = $vs;
            }
            //== them vao lang
            if (!model($table)->field_exists('_lang')) {
                //them cot moi
                $this->load->dbforge();
                $fields = array(
                    '_lang' => array('type' => 'VARCHAR', 'constraint' => '20',),
                    //'_lang'  => array('type' => 'longtext','after' => 'another_field'),
                );
                $this->dbforge->add_column($table, $fields);
            }

            $row = model($table)->get_info($id, '_lang');
            if ($row->_lang) {
                $row->_lang = explode(',', $row->_lang);
            } else {
                $row->_lang = array();
            }
            foreach ($langs as $l) {
                $row->_lang[] = $l->directory;
            }
            $row->_lang = array_unique($row->_lang);
            $where = array();
            $where['_lang'] = implode(',', $row->_lang);
            model($table)->update($id, $where);



            //== Cap nhat vao data
            $this->translate_model->set($table, $id, $data);
            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;
            $result['location'] = admin_url($table);
            set_message(lang('notice_update_success'));
            // Form output
            $this->_form_submit_output($result);
        }


        // Lay cac ban dich cua row
        $info = $this->translate_model->get($table, $id);
        foreach ($field as $f) {
            foreach ($langs as $l) {
                if ((empty($info[$f][$l->id])) && isset($row->$f)) {
                    $info[$f][$l->id] = $row->$f;
                } else {
                    $info[$f][$l->id] = $info[$f][$l->id];
                }
            }
        }

        // Luu cac bien gui den view
        $this->data['action'] = current_url();
        $this->data['message'] = get_message();
        $this->data['langs'] = $langs;
        $this->data['table'] = $table;
        $this->data['id'] = $id;
        $this->data['field'] = $field;
        $this->data['info'] = $info;

        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(admin_url('page'), lang('mod_' . $table));
        $breadcrumbs[] = array(current_url(), lang('mod_translate'));
        $this->data['breadcrumbs'] = $breadcrumbs;


        $view = "/admin/{$table}/translate";

        if($this->input->is_ajax_request()){
            $this->_display($view,null);
        }
        else
            $this->_display($view);
    }

    // --------------------------------------------------------------------

    /**
     * Trang dich chinh
     */
    protected function _translate($page)
    {
        // Lay input
        $table = $this->input->get('table');
        $id = $this->input->get('id');
        $field = $this->input->get('field');

        // Xu ly field
        $field = (!is_array($field)) ? array($field => '') : $field;
        foreach ($field as $f => $o) {
            $o = (!is_array($o)) ? array() : $o;
            $o = set_default_value($o, array('type', 'name', 'value'));
            $o['type'] = (!in_array($o['type'], array('text', 'textarea', 'html'))) ? 'text' : $o['type'];
            $o['name'] = ($o['name'] == '') ? $f : $o['name'];
            $field[$f] = $o;
        }

        // Lay danh sach lang
        $langs = lang_get_list();


        // Tai cac file thanh phan
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Lay data
            $data = array();
            foreach ($field as $f => $o) {
                $v = $this->input->post($f);
                $v = array_filter($v);
                $data[$f] = $v;
            }
            $data = array_filter($data);

            // Cap nhat vao data
            $this->translate_model->set($table, $id, $data);

            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;
            $result['location'] = current_url(TRUE);
            set_message(lang('notice_update_success'));

            // Form output
            $this->_form_submit_output($result);
        }


        // Lay cac ban dich trong data
        $info = $this->translate_model->get($table, $id, array_keys($field));

        // Luu cac bien gui den view
        $this->data['action'] = current_url(TRUE);
        $this->data['langs'] = $langs;
        $this->data['table'] = $table;
        $this->data['id'] = $id;
        $this->data['field'] = $field;
        $this->data['info'] = $info;

        // Breadcrumbs
        $breadcrumbs = array();
        $breadcrumbs[] = array(current_url(TRUE), lang('mod_translate'));
        $this->data['breadcrumbs'] = $breadcrumbs;

        // Hien thi view
        if ($page == 'quick') {
            $this->_display('', NULL);
        } else {
            $this->_display();
        }
    }


}