<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_post extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lang->load('site/product');

        $this->data['currency'] = currency_get_default();
        $this->data['categories'] = model('product_cat')->get_hierarchy_data();
        $this->data['manufactures'] = model('manufacture')->get_list();

        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }
        $range_types = mod('range')->get_range_types();
        foreach ($range_types as $t) {
            $this->data['range_type_' . $t] = model('range')->get_type($t);
        }

    }


    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, model('product')->actions_row);
    }

    /*
       * ------------------------------------------------------
       *  Danh sach
       * ------------------------------------------------------
       */

    function loadOptionValue()
    {
        if( $_GET['option_id'] )
        {
            $model = model('option_value')->get_list_rule( array( 'option_id' => $_GET['option_id'] ) );

            set_output ('text', json_encode($model) );
        }
    }
    /*
     * ------------------------------------------------------
     *  Actions
     * ------------------------------------------------------
     */

    /**
     * Them moi
     */
    function index()
    {
        $fake_id = $this->_get_id_cur();
        $form = array();
        $form['validation']['params'] =$this->_get_params();
        $form['submit'] = function () use ($fake_id) {
            $id = 0;
            $data = $this->_get_inputs($id,$fake_id);
            if(isset($data['sort_order']) && !$data['sort_order'])
                $data['sort_order'] = model('product')->get_total() + 1;
            model('product')->create($data, $id);
            $this->_update_infos($id, $data);
            // Cap nhat lai anh
            model('file')->update_table_id_of_mod('product', $fake_id, $id);
            fake_id_del('product');
            set_message(lang('notice_add_success'));
            return $this->_url();
        };
        $form['form'] = function () use ($fake_id) {
            $this->_create_view_data($fake_id);
            $this->_display('add');
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
           // pr($data);
            model('product')->update($info->id, $data);
            //pr_db($data);
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
        model('product')->del($info->id);

        // Xoa file
        file_del_table('product', $info->id);

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
            $info = model('product')->get_info($id);
            if (!$info) continue;

            // Kiem tra co the thuc hien hanh dong nay khong
            if (!mod('product')->can_do($info, $action)) continue;


            // Chuyen den ham duoc yeu cau
            if (in_array($action, array('feature', 'feature_del'))) {
                // thuc hien yeu cau
                set_message(lang('notice_update_success'));
                mod('product')->action($info, $action);
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
     * Kiem tra city
     */
    function _check_country($value)
    {
        $row = model('country')->check_id($value);
        if (!$row) {
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
        if (!$v)
            return TRUE;
        if (!$this->_get_user_id()) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }



    /**
     * Kiem tra email nay co ton tai hay khong
     */
    function _check_admin($v)
    {
        if (!$v)
            return TRUE;
        if (!$this->_get_admin_id()) {
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
        return $id ? $id : 0;
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
        return $id ? $id : 0;

    }

    protected function _get_params()
    {
        $params =  model('product')->fields;
        // array_push($params, 'image','avatar','icon', 'banner');
        return $params;
    }
    protected function _get_inputs($id=null,$fake_id=null)
    {
        $data = parent::_form_get_inputs($id,$fake_id);
        $data['user_id'] = $this->_get_user_id();
        $user_options = $this->input->post("user_options");
        if ($user_options) {
            $user_options["amount"] = currency_handle_input($user_options["amount"]);
            $user_options['user_id'] = $data['user_id'];
        }
        $data['user_options'] = json_encode($user_options);
        $affiliate_options = $this->input->post("affiliate_options");
        if ($affiliate_options) {
            $affiliate_options["amount"] = currency_handle_input($affiliate_options["amount"]);
            $data['affiliate_options'] = json_encode($affiliate_options);
        }
        $data['price_is_contact'] = (int) $data['price_is_contact'];
        $data['price_is_auction'] = (int) $data['price_is_auction'];
        //pr($data);
        return $data;
    }

    protected function _update_infos($id, $data)
    {
        mod('product')->tags_set($id, $this->input->post('tags'));
        mod('product')->to_option( $id, $this->input->post('option'), $this->input->post('option_value') );
       // mod('product')->to_attribute( $id, $this->input->post('attribute') );
        mod('product')->to_discount( $id, $this->input->post('discount') );
        mod('product')->to_special( $id, $this->input->post('special') );
        mod('product')->to_addon( $id, $this->input->post('addon') );


    }
    protected function _create_view_data($id, $info = null)
    {
        parent::_form_create_view($id, $info);
        // Xu ly thong tin
        if (isset($info->user_id) && $info->user_id)
            $info->_user = model('user')->get_info($info->user_id, 'email,username,name');
       // echo 1;pr($info);
        if ($info) {
            $info = mod('product')->add_info($info,true);
            //echo 1;pr($info);
            $info = mod('product')->tags_get($info);
        }
        $info = isset($info) ? (array)$info : null;
        $this->data['info'] = $info;

        $this->data['taxclasses'] = model('tax_class')->get_list( );
        $this->data['countrys'] = model('country')->get_grouped();

        $this->data['options'] = model('option')->get_list( array( 'type' => 'asc' ) );
        $this->data['option_values'] = model('option_value')->get_list( array( 'sort' => 'asc' ) );
        $this->data['attribute_groups'] = model('attribute_group')->get_list( array( 'sort' => 'asc', 'id' => 'desc' ) );
        $this->data['attributes'] = model('attribute')->get_list( array( 'sort' => 'asc', 'id' => 'desc' ) );
        $this->data['addons'] = model('addon')->get_list( array( 'sort' => 'asc', 'id' => 'desc' ) );





    }

}