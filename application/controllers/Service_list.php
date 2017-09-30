<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_list extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->lang->load('site/service');

    }

    public function all()
    {
        $this->_display();
    }

    /**
     * All service page display
     *
     *
     */
    public function index()
    {
        redirect();
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
            $category = model("service_cat")->filter_get_info(array('seo_url' => $id, 'show' => 1));
        } else {
            $category = model("service_cat")->filter_get_info(array('id' => $id, 'show' => 1));
        }
        if (!$category)
            show_404();
        $category = mod("service_cat")->add_info($category);
        $this->data['category'] = $category;

        // Filter set
        $filter = array();
        $filter['cat_id'] = mod('service_cat')->get_child_ids($category->id);

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


    /**
     * Tao danh sach hien thi
     */
    private function _create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('service')->fields_filter);
        $mod_filter = mod('service')->create_filter($filter_fields, $filter_input);
        $filter = array_merge($filter,$mod_filter);
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
            $total = model('service')->filter_get_total($filter, $input);
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
            $input['order'] = array($orderex[0], $orderex[1]);
            $filter_input['order']=$order;
        }
        /*else {
            $orderex = explode('|', $sort_orders[0]);
        }
        if (!isset($input['order'])) {
            $input['order'] = array($orderex[0], $orderex[1]);
            //$input['order'] = array(array('sort_order', 'asc'), array('id', 'desc'));

        }*/
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
        $list = model('service')->filter_get_list($filter, $input);
        //pr_db($list);
        foreach ($list as $row) {
            $row = mod('service')->add_info($row);

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

        // Ajax list
        if ($this->input->is_ajax_request()) {
            //= su ly hien thi danh sach theo danh muc
            $category= $style_display = '';
            if (isset($this->data['category'])) {
                $category = $this->data['category'];
            } else {
                $cat_id = $this->input->get('cat_id');
                if ($cat_id)
                    $category = model("service_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            echo json_encode(array('status' => true,
                'content' => widget('service')->display_list($list, $temp, array('return_data' => 1, 'pages_config' => $this->data['pages_config'])),
                'total' => number_format($total)));
            exit;
        }


        $this->data['list'] = $list;
        $this->data['filter'] = $filter_input;
        $this->data['sort_orders'] = $sort_orders;
        $this->data['sort_order'] = $order;
        $this->data['action'] = current_url();
        // lay cac loai danh muc

        $service_list=model('service')->filter_get_list(['show'=>1,'cat_id'=>$this->data['category']->id]);

        foreach($service_list as $row){
            $row =mod('service')->add_info_url($row);
        }
        $this->data['service_list'] =$service_list;
        /* $this->data['types'] = mod("service")->config('movie_types');
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
    }
}