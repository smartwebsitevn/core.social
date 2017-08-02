<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_owner extends MY_Controller
{
    protected function _get_mod()
    {
        return 'product';
    }

    function __construct()
    {
        parent::__construct();
        if (!user_is_login()) {
            redirect_login_return();
        }
        $user = user_get_account_info();
        $this->data['user'] = $user;

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
        return $this->_remap_action($method, $params, array('create','active','unactive','del'), '_action');
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
        $act = $this->input->get('_act');
        if ($act && $this->input->is_ajax_request()) {
            if(!in_array($act,['post_youtube','load_types','load_url','load_files'])) return;
            set_output('html',  $this->{'_' . $act}($fake_id));
            return;
        }
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($fake_id) {
            $user = $this->data['user'];
            $user = mod('user')->add_info($user);
            $id = 0;
            $data = $this->_get_inputs($id, $fake_id);
            $data['created'] = now();
            model('product')->create($data, $id);
            $this->_update_infos($id, $data);
            // Cap nhat lai anh
            model('file')->update_table_id_of_mod('product', $fake_id, $id);
            fake_id_del('product');
            set_message(lang('notice_add_success'));
            return $user->_url_my_page;
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
            $user = $this->data['user'];
            $user = mod('user')->add_info($user);
            $data = $this->_get_inputs($info->id, $info->id);
            // pr($data);
            model('product')->update($info->id, $data);
            //pr_db($data);
            $this->_update_infos($info->id, $data);
            set_message(lang('notice_update_success'));
            return $user->_url_my_page;

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

        $rules['youtube'] = array('youtube', 'required|trim|xss_clean|callback__check_youtube');

        $this->form_validation->set_rules_params($params, $rules);
    }
    // Su ly link thuoc loai chuyen biet
    function _check_youtube($link)
    {
        $rs =lib('youtube')->getVideoInfo($link,false);
        // neu la link youtube
        if (!$rs) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        $this->data['youtube_info'] =$rs;
        return true;
    }

    // kiem tra xem url co phai link youtube
    function _get_link_youtube($url)
    {
        if (empty($url))
            return false;

        $parse_url = parse_url($url);
        if (!isset($parse_url ['query']))
            return false;
        $array = explode("&", $parse_url ['query']);
        $param = explode("=", $array [0]);
        //pr($param);
        if ($parse_url ['path'] != '/watch' || $param [0] != "v") {
            return false;
        }
        $key = $param [1];
        /*if (!$this->checkVideoExist($key)) {
            return false;
        }*/
        return $key;

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

    protected function _get_verify($data)
    {
        return 1;
        // pr($data,0);
        $settings = $this->data['recruit_settings'];
        if ($settings['mode_verify_recruit'] == 'auto') {
            return 1;
        } elseif ($settings['mode_verify_recruit'] == 'special') {
            // cac truong can check noi dung rieng
            foreach (array(
                         'cat_lang_id', 'cat_j_note_id', 'cat_j_welfare_id',
                         'cat_u_specialize_id', 'cat_u_meetwork_id', 'cat_u_quality_id',
                     ) as $f) {

                if (isset($data[$f]) && $data[$f]) {
                    $list = json_decode($data[$f]);
                    // echo '<br>-';pr($list,0);
                    if ($list) {
                        foreach ($list as $it) {
                            // echo '<br>-';pr($it,0);
                            if (!is_numeric($it->id) && $it->content) {
                                //echo '<br>-';pr($it,0);
                                return 0;
                            }
                        }
                    }
                }
            }
        } elseif ($settings['mode_verify_recruit'] == 'handle') {
            return 0;
        }
        // pr(1);
        return 1;
    }

    protected function _get_params()
    {
        $params = model('product')->fields;
        // array_push($params, 'image','avatar','icon', 'banner');
        return $params;
    }

    protected function _get_inputs($id = null, $fake_id = null)
    {
        $data = parent::_form_get_inputs($id, $fake_id);
        $data['user_id'] = $this->data['user']->id;
        $data['link_data'] =  json_encode($this->_get_url_data($data['link']));

        $draft = $this->input->post('draft');
        if ($draft)
            $data['draft'] = 1;
        $data['verified'] = $this->_get_verify($data);
        $data['status'] = 1;// cong bo ngay khi dang

        return $data;
    }

    protected function _update_infos($id, $data)
    {
        return;
        mod('product')->tags_set($id, $this->input->post('tags'));
        mod('product')->to_option($id, $this->input->post('option'), $this->input->post('option_value'));
        // mod('product')->to_attribute( $id, $this->input->post('attribute') );
        mod('product')->to_discount($id, $this->input->post('discount'));
        mod('product')->to_special($id, $this->input->post('special'));
        mod('product')->to_addon($id, $this->input->post('addon'));


    }

    protected function _create_view_data($id, $info = null)
    {
        parent::_form_create_view($id, $info);
       // $this->data['widget_upload_images']['url_get'] =$this->_url().'?_act=load_files&file_type=image';
        $this->data['widget_upload_images']['url_get'] =$this->_url().'?_act=load_files&file_type=image&field=images';
        $this->data['widget_upload_files']['url_get'] =$this->_url().'?_act=load_files&file_type=file&field=files';

        // Xu ly thong tin
        if (isset($info->user_id) && $info->user_id)
            $info->_user = model('user')->get_info($info->user_id, 'email,username,name');
        // echo 1;pr($info);
        if ($info) {
            $info = mod('product')->add_info($info, true);
            //echo 1;pr($info);
            $info = mod('product')->tags_get($info);
        }
        $info = isset($info) ? (array)$info : null;
        $this->data['info'] = $info;

        $this->data['countrys'] = model('country')->get_grouped();

        $this->data['user'] = mod('user')->add_info($this->data['user']);

    }
    /**
     * Lay id xu ly hien tai
     *
     * @return int
     */
    protected function _get_id_cur()
    {
        return ($this->uri->rsegment(2) == 'index') ? fake_id_get($this->_get_mod())            : $this->uri->rsegment(3);
    }
}