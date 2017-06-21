<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->load->language('site/product');
        if (mod('product')->setting('turn_off_function_order'))
            redirect();

    }

    public function _remap($method, $params = array())
    {
        // su ly bug do phan seo theo title, no ghi de cac ham khac cua product do do ta phai su ly them phan nay
        $_method = $this->uri->segment(2);
        if (in_array($_method, array('contact', 'request'))) {
            return call_user_func_array(array($this, $_method), $params);

        }
        return $this->_remap_action($method, $params, array(
            'demo',
            'report', 'raty', 'vote',
            'favorite', 'favorite_del',
            'subscribe_del', 'subscribe', 'subscribe_adv',));
    }

    /*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
    function index()
    {
        redirect(site_url('product_list'));
    }

    function view()
    {

        $this->_common_process();
        //$this->_view_process();
        //$this->_other_process();
        $this->_display();
    }

    function _common_process()
    {

        //== Lay thong tin
        $id = $this->uri->rsegment(3);
        if (!is_numeric($id) && is_slug($id)) {
            // neu la seo url
            $info = $this->_model()->filter_get_info(array('seo_url' => $id, 'show' => 1));
        } else {
            $info = $this->_model()->filter_get_info(array('id' => $id, 'show' => 1));

        }
        if (!$info) {
            set_message(lang('notice_page_not_found'));
            redirect();
        }

        //== Cap nhat luot view
        $data = array();
        $data['count_view'] = $info->count_view + 1;
        $this->_model()->update($info->id, $data);

        //== Su ly danh muc
        $category = model('product_cat')->get_info($info->cat_id);

        //== Item Truoc - sau
        $filter = [];// $this->_mod()->sess_data_get('product_filter');// phuc vu loc du lieu
        $filter_pre = $filter;
        $filter_pre['id_lt'] = $info->id;
        $filter_pre['show'] = true;

        $input['limit'] = array(0, 1);
        //$input['order'] = array('id', 'desc');
        $info_pre = $this->_model()->filter_get_list($filter_pre, $input);
        if ($info_pre) $info_pre = $this->_mod()->add_info($info_pre[0]);
        // next
        $filter_next = $filter;
        $filter_next['show'] = true;
        $filter_next['id_gt'] = $info->id;
        $info_next = $this->_model()->filter_get_list($filter_next, $input);
        if ($info_next)
            $info_next = $this->_mod()->add_info($info_next[0]);
        $this->data['info_prev'] = $info_pre;
        $this->data['info_next'] = $info_next;

        //== Them thong tin
        $info = $this->_mod()->add_info($info, true);

        // Lựa chọn sản phẩm
        $info->_option = model('product_to_option')->get_list_rule(array('product_id' => $info->id));
        $ids = array_gets(
            $info->_option,
            'option_id'
        );
        if ($ids) {
            $options = model('option')->filter_get_list(array('id' => $ids), array('order' => array('sort_order', 'ASC')));

            // Sort
            $tmp = array();
            foreach ($options as $opt) {
                $tmp[] = objectExtract(array('option_id' => $opt->id), $info->_option, true);
            }
            $info->_option = $tmp;
            $this->data['options'] = $options;
        }

        $info->_option_value = model('product_to_option_value')->get_list_rule(array('product_id' => $info->id));
        $ids = array_gets(
            $info->_option_value,
            'option_value_id'
        );
        if ($ids)
            $this->data['option_values'] = model('option_value')->filter_get_list(array('id' => $ids));


        //== Chu so huu
        // Login
        $is_login = user_is_login();
        $this->data['is_login'] = $is_login;
        if ($is_login) {
            $this->data['user'] = user_get_account_info();
        }

        //== Day du lieu xuong view
        $this->data['info'] = $info;
        $this->data['category'] = $category;

        //== Breadcrumbs
        $this->_breadcrumbs($category);
        //== Seos
        $title = character_limiter($info->name, 60);
        if ($info->seo_title)
            $title = $info->seo_title;
        page_info('title', $title);
        if ($info->seo_description)
            page_info('description', character_limiter($info->seo_description, 160));
        if ($info->seo_keywords)
            page_info('keywords', $info->seo_keywords);


    }

    function _view_process()
    {
        $info = $this->data['info'];

        // Lay ngay update view + ngay hien tai
        $date_update = explode('-', get_date($info->view_date_day));
        $date_now = explode('-', date("d-m-y", mktime()));

        // Neu ngay hien tai || thang || nam != Ngay update thi reset = 0
        $day = $info->view_in_day;
        if ($date_update[0] != $date_now[0] || $date_update[1] != $date_now[1] || $date_update[2] != $date_now[2] + 2000) {
            $day = 0;
        }

        // Neu tuan hien tai || thang || nam != ngay da update thi reset = 0
        $week = $info->view_in_week;
        $week_now = get_week(now());
        $week_db = get_week($info->view_date_day);
        if ($week_now != $week_db || $date_update[1] != $date_now[1] || $date_update[2] != $date_now[2] + 2000) {
            $week = 0;
        }

        // Neu thang hien tai khong phai la thang trong he thong da cap nhat thi reset = 0
        $months = $info->view_in_months;
        if ($date_update[1] != $date_now[1] || $date_update[2] != $date_now[2] + 2000) {
            $months = 0;
        }

        $number_view_random = 1;//rand(5, 50);

        // Cap nhat luot view + thoi diem xem
        $data = array();
        $data['view_total'] = $info->view_total + $number_view_random;
        $data['view_in_day'] = $day + $number_view_random;
        $data['view_in_week'] = $week + $number_view_random;
        $data['view_in_months'] = $months + $number_view_random;
        $data['view_date_day'] = now();
        $this->_model()->update($info->id, $data);

    }

    function _other_process()
    {
        $info = $this->data['info'];
        $act = $this->input->get('act');

        if ($act == 'get_info') {
            // echo view('tpl::_widget/movie/display/item/info_inline', array('movie' => $movie), 1);
            return;
        } else if ($act == 'get_player') {
            // echo view('tpl::movie/_common/player', $this->data, 1);
            return;
        } else if ($act == 'get_player_popup') {
            echo widget("media")->player($info->link_data, ['image_url' => $info->banner->url]);
            return;

        }
    }


    /**
     * Build breadcrumbs
     *
     * @param  [type] $category [description]
     * @return [type]           [description]
     *
     */
    protected function _breadcrumbs($category)
    {
        $breadcrumbs = array();
        $breadcrumbs[] = array(
            site_url('product_list'),
            lang('search_product'),
            lang('search_product')
        );
        /*$parent = $category;
        while ($parent->parent_id) {
            $parent = model('product_cat')->get_info($parent->parent_id);

            $breadcrumbs[] = array(
                $parent->seo_url,
                word_limiter($parent->name, 10),
                $parent->name
            );

        }

        $breadcrumbs = array_reverse($breadcrumbs);
        $breadcrumbs[] = array(
            $category->seo_url,
            word_limiter($category->name, 10),
            $category->name
        );*/

        page_info('breadcrumbs', $breadcrumbs);
    }


    /**===============================
     *          HANDLE ACTION
     * =================================*/
    /**
     * Gui yeu cau gop y
     */
    function contact()
    {
        if (!user_is_login())
            return;

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        // Tai cac file thanh phan
        $this->data['user'] = user_get_account_info();
        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('contact_name', 'email', 'subject', 'message', 'security_code');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                lib('captcha')->del();
                // Them du lieu vao data
                // Cap nhat vao data
                $data = array();
                $data['name'] = strip_tags($this->input->post('contact_name'));
                $data['email'] = $this->input->post('email');
                $data['subject'] = strip_tags($this->input->post('subject'));
                $data['message'] = strip_tags($this->input->post('message'));
                $data['created'] = now();
                model('contact')->create($data);
                set_message(lang('notice_send_contact_success'));
                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['reload'] = 1;
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            $output = json_encode($result);
            set_output('json', $output);
        }

    }

    /**
     * Gui yeu cau phim
     */
    function request()
    {
        if (!user_is_login())
            return;

        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        // Tai cac file thanh phan
        $this->data['user'] = user_get_account_info();
        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('name', 'security_code');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                lib('captcha')->del();
                // Them du lieu vao data
                $data = array();
                $data['name'] = $this->input->post('name');
                $data['content'] = $this->input->post('content');
                $data ['user_id'] = $this->data['user']->id;
                $data['created'] = now();
                model('product_request')->create($data);
                set_message(lang('notice_request_success'));
                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;
                $result['reload'] = 1;
            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }

            }

            $output = json_encode($result);
            set_output('json', $output);
        }

    }

    function share_to_watch()
    {

        if ($this->input->post('_submit')) {
            $id = $this->input->post("id", true);
            $info = $this->_model()->get_info($id);
            if (!$info)
                set_output('text', 0);
            $info = site_create_url('product', $info);

            $count_save = $this->session->userdata('count_product_' . $info->id . '_share');
            $count_share = $this->_fb_url_get_shares($info->_url_view);

            //echo '$count_save='.$count_save;
            //echo '$count_share='.$count_share;

            if ($count_share > $count_save) {
                $shares = get_cookie('share_products');

                //$info->id =5;

                if (!empty($shares) && $shares != 'null')// neu chua luu thi luu lai
                {

                    $shares = json_decode($shares);
                    //$shares=security_encrypt($shares,'decode');
                } else
                    $shares = array();


                //pr($shares);
                if (!in_array($info->id, $shares)) {
                    $shares = array_merge($shares, array($info->id));
                    $shares = json_encode($shares);
                    //$shares=security_encrypt($shares,'encode');

                    set_cookie('share_products', $shares, 365 * 24 * 60 * 60);


                }
                set_output('text', 1);

            } else {

                set_output('text', 0);
            }


        }
    }


    /*
  * ------------------------------------------------------
  *  Action handle
  * ------------------------------------------------------
  */

    protected function _action($action)
    {
        $dont_check_login = array('demo', 'report', 'favorite', 'favorite_del',/*'vote',*/);
        if (!in_array($action, $dont_check_login)) {

            if (!user_is_login()) {
                // $this->_response(array('msg_modal' => lang('notice_please_login_to_use_function')));
                // return;
                $result["modal_box"] = "modal-user-login";
                $this->_response($result);
            }
        }
        // Lay input
        $id = $this->uri->rsegment(3);
        $id = (!$id) ? $this->input->post('id') : $id;

        // Xu ly id
        $id = (!is_numeric($id)) ? 0 : $id;

        // Kiem tra id
        $info = $this->_model()->get_info($id);
        if (!$info) return;
        if (!$info->status) {
            redirect();
        }

        // Kiem tra co the thuc hien hanh dong nay khong
        //if ( !  $this->_mod()->can_do($info, $action)) return;

        $this->data['info'] = $info;
        $this->data['user'] = user_get_account_info();
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        // Chuyen den ham duoc yeu cau
        $this->{'_' . $action}();
    }


    /* Demo
     * */
    function _demo()
    {
        $info = $this->data['info'];
        if (!$info->link_demo) redirect();

        $this->data['info'] = $this->_mod()->add_info($info, true);
        $this->_display();

    }

    /**
     * Yeu thich
     */
    function _vote()
    {
        $act = $this->input->get('act');
        if (!in_array($act, ['like', 'dislike']))
            $this->_response();

        $id = $this->data['info']->id;
        if ($this->data['user']) {
            //kiem tra da luu hay chua
            $data = array();
            $data ['table_name'] = 'product';
            $data ['table_id'] = $this->data['info']->id;
            $data ['user_id'] = $this->data['user']->id;
            $voted = model('social_vote')->get_info_rule(array('table_name' => 'product', 'table_id' => $id, 'user_id' => $this->data['user']->id));
            if ($voted) {
                $data ['updated'] = now();
                $data [$act] =$voted->{$act}?0:1;
                if($act == 'like'){
                    $data ['dislike'] =0;
                }
                else{
                    $data ['like'] =0;
                }

                model('social_vote')->update($voted->id,$data);
            } else {
                //them vao table product_favorite
                $data ['created'] = now();
                $data [$act] = 1;
                model('social_vote')->create($data);
            }
        } else {
            mod('product')->guest_owner_add($id, "voted");;
        }

        $this->_response(array('msg_toast' => lang('notice_product_favorited')));
    }

    /**
     * Yeu thich
     */
    function _favorite()
    {
        $id = $this->data['info']->id;
        if ($this->data['user']) {
            //kiem tra da luu hay chua
            $favorited = model('product_to_favorite')->check_exits(array('product_id' => $id, 'user_id' => $this->data['user']->id));
            if ($favorited) {
                $this->_response(array('msg_toast' => lang('notice_product_favorited')));
            }
            //them vao table product_favorite
            $data = array();
            $data ['product_id'] = $this->data['info']->id;
            $data ['user_id'] = $this->data['user']->id;
            $data ['created'] = now();
            model('product_to_favorite')->create($data);
        } else {
            mod('product')->guest_owner_add($id, "favorited");;
        }

        $this->_response(array('msg_toast' => lang('notice_product_favorited')));
    }

    function _favorite_del()
    {
        $id = $this->data['info']->id;
        if ($this->data['user']) {

            //kiem tra da luu hay chua
            $favorited = model('product_to_favorite')->check_exits(array('product_id' => $id, 'user_id' => $this->data['user']->id));
            if (!$favorited) {
                $this->_response(array('msg_toast' => 'Error'));
            }
            $data = array();
            $data ['product_id'] = $this->data['info']->id;
            $data ['user_id'] = $this->data['user']->id;
            model('product_to_favorite')->del_rule($data);
        } else {
            mod('product')->guest_owner_del($id, "favorited");;
        }
        $this->_response(array('msg_toast' => lang('notice_product_favorited_del_succcess')));

    }


    function _report()
    {
        $info = $this->data['info'];
        // cho phep he thong tu dong bao cao
        $auto = $this->input->get('auto');
        if ($auto) {
            $reported = model('product_report')->check_exits(array('product_id' => $info->id, 'user_id' => 0));
            if ($reported) {
                return;
            }
            $episode = $this->input->get('ep');
            $episode = (int)$episode;
            $data = array();
            $data ['product_id'] = $info->id;
            $data ['episode'] = $episode;
            $data ['user_id'] = 0;
            $data ['content'] = "[Hệ thống tự động thông báo]: Không load được phim";
            if ($episode > 1)
                $data ['content'] .= ", tập " . $episode;
            $data ['created'] = now();
            model('product_report')->create($data);
            return;
        }


        $user = $this->data['user'];
        if ($user)
            $user_id = $user->id;
        else
            return;

        $msg = 'Phim đã được thông báo tới ban quản trị, chúng tôi sẽ sớm kiểm tra lại phim này';
        //kiem tra da luu hay chua
        $reported = model('product_report')->check_exits(array('product_id' => $info->id, 'user_id' => $user_id));
        if ($reported) {
            $this->_response(array('msg_toast' => $msg, 'reload' => 1));
        }


        $this->load->library('form_validation');
        $this->load->helper('form');


        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('content', 'security_code');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                lib('captcha')->del();
                // Them du lieu vao data
                //them vao table product_favorite
                $data = array();
                $data ['product_id'] = $info->id;
                $data ['user_id'] = $user_id;
                $data ['content'] = $this->input->post('content');
                $data ['created'] = now();
                model('product_report')->create($data);
                //$this->_response(array('msg_toast'=>$msg,'reload'=>1));
                set_message(lang($msg));
                redirect($this->_url());

            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            $output = json_encode($result);
            set_output('json', $output);
        }

    }

    function _subscribe()
    {
        $msg = lang('notice_product_subscribe_success');
        //kiem tra da luu hay chua
        $subscribed = model('product_subscribe')->check_exits(array('product_id' => $this->data['info']->id, 'user_id' => $this->data['user']->id));
        if ($subscribed) {
            $this->_response(array('msg_toast' => $msg, 'reload' => 1));

        }

        //them vao table product_subscribe
        $data = array();
        $data ['product_id'] = $this->data['info']->id;
        $data ['user_id'] = $this->data['user']->id;
        $data ['email'] = $this->data['user']->email;
        $data ['name'] = $this->data['user']->name;
        $data ['created'] = now();
        model('product_subscribe')->create($data);
        $this->_response(array('msg_toast' => $msg, 'reload' => 1));
    }

    function _subscribe_del()
    {
        //kiem tra da luu hay chua
        $subscribed = model('product_subscribe')->check_exits(array('product_id' => $this->data['info']->id, 'user_id' => $this->data['user']->id));
        if (!$subscribed) {
            return;
        }
        $data = array();
        $data ['product_id'] = $this->data['info']->id;
        $data ['user_id'] = $this->data['user']->id;
        model('product_subscribe')->del_rule($data);
        $this->_response(array('msg_toast' => lang('notice_product_subscribe_del_succcess'), 'reload' => 1));


    }

    /**
     * theo doi phim (cho phep nhap thong tin email)
     */
    function _subscribe_adv()
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('product_subscribe_model');

        //kiem tra id product
        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('client_name', 'client_email', 'security_code');

            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                if (!user_is_login()) {
                    $subscribeds = get_cookie('subscribed_products');
                    if (!empty($subscribeds) && $subscribeds != 'null')// neu chua luu thi luu lai
                    {
                        $subscribeds = security_encrypt($subscribeds, 'decode');
                        $subscribeds = json_decode($subscribeds);

                    } else
                        $subscribeds = array();


                    //pr($subscribeds);
                    if (in_array($info_id, $subscribeds)) {
                        $result['complete'] = TRUE;
                        $result['reset_form'] = TRUE;
                        $result['msg'] = 'Ban da dang ky';
                        $this->_form_submit_output($result);
                    }

                    //$subscribeds = array_merge($subscribeds,array($info_id));
                    array_unshift($subscribeds, $info_id);// them vao dau mang $compare[]=$info_id;
                    $count = count($subscribeds);
                    if ($count > 8) // neu hon 4 san pham thi bo phan tu cuoi
                        array_pop($subscribeds);

                    $subscribeds = json_encode($subscribeds);
                    $subscribeds = security_encrypt($subscribeds, 'encode');
                    set_cookie('subscribed_products', $subscribeds, 365 * 24 * 60 * 60);

                    $user_id = get_cookie('_client_id');


                } else {
                    $user = user_get_account_info();

                    $where = array();
                    $where ['product_id'] = $id;
                    $where ['user_id'] = $user->id;
                    //kiem tra xem thanh vien da thich phim nay chua
                    $id = $this->product_subscribe_model->get_id($where);
                    if ($id) {
                        $result['complete'] = TRUE;
                        $result['reset_form'] = TRUE;
                        $result['msg'] = 'Ban da dang ky';
                        $this->_form_submit_output($result);
                    }

                    $user_id = $user->id;

                }

                //them vao table product_subscribe
                $data = array();
                $data ['product_id'] = $info_id;
                $data ['user_id'] = $user_id;
                $data ['email'] = $this->input->post('client_email');
                $data ['name'] = $this->input->post('client_name');
                $data ['created'] = now();
                $this->product_subscribe_model->create($data);


                // Khai bao du lieu tra ve
                $result['complete'] = TRUE;

                $result['reset_form'] = TRUE;
                //$result['msg']        = 'Gửi thành công';

            } else {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }
            //Form Submit
            $this->_form_submit_output($result);
        }

    }

    /**
     * Danh gia sản phẩm
     */
    function _raty()
    {
        $result = array();

        // Lay thong tin
        /* $id = $this->uri->rsegment(3);
         $id = (!is_numeric($id)) ? 0 : $id;
         $info = $this->product_model->get_info($id);
         if (!$info) {
             return false;
         }*/
        $info = $this->data['info'];
        $id = $info->id;
        //kiem tra xem khach da binh chon hay chua
        $raty = $this->session->userdata('session_raty');
        $raty = (!is_array($raty)) ? array() : $raty;
        $result = array();
        if (isset ($raty [$id])) {
            $this->_response(array('msg_toast' => lang('notice_rated')));
        }
        //cap nhat trang thai da binh chon
        $raty [$id] = TRUE;
        $this->session->set_userdata('session_raty', $raty);

        $score = $this->input->post('score');
        $data = array();
        $data ['rate_total'] = $info->rate_total + $score;
        $data ['rate_count'] = $info->rate_count + 1;
        $data ['rate'] = ($data ['rate_total'] / ($data ['rate_count'] * 5)) * 5;

        $this->product_model->update($id, $data);
        // Khai bao du lieu tra ve
        $this->_response(array('msg_toast' => lang('notice_raty_success')));

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
        $rules['name'] = array('name', 'required|trim|max_length[50]|xss_clean');
        $rules['contact_name'] = array('contact_name', 'required|trim|max_length[50]|xss_clean');
        $rules['email'] = array('email', 'required|trim|xss_clean|valid_email|max_length[50]');
        $rules['content'] = array('content', 'required|trim|xss_clean|max_length[255]');

        $rules['subject'] = array('subject', 'required|trim|max_length[255]|xss_clean');
        $rules['message'] = array('message', 'required|trim|xss_clean|min_length[10]|max_length[255]');

        $rules['security_code'] = array('security_code', 'required|trim|callback__check_security_code');
        $rules['user'] = array('user', 'callback__check_user');
        $rules['rate'] = array('rate', 'required|trim|xss_clean|greater_than[0]|less_than[101]');
        $rules['product_id'] = array('product', 'required|trim|callback__check_product_id');

        $rules['client_name'] = array('client_name', 'required|trim|xss_clean');
        $rules['client_email'] = array('client_email', 'required|trim|xss_clean|valid_email');


        $rules['question'] = array('question', 'required|trim|xss_clean|min_length[6]|max_length[255]');

        $this->form_validation->set_rules_params($params, $rules);
    }

    /**
     * Kiem tra ma bao mat
     */

    public
    function _check_security_code($value)
    {
        if (!lib('captcha')->check($value, 'four')) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return FALSE;
        }

        return TRUE;
    }
}