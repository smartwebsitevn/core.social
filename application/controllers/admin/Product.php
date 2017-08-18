<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lang->load('admin/'.$this->_get_mod());

        $this->data['currency'] = currency_get_default();
        $this->data['type_cats'] = model('type_cat')->get_hierarchy_data();
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
        return $this->_remap_action($method, $params, $this->_model()->actions_row);
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
    function get_types()
    {
        $type_cat_id=$this->input->post('type_cat_id');
        $product_id=$this->input->post('product_id');

        if(!$type_cat_id) return;

        $types = model('type')->filter_get_list(['cat_id'=>$type_cat_id], ['select'=>'id,name,image_id,image_name,seo_url']);
        $types_values=[];
       if($product_id){
           $types_values = model('type_table')->filter_get_list(['type_cat_id'=>$type_cat_id,'table_id'=>$product_id,'table_'=>'product']);

       }
        if ($types) {
            foreach($types as $type){
                $type_items = model('type_item')->filter_get_list(['type_id'=>$type->id], ['select'=>'id,name,image_id,image_name,seo_url']);
                $type->items =$type_items;
            }
        }
        $this->data['types'] =$types;
        $this->data['types_values'] =$types_values;
        //pr($types_values);
        view('tpl::type_cat/types', $this->data);

    }
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
            $data['created'] = now();
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
           // pr($data);
            $this->_model()->update($info->id, $data);
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
        $params =  $this->_model()->fields;
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
        $this->_mod()->tags_set($id, $this->input->post('tags'));
         $this->_mod()->to_types( $id, $this->input->post('types'), $this->input->post('type_cat_id') );
        // $this->_mod()->to_option( $id, $this->input->post('option'), $this->input->post('option_value') );
       // $this->_mod()->to_attribute( $id, $this->input->post('attribute') );
       // $this->_mod()->to_discount( $id, $this->input->post('discount') );
       // $this->_mod()->to_special( $id, $this->input->post('special') );
       // $this->_mod()->to_addon( $id, $this->input->post('addon') );


    }
    protected function _create_view_data($id, $info = null)
    {
        parent::_form_create_view($id, $info);
        // Xu ly thong tin
        if (isset($info->user_id) && $info->user_id)
            $info->_user = model('user')->get_info($info->user_id, 'email,username,name');
       // echo 1;pr($info);
        if ($info) {
            $info = $this->_mod()->add_info($info,true);
            //echo 1;pr($info);
            $info = $this->_mod()->tags_get($info);
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