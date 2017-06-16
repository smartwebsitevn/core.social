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
        $this->load->model('comment_model');
        $this->load->model('user_model');
        $this->load->helper('user');

        $this->lang->load('site/comment');
    }


    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params = array())
    {
        if (!is_array($params)) {
            $params = array($params);
        }

        $rules = array();
        $rules['user'] = array('user', 'callback__check_user');
        $rules['rate'] = array('rate', 'trim|xss_clean|greater_than[0]|less_than[6]');
        $rules['content'] = array('content', 'required|trim|xss_clean|min_length[6]|max_length[255]|filter_html');
        $rules['security_code'] = array('security_code', 'required|trim|callback__check_security_code');
        $rules['parent_id'] = array('parent_id', 'trim|callback__check_parent_id');
        $rules['table_id'] = array('product_id', 'required|trim|callback__check_table_id');
        $rules['table_name'] = array('table_name', 'trim|xss_clean');

        foreach ($params as $param) {
            if (isset($rules[$param])) {
                $this->form_validation->set_rules($param, 'lang:' . $rules[$param][0], $rules[$param][1]);
            }
        }
    }


    /**
     * Kiem tra id comment cha
     */
    function _check_parent_id($value)
    {
        if (!$value) {
            return TRUE;
        }

        $where = array();
        $where['id'] = $value;
        $id = $this->comment_model->get_id($where);
        if (!$id) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }


    /**
     * Kiem tra id comment cha
     */
    function _check_user($value)
    {

        if (!user_is_login()) {

            $this->form_validation->set_message(__FUNCTION__, 'Bạn cần đăng nhập để sử dụng chức năng này');
            return FALSE;
        }
        return TRUE;
    }


    /**
     * Kiem tra id comment cha
     */
    function _check_table_id($value, $type, &$err = null)
    {
        $where = array();
        $where['id'] = $value;
        if ($type == 'product')
            $id = model('product')->get_id($where);
        elseif ($type == 'lesson')
            $id = model('lesson')->get_id($where);
        elseif ($type == 'site') {
            $user = user_get_account_info();
            if (!$user) {
                $err = 'Vui lòng đăng nhập để có thể sử dụng chức năng này';
                return false;
            }
            // kiem tra xem user nay da vote chua
            $err = 'Cám ơn, bạn đã đánh giá rồi.';

            $id = !model("comment")->check_exits(['user_id' => $user->id, 'table_name' => 'site']);
            //$id = 1;
        }
        else{
            $id = model($type)->get_id($where);
        }

        //pr($id);
        if (!$id) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }


    /**
     * Kiem tra ma bao mat
     */
    function _check_security_code($value)
    {
        $this->load->library('captcha_library');

        if (!$this->captcha_library->check($value, 'four')) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return FALSE;
        }

        return TRUE;
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


    function add()
    {
        if(!mod("product")->setting('comment_allow'))
            redirect();
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');

        // Tu dong kiem tra gia tri cua 1 bien
        $param = $this->input->post('_autocheck');
        if ($param) {
            $this->_autocheck($param);
        }

        //kiem tra id product
        $table_id = $this->input->post('table_id');
        $table_name = $this->input->post('table_name');
        $err = '';
        if (!$this->_check_table_id($table_id, $table_name, $err)) {
            set_output('json', json_encode(['user' => $err]));
        }

        // Gan dieu kien cho cac bien
        $params = array('user',  'content');
      if (in_array($table_name,['site','product']))
          $params[]='rate';

        $this->_set_rules($params);

        // Xu ly du lieu
        $result = array();
        if ($this->form_validation->run()) {
            $user = user_get_account_info();

            // neu la khoa hoc thi chi cho comment 1 lan
            if($table_name =="product"){
                if(model("comment")->check_exits(["table_id"=>$table_id,"table_name"=>$table_name,"user_id"=>$user->id]))
                   set_output('json', json_encode(['user' => "Bạn đã đánh giá sản phẩm này rồi!"]));

            }
            // Lay content
            $content = $this->input->post('content');
            $content = strip_tags($content);

            // Them du lieu vao data
            $data = array();
            $data['table_id'] = $table_id;
            $data['table_name'] = $table_name;
            $data['rate'] = floatval($this->input->post('rate'));
            $data['content'] = $content;
            $data['user_id'] = $user->id;
            $comment_active_status = mod("product")->setting('comment_auto_verify');

            if ($comment_active_status == config('status_on', 'main')) {
                $data['status'] = config('verify_yes', 'main');

                //them so lan nhan xet
                if ($table_name == 'product')
                    $model = model('product')->get_info($table_id, 'id, comment_count, rate_total, rate_one, rate_two, rate_three, rate_four, rate_five');
                elseif ($table_name == 'site')
                    $model = (object)setting_get_group('site-rating');
                else
                    $model = model($table_name)->get_info($table_id, 'id, comment_count, rate_total, rate_one, rate_two, rate_three, rate_four, rate_five');

                $_data = array();
                $_data['comment_count'] = $model->comment_count + 1;

                if(isset($data['rate']) && $data['rate']){
                    $_data['rate_total'] = $model->rate_total + 1;
                    $arrs = array(
                        1 => 'rate_one',
                        2 => 'rate_two',
                        3 => 'rate_three',
                        4 => 'rate_four',
                        5 => 'rate_five'
                    );
                    $_data[$arrs[$data['rate']]] = $model->{$arrs[$data['rate']]} + 1;

                    $count = 0;
                    for ($i = 1; $i < 6; $i++) {
                        if ($data['rate'] == $i)
                            $count += ($model->{$arrs[$i]} + 1) * $i;
                        else
                            $count += $model->{$arrs[$i]} * $i;
                    }
                    $_data['rate'] = round($count / $_data['rate_total'], 1);
                }

                if ($table_name == 'product')
                    model('product')->update($model->id, $_data);
                elseif ($table_name == 'site') {
                    model('setting')->set_group('site-rating', $_data);
                }
                else
                    model($table_name)->update($model->id, $_data);
            }
            $data['created'] = now();

            model("comment")->create($data);

            // Khai bao du lieu tra ve
            $result['complete'] = TRUE;

            if ($comment_active_status == config('status_on', 'main')) {
                $result['location'] = '';
                set_message(lang('notice_comment_success'));
            } else {
                $result['location'] = '';
                set_message(lang('notice_send_comment_success'));
            }
        } else {
            foreach ($params as $param) {
                $result[$param] = form_error($param);
            }
        }


        //Form Submit
        $this->_form_submit_output($result);
    }


    function show()
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('file_model');

        $table_id = $this->uri->rsegment(4);
        $table_name = $this->uri->rsegment(3);
        if (!$this->_check_table_id($table_id, $table_name)) {
            return;
        }

        $filter = [
            'status' => config('verify_yes', 'main'),
            'table_id' => $table_id,
            'table_name' => $table_name
        ];
        $total = model('comment')->filter_get_total($filter);

        $page_size = $this->input->get('per_page');
        $page = $this->input->get('page');
        $limit = ($page - 1) * $page_size;
        $limit = max(0, $limit);

        $input = array();
        $input['order'] = array('created', 'DESC');
        $input['limit'] = array($limit, $page_size);

        $filter['parent_id'] = 0;

        // Lay danh sach
        $list = model('comment')->filter_get_list($filter, $input);
        foreach ($list as $row) {
            $user = model('user')->get_info($row->user_id, 'name');
            $row->user = $user;
            $image = $this->file_model->get_info_of_mod('user', $row->user_id, 'avatar', 'id, file_name');
            if ($image)
                $row->user->avatar = $image->file_name;


            if ($table_name == 'product')
                $row = mod('product')->comment_add_info($row);
            else
                $row = mod("product")->comment_add_info($row);
        }

        if ($table_name == 'product')
            $this->data['info'] = model('product')->get_info($table_id);
        else
            $this->data['info'] = model('lesson')->get_info($table_id);

        $this->data['list'] = $list;
        $this->data['total'] = $total;
        $this->data['page'] = $page;
        $this->data['page_size'] = $page_size;
        $this->data['ajax_pagination_total'] = ceil($total / $page_size);

        // Hien thi view
        $temp = 'site/_widget/' . $table_name . '/comment/_list';
        $this->load->view($temp, $this->data);
    }


}