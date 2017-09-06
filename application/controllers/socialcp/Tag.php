<?php

class Tag extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();

        $this->lang->load('admin/tag');
    }

    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('edit', 'del', 'feature', 'feature_del'));
    }


    /*
     * ------------------------------------------------------
     *  Rules params
     * ------------------------------------------------------
     */
    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $rules = array();

        $rules['name'] = array('name', 'required|trim|xss_clean|callback__check_name');

        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra ten
     */
    public function _check_name($value)
    {
        $id = $this->uri->rsegment(3);
        $id = (!is_numeric($id)) ? 0 : $id;
        $where = array();
        $where['id !='] = $id;
        $where['name'] = $value;
        $id = $this->_model()->get_id($where);
        if ($id)
        {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_already_used'));
            return FALSE;
        }
        return TRUE;
    }


    function _fields()
    {
        $fields = array(
            'name',   'meta_title', 'meta_desc', 'meta_key', 'seo_url',
            'status', 'feature',
        );
        return $fields;
    }

    protected function _get_params()
    {
        $params = $this->_fields();
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
        if(!$data['seo_url'])
            $data['seo_url'] = convert_vi_to_en($data['name']);
        //pr($data);
        return $data;
    }
    /**
     * Tao data gui den view
     *
     * @param int $id
     */
    protected function _create_view_data($id=null)
    {

        // Other
        $this->data['action'] = current_url();
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
        $ids = ( ! $ids) ? $this->input->post('id') : $ids;
        $ids = ( ! is_array($ids)) ? array($ids) : $ids;

        // Thuc hien action
        foreach ($ids as $id)
        {
            // Xu ly id
            $id = ( ! is_numeric($id)) ? 0 : $id;

            // Kiem tra id
            $info = $this->_model()->get_info($id);
            if ( ! $info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if ( ! $this->_mod()->can_do($info, $action)) continue;

            // Chuyen den ham duoc yeu cau
            if (in_array($action, array('feature', 'feature_del')))
            {
                $this->_action_option($info, $action);
            }
            else
            {
                $this->{'_'.$action}($info);
            }
        }
    }

    /**
     * Xu ly hanh dong voi cac thuoc tinh
     */
    function _action_option($info, $action)
    {
        // Xu ly voi cac option
        $data = array();
        switch ($action)
        {
            case 'feature':
            {
                $data[$action] = 1;//now();
                break;
            }
            case 'feature_del':
            {
                $p = preg_replace('#_del$#i', '', $action);
                $data[$p] = 0;
                break;
            }
        }

        // Cap nhat data
        if (count($data))
        {
            $this->_model()->update($info->id, $data);
            $output = json_encode(array('complete' => TRUE));
            set_output('json', $output);
        }
    }

    /**
     * Chinh sua
     */
    protected function _edit($info)
    {
        //$info = $this->_mod()->add_info($info);
        $this->data['info'] = $info;

              // Form
        $form = array();

        $form['validation']['params'] =$this->_get_params();

        $form['submit'] = function() use ($info)
        {
            $data = $this->_get_inputs();
            $this->_model()->update($info->id, $data);
            set_message(lang('notice_update_success'));
            return $this->_url();
        };

        $form['form'] = function() use ($info)
        {
            $this->_create_view_data($info->id);

            $this->_display('form');
        };
        $this->_form($form);
    }

    /**
     * Xoa
     */
    protected function _del($info)
    {
        $this->_model()->del($info->id);
        // xoa trong bang value
       model('tag_value')->del_rule(array('tag_id'=>$info->id));

        set_message(lang('notice_del_success'));
    }
    /**
     * Them moi
     */
    public function add()
    {

        $form = array();

        $form['validation']['params'] = $this->_get_params();

        $form['submit'] = function()
        {
            // Lay input
            $data = $this->_get_inputs();

            $this->_model()->create($data, $id);
            set_message(lang('notice_add_success'));
            return $this->_url();
        };

        $form['form'] = function()
        {
            $this->_create_view_data();
            $this->_display('form');
        };

        $this->_form($form);
    }

    /**
     * Danh sach
     */
    public function index()
    {
        $list = array();
        $list['filter'] = TRUE;
        $list['filter_fields'] = array('name','feature','status');
        $list['sort'] = true;
        $list['display'] = false;
        $this->_list($list);

        $list = $this->data['list'];
        $actions = array('edit', 'del', 'feature', 'feature_del');
        $list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
        foreach ($list as $row)
        {
            foreach ($actions as $action)
            {
                $row->{'_can_'.$action} = ($this->_mod()->can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
            }
            foreach (array('edit') as $action)
            {
                $row->{'_url_'.$action} = url_add_return($row->{'_url_'.$action});
            }
        }
        $this->data['list'] = $list;
        $this->_display();
    }
    /**
     * Tim kiem product info (autocomplete)
     */
    function getinfor() {
        $info = $this->uri->rsegment(3);
        $keyword = $this->input->get('term');
        $this->load->model('tag_model');

        $filter = array();
        $filter['name'] = $keyword;
        $input = array();
        $input['limit'] = array(0, config('list_auto_limit', 'main'));
        $list = $this->tag_model->filter_get_list($filter, $input);
        $result = array();
        foreach ($list as $row) {
            $item = array();
            $item['id'] = $row->id;
            $item['label'] = $row->name;
            $item['value'] = $row->name;

            $result[] = $item;
        }

        $output = json_encode($result);
        set_output('json', $output);
    }
}