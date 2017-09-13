<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_notice extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ( ! user_is_login())
        {
            redirect_login_return();
        }
        $user = user_get_account_info();
        $this->data['user'] = $user;

        // Tai cac file thanh phan
        $this->lang->load('site/'.$this->_get_mod());
    }
    function index()
    {
        $this->_create_list();
        $this->_display();
    }

    function view()
    {

        //== Lay thong tin
        $id = $this->uri->rsegment(3);
        if (!is_numeric($id) && is_slug($id)) {
            // neu la seo url
             $info = $this->_model()->filter_get_info(array('seo_url' => $id,'show'=>1));
        } else {
            $info = $this->_model()->filter_get_info(array('id' => $id,'show'=>1));
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
        $category = model('user_notice_cat')->get_info($info->cat_id);


        //== Them thong tin
        $info = $this->_mod()->add_info($info);
        $info = $this->_mod()->add_info_images($info);
        $info = $this->_mod()->add_info_files($info);
        // pr($info);

        $this->data['info'] = $info;
        $this->data['category'] = $category;


        //== Breadcrumbs
        $this->_breadcrumbs($category);
        //== Seos
        $title = character_limiter($info->name, 60);
        if ($category->seo_title)
            $title = $category->seo_title;
        page_info('title', $title);
        if ($category->seo_description)
            page_info('description', character_limiter($category->seo_description, 160));
        if ($category->seo_keywords)
            page_info('keywords', $category->seo_keywords);

        $this->_display();

    }
    function view_all(){
        $this->_model()->update_rule(['user_id'=>$this->data['user']->id],['readed' =>1,'readed_time'=>now()]);
        $this->_response(['reload'=>1]);
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

        $parent = $category;

        while ($parent->parent_id) {
            $parent = model('user_notice_cat')->get_info($parent->parent_id);

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
        );

        page_info('breadcrumbs', $breadcrumbs);
    }

    /**
     * Tao danh sach hien thi
     */
    private function _create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $user  = $this->data['user'];
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('user_notice')->fields_filter);
        $mod_filter = mod('user_notice')->create_filter($filter_fields, $filter_input);
        $filter = array_merge( $filter,$mod_filter);
        // pr($filter_input);

        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if ($key) {
            $filter['%name'] = $filter_fields['name'] = $key;
        }
        // Gan filter
        $filter['user_id'] = $user->id;

        $filter['show'] = 1;

        //== Lay tong so
        if (!isset($input['limit'])) {
            $total = model('user_notice')->filter_get_total($filter, $input);
            // pr_db();
            $page_size = config('list_limit', 'main');

            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }

        //== Sort Order
        $sort_orders = array('id|desc', 'name|asc', 'price|desc', 'price|desc',
            'count_view|desc', 'count_buy|desc', 'new|desc', 'feature|desc', 'rate|desc',
        );
        $order = $this->input->get("order", true);
        if ($order && in_array($order, $sort_orders)) {
            $orderex = explode('|', $order);
        } else {
            $orderex = explode('|', $sort_orders[0]);
        }

        $list = model('user_notice')->filter_get_list($filter, $input);
        //pr_db($list);
        foreach ($list as $row) {
            $row = mod('user_notice')->add_info($row);
            if(!$row->readed)
                $row = model('user_notice')->update_field($row->id,'readed',1);

        }

        // Tao chia trang
        $pages_config = array();
        if (isset($total)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
            $pages_config['total_rows'] = $total;
            $pages_config['per_page'] = $page_size;
            $pages_config['cur_page'] = $limit;
        }

        $this->data['pages_config'] = $pages_config;
        $this->data['total'] = $total;
        $this->data['list'] = $list;
        $this->data['filter'] = $filter_input;
        $this->data['sort_orders'] = $sort_orders;
        $this->data['sort_order'] = $order;
        $this->data['action'] = current_url();

        //===== Ajax list====
        $this->_create_list_ajax();

        // lay cac loai danh muc
        /* $this->data['types'] = mod("user_notice")->config('movie_types');
         $cat_types = mod('cat')->get_cat_types();
         foreach ($cat_types as $t) {
             $this->data['cat_type_' . $t] = model('cat')->get_type($t);
         }

         // Lay danh sach country
         $this->data['countries'] = model('country')->get_list();*/

        /*$breadcrumbs = array();
        $breadcrumbs[] = array(site_url('movie'), 'Movie', 'Movie');
        page_info('breadcrumbs', $breadcrumbs);*/

        // Gan thong tin page
        /*  page_info('title', $page->titleweb ? $page->titleweb : $page->title);
          page_info('description', $page->description ? $page->description : $page->title);
          page_info('keywords', $page->keywords ? $page->keywords : $page->title);*/

        // luu lai thong so loc va ket qua
        mod('user_notice')->sess_data_set('list_filter', $filter);// phuc vu loc du lieu
        mod('user_notice')->sess_data_set('list_filter_input', $filter_input);// phuc vu hien thi
        mod('user_notice')->sess_data_set('list_sort_orders', $sort_orders);// phuc vu hien thi
        mod('user_notice')->sess_data_set('list_sort_order', $order);// phuc vu hien thi
        mod('user_notice')->sess_data_set('list_total_rows', $total);// phuc vu hien thi


    }

    private function _create_list_ajax()
    {
        if ($this->input->is_ajax_request()) {
            //= su ly hien thi danh sach theo danh muc
            $category = $style_display = '';
            if (isset($this->data['category'])) {
                $category = $this->data['category'];
            } else {
                $cat_id = $this->input->get('cat_id');
                if ($cat_id)
                    $category = model("user_notice_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);
            echo json_encode(
                array(
                    'status' => true,
                    'content' => widget('user_notice')->display_list(
                        $this->data['list'], $temp,
                        array(
                            'return_data' => 1,
                            'pages_config' => $this->data['pages_config'],
                            'load_more' => $load_more)
                    ),
                    'total' => number_format($this->data['total']))
            );
            exit;
        }
    }
}