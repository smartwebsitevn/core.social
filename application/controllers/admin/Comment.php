<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller
{

    /**
     * Ham khoi dong
     */
    function __construct()
    {
        parent::__construct();

        // Tai cac file thanh phan
        $this->lang->load('admin/' . $this->_get_mod());

    }


    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('view','reply',  'verify', 'unverify', 'del'));
    }

    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $rules = array();
        $rules['rate_one'] = array('rate', 'trim|xss_clean');
        $rules['rate_two'] = array('rate', 'trim|xss_clean');
        $rules['rate_three'] = array('rate', 'trim|xss_clean');
        $rules['rate_four'] = array('rate', 'trim|xss_clean');
        $rules['rate_five'] = array('rate', 'trim|xss_clean');
        $rules['content'] = array('content', 'required|trim|xss_clean|filter_html|min_length[6]|max_length[255]');

        $this->form_validation->set_rules_params($params, $rules);
    }


    /*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
    function index_()
    {


        // Hien thi view
        $this->_display();
    }

    /*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
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

            if (in_array($action, array('verify', 'unverify',))) {
                // thuc hien yeu cau
                set_message(lang('notice_update_success'));
                $this->_mod()->action($info, $action);
                $output = array('complete' => TRUE);
                set_output('json', json_encode($output));
            } else {
                $this->{'_' . $action}($info);
            }

        }
    }

    /**
     * Kiem tra co the thuc hien hanh dong hay khong
     */
    function _can_do($info, $action)
    {
        switch ($action) {
            case 'view':
            case 'del': {
                return TRUE;
            }
        }

        return FALSE;
    }
    function _reply($info)
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');


        // Gan dieu kien cho cac bien
        $params = array(  'content');
        $this->_set_rules($params);
        // Xu ly du lieu
        $result = array();
        if ($this->form_validation->run()) {

            // Lay content
            $content = $this->input->post('content',true);
            $content = strip_tags($content);

            // Them du lieu vao data
            $data = array();
            $data['table_id'] = $info->table_id;
            $data['table_name'] = $info->table_name;
            $data['content'] = $content;
            //$data['user_id'] = $user->id;
            $data['parent_id'] =$info->id;
            $data['status'] = config('verify_yes', 'main');
            $data['created'] = now();

                //them so lan nhan xet
                if ($data['table_name'] == 'course'){
                    model('lesson_course')->update_stats($data['table_id'],['comment_count'=>1]);

                }
                elseif ($data['table_name'] == 'lesson')
                    model('lesson')->update_stats($data['table_id'],['comment_count'=>1]);



            set_message(lang('notice_update_success'));
            model("comment")->create($data);

            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;

        } else {
            foreach ($params as $param) {
                $result[$param] = form_error($param);
            }
        }
        //Form Submit
        $this->_form_submit_output($result);
    }
    /**
     * Xem chi tiet
     */
    function _view($info)
    {
        // Gan trang thai
        if ($info->readed == config('verify_no', 'main')) {
            $this->_model()->update_field($info->id, 'readed', config('verify_yes', 'main'));
        }

        // Xu ly thong tin
        $info->_created = get_date($info->created);
        $info->_created_full = get_date($info->created, 'full');
        $table = trim($info->table_name);
        $info->model = null;
        if ($table != "site") {
            if ($table == 'course')
                $table = "lesson_course";
            $info->model = mod($table)->get_info($info->table_id);
        }

        $info->user = mod('user')->get_info($info->user_id);
        // Luu bien gui den view
        $this->data['info'] = $info;

        // Hien thi view
        $this->_display('view', NULL);
    }

    /**
     * Xoa du lieu
     */
    function _del($info)
    {
        // Thuc hien xoa
        $this->_model()->del($info->id);

        // Gui thong bao
        set_message(lang('notice_del_success'));
    }

    /**
     * Danh sach
     */
    function index()
    {
        // Tai cac file thanh phan
        $filter = array();
        $filter['parent_id'] = 0;
        $filter['table_name_not_site'] = true;
        // Tao danh sach
        $this->_create_list($filter);
        $this->_display();
    }

    function site()
    {
        $filter = array();
        $filter['table_name'] = "site";
        $filter['parent_id'] = 0;

        // Tao danh sach
        $this->_create_list($filter);
        $this->_display();
    }

    function site_edit()
    {
        // Form
        $form = array();

        $form['validation']['params'] = array(
            'rate_one', 'rate_two',  'rate_three', 'rate_four', 'rate_five',
        );

        $form['submit'] = function ($params) {
            $_data = array();
            $arrs = array(
                1 => 'rate_one',
                2 => 'rate_two',
                3 => 'rate_three',
                4 => 'rate_four',
                5 => 'rate_five'
            );
            $count = $total = 0;
            for ($i = 1; $i < 6; $i++) {
                $v = floatval($this->input->post($arrs[$i]));;
                $_data[$arrs[$i]] = $v;
                $count += $v * $i;
                $total += $v;
            }

            $_data['rate_total'] = $total;
            $_data['comment_count'] = $total;
            $_data['rate'] = round($count /$total, 1);
           // pr($_data);
            model('setting')->set_group('site-rating', $_data);

            set_message(lang('notice_update_success'));

            return $this->_url('site');
        };

        $form['form'] = function () {
            $model = (object)setting_get_group('site-rating');
            $this->data["info"] = $model;
            $this->_display('site_form');
        };

        $this->_form($form);
    }

    private function _create_list($filter = array(), $filter_fields = array(), $input = array())
    {
        // Lay gia tri cua filter dau vao
        $filter_input = array();
        if (!$filter_fields)
            $filter_fields = array('id', 'user', 'table_name', 'status', 'readed', 'created');
        $mod_filter = $this->_mod()->create_filter($filter_fields, $filter_input);
        $filter = array_merge($mod_filter, $filter);
        $this->data['filter'] = $filter_input;
        $total = $this->_model()->filter_get_total($filter);
        $page_size = config('list_limit', 'main');
        $limit = $this->input->get('per_page');
        $limit = min($limit, get_limit_page_last($total, $page_size));
        $limit = max(0, $limit);

        // Lay danh sach
        $input = array();
        $input['limit'] = array($limit, $page_size);
        $list = $this->_model()->filter_get_list($filter, $input);
        //pr_db($filter_input);
        $actions = array('view', 'del');
        $list = admin_url_create_option($list, 'comment', 'id', $actions);
        foreach ($list as $it) {
            $it = $this->_mod()->add_info($it);
            $table = trim($it->table_name);
            $it->model = null;
            if ($table != "site") {
                if ($table == 'course')
                    $table = "lesson_course";
                 $it->model = mod($table)->get_info($it->table_id);

                $filter['parent_id']    = $it->id;
                $subs = $this->_model()->filter_get_list($filter, $input);
                foreach ($subs as $sub)
                {
                    $sub->user = mod('user')->get_info($sub->user_id);
                    $sub->_created = get_date($sub->created);
                }
                $it->subs = $subs;
            }
            $it->user = mod('user')->get_info($it->user_id);

            foreach ($actions as $action) {
                $it->{'_can_' . $action} = $this->_mod()->can_do($it, $action);
            }
        }
       // pr($list);
        $this->data['list'] = $list;

        // Tao chia trang
        $pages_config = array();
        $pages_config['page_query_string'] = TRUE;
        $pages_config['base_url'] = $this->_url() . '?' . url_build_query($filter_input);
        $pages_config['total_rows'] = $total;
        $pages_config['per_page'] = $page_size;
        $pages_config['cur_page'] = $limit;
        $this->data['pages_config'] = $pages_config;
        $actions = array();
        foreach (array('del', 'verify', 'unverify',) as $v) {
            $url = admin_url(strtolower(__CLASS__) . '/' . $v);
            if (!admin_permission_url($url)) continue;

            $actions[$v] = $url;
        }
        $this->data['actions'] = $actions;
    }
}