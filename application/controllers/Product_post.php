<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_post extends MY_Controller
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
        return $this->_remap_action($method, $params, array('edit','off','on','active','unactive','del'), '_action');
    }



    /*
       * ------------------------------------------------------
       *  Danh sach
       * ------------------------------------------------------
       */

    /*
     * ------------------------------------------------------
     *  Actions
     * ------------------------------------------------------
     */
    function _ajax_load_types($id)
    {
        $type_cat_id = $this->input->get('type_cat');
        $product_id = $this->input->get('product_id');

        if (!$type_cat_id) return;

        $types = model('type')->filter_get_list(['cat_id' => $type_cat_id], ['select' => 'id,name,image_id,image_name,seo_url']);
        $types_values = [];
        if ($product_id) {
            $types_values = model('type_table')->filter_get_list(['type_cat_id' => $type_cat_id, 'table_id' => $product_id, 'table_' => $this->_get_mod()]);

        }
        if ($types) {
            foreach ($types as $type) {
                $type_items = model('type_item')->filter_get_list(['type_id' => $type->id], ['select' => 'id,name,image_id,image_name,seo_url']);
                $type->items = $type_items;
            }
        }
        $this->data['types'] = $types;
        $this->data['types_values'] = $types_values;
        //pr($types_values);
        return view('tpl::product_post/form/_common/types', $this->data, 1);
    }
    function _ajax_load_url($id)
    {
        $url= $this->input->get('url');
        if (!$url) return;

        $this->data['tags']= $this->_get_url_data($url);
        return view('tpl::product_post/form/_common/metas', $this->data, 1);
    }

    /**
     * Lay danh sach file
     */
    function _ajax_load_files($id)
    {
        // Cap nhat sort_order
        if ($this->input->get('sort'))
        {
            $items = $this->input->post('items');
            $items = explode(',', $items);
            foreach ($items as $i => $v)
            {
                model('file')->update_field($v, 'sort_order', $i+1);
            }
            $this->_response();
        }
        $file_type 		= $this->input->get('file_type');

        // Lay gia tri dau vao
        $table_id 		= $id;
        $table_field 	= $this->input->get('field');
        $table 			= $this->_get_mod();

        // Lay danh sach file
        $list = model('file')->get_list_of_mod($table, $table_id, $table_field);
       // pr_db($list);
        if(!$list ) return;
        foreach ($list as $row)
        {
            $row = file_add_info($row);
            $row->_url_del 		= site_url('file/del').'?'.security_create_query(array('id' => $row->id));
            $row->_url_download = site_url('file/download').'?'.security_create_query(array('id' => $row->id));
            if (isset($row->table) && isset($row->table_id))
            {
                $row->_url_get 	= site_url('file/get').'?'.security_create_query(array('table' => $row->table, 'table_id' => $row->table_id, 'table_field' => $row->table_field,));
            }
        }
        //pr($list);
        $this->data['list'] = $list;

        // Luu cac bien gui den view
        $this->data['message'] = get_message();
        $this->data['url_update_order'] = $this->_url().'?_act=load_files&sort=1';

        $this->data['sort'] = (int) $this->input->get('sort');

        // Hien thi view
        $temp 		= $this->input->get('temp');
        if(!$temp){
            $temp = ($file_type == 'image') ? 'slides' : 'files';
        }
        return view('tpl::_widget/product/upload/file/'.$temp, $this->data, 1);
    }

    /**
     * Them moi
     */
    function _ajax_post_youtube($id)
    {
        $form = array();
        $form['validation']['params'] =['youtube'];
        $form['submit'] = function () use ($id) {
            $youtube=$this->data['youtube_info'];
            $image_name = $this->_ajax_post_youtube_dowload_image($youtube);
            if(!$image_name) return;
            $user = $this->data['user'];

            // Them vao table file
            $data = array();
            $data['file_name'] 		= $image_name;
            $data['orig_name'] 		= $image_name;
            $data['table'] 			= $this->_get_mod();
            $data['table_id'] 		= $id;
            $data['table_field'] 	= 'images';
            $data['type'] 	        = 'youtube';
            $data['data'] 	        = $youtube->id;
            $data['user_id'] 	    = $user->id;
            $data['created'] 		= now();
            model('file')->create($data);
            return;
        };
        $this->_form($form);

    }
    public function _ajax_post_youtube_dowload_image($youtube) {
        // Lay folder upload file
        $status = config('file_public', 'main');
        $folders 	= config('file', 'main');
        $folder 	= $folders[$status];
        // Tao config upload
        $config 				= config('upload', 'main');
        $path= $config['path'].$config['folder'].'/'.$folder.'/';
        $image_url = trim ($youtube->url_image );
        $path_img_new = $path . $youtube->id.'.jpg';
        if (lib("Curl")->download ( $image_url, $path_img_new )) {
            file_create_thumb($path_img_new);
            return $youtube->id.'.jpg';
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
        redirect($this->_url('add'));
    }


   function add()
    {
        file_del_temporary(); // xoa anh fa
        $fake_id = $this->_get_id_cur();
        $this->_action_ajax($fake_id);
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($fake_id) {
            $user = $this->data['user'];
            $user = mod('user')->add_info($user);
            $id = 0;
            $data = $this->_get_inputs($id, $fake_id);
            $data['created'] = now();
            $this->_model()->create($data, $id);
            $this->_update_infos($id, $data);
            // Cap nhat lai anh
            model('file')->update_table_id_of_mod($this->_get_mod(), $fake_id, $id);
            fake_id_del($this->_get_mod());
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
            if (in_array($action, array('on', 'off' ,'del'))) {
                // thuc hien yeu cau
                set_message(lang('notice_update_success'));
                $this->_mod()->action($info, $action);
                $this->_response(['reload'=>1]);
                //$this->_action_option($info, $action);
            } else {
                $this->{'_' . $action}($info);
            }
        }
    }


    function _action_ajax($id)
    {
        $act = $this->input->get('_act');
        if ($act && $this->input->is_ajax_request()) {
            if (!in_array($act, ['post_youtube', 'load_types', 'load_url', 'load_files'])) return;
            set_output('html', $this->{'_ajax_' . $act}($id));
            return;
        }
    }
    /**
     * Chinh sua
     */
    function _edit($info)
    {
        $this->_action_ajax($info->id);
        $form = array();
        $form['validation']['params'] = $this->_get_params();
        $form['submit'] = function () use ($info) {
            $user = $this->data['user'];
            $user = mod('user')->add_info($user);
            $data = $this->_get_inputs($info->id, $info->id);
            // pr($data);
            $this->_model()->update($info->id, $data);
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
    function _get_url_data($url)
    {
        if (!$url) return;
        preg_match("/<title>(.+)<\/title>/siU", file_get_contents($url), $matches);
        $meta = get_meta_tags($url);
        $tags =[];
        if(isset($matches[1]))
            $tags['title'] = character_limiter($matches[1],150);
        if(isset($meta['og:image']))
            $tags['image'] =$meta['og:image'];
        if(isset($meta['description']))
            $tags['description'] = character_limiter($meta['description'],150);;

        return $tags;
    }

    //=================================
    protected function _get_params()
    {
        $params = $this->_model()->fields;
        // array_push($params, 'image','avatar','icon', 'banner');
        return $params;
    }

    protected function _get_inputs($id = null, $fake_id = null)
    {
        $data = parent::_form_get_inputs($id, $fake_id);
        $data['user_id'] = $this->data['user']->id;
        if($data['link']){
            $data['type']='link';
            $data['link_data'] =  json_encode($this->_get_url_data($data['link']));

        }
        else{
            $data['type']='media';
        }

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
        $this->_mod()->tags_set($id, $this->input->post('tags'));
        $this->_mod()->to_option($id, $this->input->post('option'), $this->input->post('option_value'));
        // $this->_mod()->to_attribute( $id, $this->input->post('attribute') );
        $this->_mod()->to_discount($id, $this->input->post('discount'));
        $this->_mod()->to_special($id, $this->input->post('special'));
        $this->_mod()->to_addon($id, $this->input->post('addon'));


    }

    protected function _create_view_data($id, $info = null)
    {
        parent::_form_create_view($id, $info);
       // $this->data['widget_upload_images']['url_get'] =$this->_url().'?_act=load_files&file_type=image';
        $this->data['widget_upload_images']['url_get'] =$this->_url($this->_get_act().'/'.$id).'?_act=load_files&file_type=image&field=images';
        $this->data['widget_upload_files']['url_get'] =$this->_url($this->_get_act().'/'.$id).'?_act=load_files&file_type=file&field=files';

        // Xu ly thong tin
        if (isset($info->user_id) && $info->user_id)
            $info->_user = model('user')->get_info($info->user_id, 'email,username,name');
        // echo 1;pr($info);
        if ($info) {
            $info = $this->_mod()->add_info($info, true);
            //echo 1;pr($info);
            $info = $this->_mod()->tags_get($info);
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
        return ($this->uri->rsegment(2) == 'add') ? fake_id_get($this->_get_mod()): $this->uri->rsegment(3);
    }
}