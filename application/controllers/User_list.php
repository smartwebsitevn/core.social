<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_list extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Tai cac file thanh phan
        $this->load->language('site/user');
        $this->load->language('site/user_list');
    }
      public function all()
    {
        $this->_display();
    }

    /**
     * All user page display
     *
     *
     */
    public function index()
    {
        // redirect($this->_url("all"));
        $this->_create_list();
        $this->data['page'] = 'all';

        $this->_display();
    }


    /**
     * Category pages
     *
     * @param  [type] $param [description]
     * @return [type]        [description]
     *
     */
    public function follow()
    {
        $user = user_get_account_info();
        if($user){
            $input['where']['us.action'] = 'follow';
            $input['where']['us.table'] = 'user';
            $input['where']['us.user_id'] =$user->id;
            $input['join'] =array(array('user_storage us','us.user_id = user.id'));
            $filter = array();
            $this->_create_list($input, $filter);
        }
        else
            $this->data['list'] = null;
        $this->data['page'] = 'follow';

        $this->_display();
    }

    public function follow_me()
    {
        $user = user_get_account_info();
        if($user){
            $input['where']['us.action'] = 'follow';
            $input['where']['us.table'] = 'user';
            $input['where']['us.table_id'] =$user->id;
            $input['join'] =array(array('user_storage us','us.user_id = user.id'));
            $filter = array();
            $this->_create_list($input, $filter);
        }
        else
            $this->data['list'] = null;
        $this->data['page'] = 'follow_me';

        $this->_display();
    }
    //====================== Tao danh sach hien thi ===========================
    private function _create_list($input = array(), $filter = array(), $filter_fields = array())
    {
        $filter_input = array();
        $filter_fields = array_merge($filter_fields, model('user')->fields_filter);
        $mod_filter = mod('user')->create_filter($filter_fields, $filter_input);
        $filter = array_merge( $filter,$mod_filter);
        // pr($filter_input);

        $key = $this->input->get('name');
        $key = str_replace(array('-', '+'), ' ', $key);
        if (isset($filter['name']) && $filter['name']) {
            $filter['%name'] = $key;
            unset($filter['name']);

        }
        $point = $this->input->get('point');
        if ($point) {
            $filter['vote_total_gte'] =$point;

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
            $total = model('user')->filter_get_total($filter, $input);
            $page_size = config('list_limit', 'main');

            $limit = $this->input->get('per_page');
            $limit = min($limit, $total - fmod($total, $page_size));
            $limit = max(0, $limit);
            //== Lay danh sach
            $input['limit'] = array($limit, $page_size);
        }
        //== Sort Order
        $sort_orders = array(
            'id|desc',
            'point_total|desc',
            'post_total|desc',
            'count_view|desc',

            /*'count_buy|desc',
            'new|desc',
            'feature|desc',

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
        $list = model('user')->filter_get_list($filter, $input);
       // pr($filter,0);
        //pr_db($list);
        foreach ($list as $row) {
            $row = mod('user')->add_info($row);
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

        // Lay danh sach country, city
        $this->data['countrys'] = model('country')->filter_get_list(['show' => 1]);
        // $this->data['countrys'] = model('country')->get_grouped();
        // $this->data['citys'] = model('city')->get_list();

        // lay cac loai danh muc
        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }
        // lay cac loai range
        $range_types = mod('range')->get_range_types();
        foreach ($range_types as $t) {
            $this->data['range_type_' . $t] = model('range')->get_type($t);
        }

        // luu lai thong so loc va ket qua
        mod('user')->sess_data_set('list_filter', $filter);// phuc vu loc du lieu
        mod('user')->sess_data_set('list_filter_input', $filter_input);// phuc vu hien thi
        mod('user')->sess_data_set('list_sort_orders', $sort_orders);// phuc vu hien thi
        mod('user')->sess_data_set('list_sort_order', $order);// phuc vu hien thi
        mod('user')->sess_data_set('list_total_rows', $total);// phuc vu hien thi

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
                    $category = model("user_cat")->get_info($cat_id);
            }
            if ($category && isset($category->common_data->display) && $category->common_data->display)
                $style_display = $category->common_data->display;

            $temp = $this->input->get('temp');
            $temp = $temp ? $temp : $style_display;
            $load_more = $this->input->get("load_more", false);
            echo json_encode(
                array(
                    'status' => true,
                    'content' => widget('user')->display_list(
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

    //====================================================================================
    //====================== Danh sach san pham cua thanh vien ===========================
    //====================================================================================
    /**
     * Remap method
     */
    public function _remap($method, $params = array())
    {
        return $this->_remap_action($method, $params, array('ordered','favorited','watched','subscribed'), '_action_user');
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

        //  $this->data['list'] = model('user')->filter_get_list($filter, $input);
        $this->_create_list($input, $filter);

        // khoa hoc mien phi
        $users_free = model('user')->filter_get_list([
            'price_option' => 0
        ], ['order' => array('created', 'DESC')]);
        //pr_db();
        if ($users_free)
            foreach ($users_free as $row) {
                $row = mod('user')->add_info($row);
            }

        $this->data['users_free'] = $users_free;
        // Authors
        $user_authors = array_gets($this->data['list'], 'author_id');
        $author_ids = array_merge($user_authors);

        $this->data['authors'] = null;
        if ($author_ids)
            $this->data['authors'] = model('user_author')->filter_get_list(['id' => $author_ids, 'show' => 1]);


        page_info('breadcrumbs', array('#', lang('my_users'), lang('my_users')));
        page_info('title', lang('my_users'));
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
        $input['select'] = 'user.*';
        $input['where'] = [];
        $input['where']['ptf.user_id'] = $user->id;
        $input['join'] = array(array('user_to_favorite ptf', 'ptf.user_id = user.id'));
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
        $input['select'] = 'user.*';
        $input['where'] = [];
        $input['where']['pts.user_id'] = $user->id;
        $input['join'] = array(array('user_to_subscribe pts', 'pts.user_id = user.id'));
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
        $input['select'] = 'user.*';
        $input['where'] = [];
        $input['where']['ptf.user_id'] = $user->id;
        $input['join'] = array(array('user_to_favorite ptf', 'ptf.user_id = user.id'));
        $this->_create_list($input, $filter, $filter_fields);
        $this->_display('category');
    }

}