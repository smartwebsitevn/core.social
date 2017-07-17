<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_list extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(mod('product')->setting('turn_off_function_order'))
            redirect();
        // Tai cac file thanh phan
        $this->load->language('site/product');
    }
      public function all()
    {
        $this->_display();
    }

    /**
     * All product page display
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
            $category = model("product_cat")->filter_get_info(array('seo_url' => $id, 'show' => 1));
        } else {
            $category = model("product_cat")->filter_get_info(array('id' => $id, 'show' => 1));
        }
        if (!$category)
            show_404();
        $category = mod("product_cat")->add_info($category);
        $this->data['category'] = $category;

        // Filter set
        $filter = array();
        $filter['cat_id'] = mod('product_cat')->get_child_ids($category->id);
        $filter['show'] = '1';
        $this->_create_list([], $filter);


        //== Seo
        $title = character_limiter($category->name, 60);
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
        $filter['show'] = '1';
        // Option set
        $input = array();
        $input['order'] = array('created', 'desc');
        $this->_create_list($input, $filter);

        $author_ids = array_gets($this->data['products'], 'author_id');
        $this->data['authors'] = null;
        if ($author_ids)
            $this->data['authors'] = model('product_author')->filter_get_list(['id' => $author_ids, 'show' => 1]);

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


    function guest_favorited()
    {
        $favorieds = $list = mod('product')->guest_owner_get("favorited");;
        $filter = array();
        $filter_fields = array();
        $filter['id'] = $favorieds;
        $this->_create_list([], $filter, $filter_fields);
        $this->_display('category');
    }
    //====================== Tao danh sach hien thi ===========================
    private function _create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('product')->fields_filter);
        $mod_filter = mod('product')->create_filter($filter_fields, $filter_input);
        $filter = array_merge( $filter,$mod_filter);
        // pr($filter_input);

        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if (isset($filter['name']) && $filter['name']) {
            unset($filter['name']);
            $filter['%name'] = $filter_fields['name'] = $key;
        }

        // lay thong tin cua cac khoang tim kiem
        foreach (array('price',) as $range) {
            if (isset($filter[$range])) {
                if (is_array($filter[$range])) {
                    foreach ($filter[$range] as $key => $row) {
                        $filter[$range.'_range'][$key] = model('range')->get($row, $range);
                    }
                } else {
                    $filter[$range.'_range'] = model('range')->get($filter[$range], $range);
                }
                unset($filter[$range]);
            }
        }
        //pr($filter);
        //pr($input);
        // Gan filter
        $filter['show'] = 1;

        //== Lay tong so
        if (!isset($input['limit'])) {
            $total = model('product')->filter_get_total($filter, $input);
            $page_size =17;// config('list_limit', 'main');

            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }
        //== Sort Order
        $sort_orders = array(
            'feature|desc',
            'id|desc',
            //'price|asc',
            //'price|desc',
            'view_total|desc',
            'point_total|desc',
            /*'count_buy|desc',
            'new|desc',

            'rate|desc',
            'name|asc',*/
        );
        $order = $this->input->get("order", true);
        if ($order && in_array($order, $sort_orders)) {
            $orderex = explode('|', $order);
        } else {
            $orderex = explode('|', $sort_orders[0]);
        }
        /*if (!isset($input['order'])) {
            $input['order'] = array($orderex[0], $orderex[1]);
        }*/
        //pr($filter);
        $list = model('product')->filter_get_list($filter, $input);
       // pr_db($list);
        foreach ($list as $row) {
            $row = mod('product')->add_info($row);
        }
        // Tao chia trang
        $pages_config = array();
        if (isset($total)) {
            $pages_config['page_query_string'] = TRUE;
            $pages_config['base_url'] = current_url() . '?' . url_build_query($filter_input);
            // pr( $pages_config['base_url'] );
            // $pages_config['base_url'] = current_url(1);
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

        // luu lai thong so loc va ket qua
        mod('product')->sess_data_set('list_filter', $filter);// phuc vu loc du lieu
        mod('product')->sess_data_set('list_filter_input', $filter_input);// phuc vu hien thi
        mod('product')->sess_data_set('list_sort_orders', $sort_orders);// phuc vu hien thi
        mod('product')->sess_data_set('list_sort_order', $order);// phuc vu hien thi
        mod('product')->sess_data_set('list_total_rows', $total);// phuc vu hien thi

    }
    /*
 * ------------------------------------------------------
 *  Ajax handle
 * ------------------------------------------------------
 */
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
                    $category = model("product_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);

             $response=   [
                    'status' => true,
                    'total' => number_format($this->data['total']),
                    'content' => widget('product')->display_list(
                        $this->data['list'], $temp,
                        array(
                            'return_data' => 1,
                            'pages_config' => $this->data['pages_config'],
                            'load_more' => $load_more)
                    ),

             ];
            //= su ly hien thi bo loc dong
            if (isset($this->data['filter']['type_cat_id'])) {
                $type_cat_id=$this->data['filter']['type_cat_id'];
                $response['filter']=  widget('type')->filter_types(
                    $type_cat_id, $this->data['filter'],'',[ 'return' => 1]
                );
            }

             echo json_encode($response);
            exit;
        }
    }

    //====================================================================================
    //====================== Danh sach san pham cua thanh vien ===========================
    //====================================================================================
    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('owner','ordered','favorited','watched','subscribed'), '_action_user');
    }

    /**
     * Thanh vien thuc hien hanh dong
     */
    protected function _action_user($action)
    {
        $user = user_get_account_info();
        if (!$user)
            redirect_login_return();

        $this->data['user'] = $user;
        // Chuyen den ham duoc yeu cau
        $this->{'_' . $action}();
    }
    /**
     * San phamda mua cua thanh vien
     */
    public function _owner()
    {
      //  return;
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

        //  $this->data['list'] = model('product')->filter_get_list($filter, $input);
        $this->_create_list($input, $filter);


        page_info('breadcrumbs', array('#', lang('my_products'), lang('my_products')));
        page_info('title', lang('my_products'));
        $this->_display();
    }

    function _ordered()
    {
        $user = $this->data['user'];

        $filter = array();
        $filter_fields = array();
        $input['where'] = [];
        $input['where']['mo.user_id'] = $user->id;
        // join den bang luu tru
        $input['select'] = 'movie.*';
        $input['join'] = array(array('movie_order mo', 'mo.movie_id = movie.id'));
        // Tao danh sach
        $this->_create_list($input, $filter, $filter_fields);

        $this->_display('list');
    }
    /**
     * San pham da thich cua thanh vien
     */

    function _favorited()
    {
        $user = user_get_account_info();
        $filter = array();
        $filter_fields = array();
        $input['select'] = 'product.*';
        $input['where'] = [];
        $input['where']['ptf.user_id'] = $user->id;
        $input['join'] = array(array('product_to_favorite ptf', 'ptf.product_id = product.id'));
        $this->_create_list($input, $filter, $filter_fields);
        $this->_display('category');
    }


    /**
     * San phamda theo doi cua thanh vien
     */
    function _subscribed()
    {
        $user = user_get_account_info();
        $filter = array();
        $filter_fields = array();
        $input['select'] = 'product.*';
        $input['where'] = [];
        $input['where']['pts.user_id'] = $user->id;
        $input['join'] = array(array('product_to_subscribe pts', 'pts.product_id = product.id'));
        $this->_create_list($input, $filter, $filter_fields);
        $this->_display('category');
    }

    /**
     * San phamda xem cua thanh vien
     */
    function _watched()
    {
        $user = user_get_account_info();
        $filter = array();
        $filter_fields = array();
        $input['select'] = 'product.*';
        $input['where'] = [];
        $input['where']['ptf.user_id'] = $user->id;
        $input['join'] = array(array('product_to_favorite ptf', 'ptf.product_id = product.id'));
        $this->_create_list($input, $filter, $filter_fields);
        $this->_display('category');
    }

}