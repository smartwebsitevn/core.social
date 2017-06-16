<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Project extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lang->load('admin/'.$this->_get_mod());
        $this->data['categories'] = model('project_cat')->get_hierarchy_data();
    }


    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, $this->_model()->actions_row);
    }


    /*
       * ------------------------------------------------------
       *  Danh sach
       * ------------------------------------------------------
       */

    public function index()
    {

        $list = array();
        $list['filter'] = TRUE;
        $list['filter_fields'] = $this->_model()->fields_filter;
        $list['actions'] = $this->_model()->actions_row;
        $list['actions_list'] = $this->_model()->actions_list;
        $list['page'] = TRUE;
        $list['sort'] = TRUE;
        $list['display'] = false;
        $this->_list($list);
        /*foreach ($this->data['list'] as $row)
        {
            $row->admin = admin_get_info($row->admin_id);
        }*/
        $this->_display();
    }

    /*
     * ------------------------------------------------------
     *  Actions
     * ------------------------------------------------------
     */

    /**
     * Them moi
     */
    function add()
    {
        $fake_id = $this->_get_id_cur();
        $form = array();
        $form['validation']['params'] =$this->_get_params();
        $form['submit'] = function () use ($fake_id) {
            $id = 0;
            $data = $this->_get_inputs($id,$fake_id);
            if(isset($data['sort_order']) && !$data['sort_order'])
                $data['sort_order'] = $this->_model()->get_total() + 1;
            $data["created"] = now();
            $this->_model()->create($data, $id);
            $this->_update_infos($id, $data);
            // Cap nhat lai anh
            model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
            fake_id_del($this->_get_mod());
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
    function _edit($info)
    {
        if ($this->input->get('act') == 'update_image') {
            $this->_update_image($info->id);
            return;
        }
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($info) {
            $data = $this->_get_inputs($info->id,$info->id);
            $this->_model()->update($info->id, $data);
            $this->_update_infos($info->id, $data);
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

        // Xoa file
        file_del_table($this->_get_mod(), $info->id);

        // Gui thong bao
        set_message(lang('notice_del_success'));
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
            $info = $this->_model()->get_info($id);
            if (!$info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if (!$this->_mod()->can_do($info, $action)) continue;


            // Chuyen den ham duoc yeu cau
            if (in_array($action, array('feature', 'feature_del'))) {
                // thuc hien yeu cau
                set_message(lang('notice_update_success'));
                $this->_mod()->action($info, $action);
                $output = array('complete' => TRUE);
                set_output('json', json_encode($output));
                //$this->_action_option($info, $action);
            } else {
                $this->{'_' . $action}($info);
            }
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
        $rules = parent::_form_set_rules($params);
        $this->form_validation->set_rules_params($params, $rules);
    }
    /**
     * Kiem tra cat
     */
    function _check_cat($value)
    {

        $cat = model("project_cat")->get_info($value, 'id');
        if ( ! $cat)
        {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }
    protected function _get_params()
    {
        $params =  $this->_model()->fields;
        // array_push($params, 'image','avatar','icon', 'banner');
        return $params;
    }
    protected function _get_inputs($id=null,$fake_id=null)
    {
        $data = $this->_form_get_inputs($id,$fake_id);
        return $data;
    }

    protected function _update_infos($id, $data)
    {
        $tags= $this->input->post('tags');
        if($tags)
            $this->_mod()->tags_set($id, $this->input->post('tags'));
    }
    protected function _create_view_data($id, $info = null)
    {
        parent::_form_create_view($id, $info);
        // Xu ly thong tin
        if (isset($info->user_id) && $info->user_id)
            $info->_user = model('user')->get_info($info->user_id, 'email,username,name');
        if ($info) {
            $info = $this->_mod()->tags_get($info);
        }
        $info = isset($info) ? (array)$info : null;
        $this->data['info'] = $info;



    }




}