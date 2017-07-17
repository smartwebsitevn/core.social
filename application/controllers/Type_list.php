<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Type_list extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->lang->load('site/type');

    }

    public function all()
    {
        $this->_display();
    }

    /**
     * All type page display
     *
     *
     */
    public function index()
    {
        // redirect($this->_url("all"));
        $this->_create_list();
        $this->_display();
    }


    /**
     * Category pages
     *
     * @param  [type] $param [description]
     * @return [type]        [description]
     *
     */
    public function category($id = null)
    {
        // $id = $this->uri->rsegment(3);
        if (!is_numeric($id) && is_slug($id)) {
            // neu la seo url
            $category = model("type_cat")->filter_get_info(array('seo_url' => $id, 'show' => 1));
        } else {
            $category = model("type_cat")->filter_get_info(array('id' => $id, 'show' => 1));
         }
              if (!$category)
            show_404();
        $category = mod("type_cat")->add_info($category);
        $this->data['category'] = $category;

        // Filter set
        $filter = array();
        $filter['cat_id'] = mod('type_cat')->get_child_ids($category->id);

        $this->_create_list([], $filter);
        $title = character_limiter($category->name, 60);
        //== Seo
        if ($category->seo_title)
            $title = $category->seo_title;
        page_info('title', $title);
        if ($category->seo_description)
            page_info('description', character_limiter($category->seo_description, 160));
        if ($category->seo_keywords)
            page_info('keywords', $category->seo_keywords);
        $this->_display();
    }




    public function search()
    {
        $keyword = escape($this->input->get('keyword'));
        if (!$keyword)
            show_404();

        $this->data['keyword'] = $keyword;
        // Filter set
        $filter = array();
        $filter['%name'] = $keyword;
        $input = array();
        $input['order'] = array('created', 'desc');
        $this->_create_list($input, $filter);

        // Gan thong tin page
        page_info('breadcrumbs', array('#', lang('search'), lang('search')));
        page_info('title', lang('search'));
        $this->_display();
    }

    public function tag($id)
    {
        // neu la seo url
        $info = model("tag")->get_info_rule(array('seo_url' => $id, 'status' => 1));
        if (!$info)
            show_404();

        $filter['tags'] = $info->name;
        //pr($info);
        $this->data['tag'] = $info;
        // Tao danh sach
        $this->_create_list(array(), $filter);
        //== Seo
        $breadcrumbs = array();
        $breadcrumbs[] = array('', 'Tag');
        $breadcrumbs[] = array('', $info->name, $info->name);
        page_info('breadcrumbs', $breadcrumbs);
        // Gan thong tin page
        page_info('title', $info->seo_title ? $info->seo_title : $info->name);
        page_info('description', $info->seo_description ? $info->seo_description : null);
        page_info('keywords', $info->seo_keywords ? $info->seo_keywords : null);
        $this->_display();
    }

    public function owner()
    {
        return;
        $is_login = user_is_login();
        if (!$is_login) {
            redirect('index');
        }

        $user = user_get_account_info();


        // Filter set
        $filter = array();
        $filter['user_id'] = $user->id;
        $filter['show'] = '1';

        // Option set
        $input = array();
        $input['order'] = array('created', 'desc');

        //  $this->data['list'] = model('type')->filter_get_list($filter, $input);
        $this->_create_list($input, $filter);

        // khoa hoc mien phi
        $types_free = model('type')->filter_get_list([
            'price_option' => 0
        ], ['order' => array('created', 'DESC')]);
        //pr_db();
        if ($types_free)
            foreach ($types_free as $row) {
                $row = mod('type')->add_info($row);
            }

        $this->data['types_free'] = $types_free;
        // Authors
        $type_authors = array_gets($this->data['list'], 'author_id');
        $author_ids = array_merge($type_authors);

        $this->data['authors'] = null;
        if ($author_ids)
            $this->data['authors'] = model('type_author')->filter_get_list(['id' => $author_ids, 'show' => 1]);


        page_info('breadcrumbs', array('#', lang('my_types'), lang('my_types')));
        page_info('title', lang('my_types'));
        $this->_display();
    }


    /**
     * Tao danh sach hien thi
     */
    private function _create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('type')->fields_filter);
        $mod_filter = mod('type')->create_filter($filter_fields, $filter_input);
        $filter = array_merge( $filter,$mod_filter);
        // pr($filter_input);

        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if ($key) {
            $filter['%name'] = $filter_fields['name'] = $key;
        }
        // Gan filter
        $filter['show'] = 1;

        //== Lay tong so
        if (!isset($input['limit'])) {
            $total = model('type')->filter_get_total($filter, $input);
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

        /*if (!isset($input['order'])) {
            $input['order'] = array($orderex[0], $orderex[1]);
        } */
        /*$order_f = $this->input->get("order_f", true);
        if (empty($order_f) || !in_array($order_f, array('id', 'name', 'year', 'view_total', 'imdb', 'rate')))
            $order_f = 'id';
        $order_d = $this->input->get("order_d", true);
        if (empty($order_d) || !in_array($order_f, array('asc', 'desc')))
            $order_d = 'desc';
        $input['order'] = array($order_f, $order_d);
        //pr($input['order']);
        $this->data['ordering_f'] = $order_f;
        $this->data['ordering_d'] = $order_d;
        $filter_input['order_f'] = $order_f;
        $filter_input['order_d'] = $order_d;*/
        $list = model('type')->filter_get_list($filter, $input);
        //pr_db($list);
        foreach ($list as $row) {
            $row = mod('type')->add_info($row);

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
        /* $this->data['types'] = mod("type")->config('movie_types');
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
        mod('type')->sess_data_set('list_filter', $filter);// phuc vu loc du lieu
        mod('type')->sess_data_set('list_filter_input', $filter_input);// phuc vu hien thi
        mod('type')->sess_data_set('list_sort_orders', $sort_orders);// phuc vu hien thi
        mod('type')->sess_data_set('list_sort_order', $order);// phuc vu hien thi
        mod('type')->sess_data_set('list_total_rows', $total);// phuc vu hien thi


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
                    $category = model("type_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);
            echo json_encode(
                array(
                    'status' => true,
                    'content' => widget('type')->display_list(
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