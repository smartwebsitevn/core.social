<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_widget extends MY_Widget
{
    /**
     * Ham khoi dong
     */
    function __construct()
    {
        $this->lang->load('site/product');
    }

    /**
     * Yeu thich
     */

    function owner($type, $temp = '')
    {
        $user = user_get_account_info();
        $total = 0;
        switch ($type) {
            case 'favorited':
                if ($user) {
                    // $can_do = true;
                    $total = model('product_to_favorite')->get_total(['user_id' => $user->id]);
                } else {
                    $list = mod('product')->guest_owner_get($type);
                    $total = count($list);
                }
                break;
        }

        $this->data['total'] = $total;
        $temp = (!$temp) ? 'favorited' : $temp;
        $temp = 'tpl::_widget/product/owner/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    /**===============================
     * HANDLE CHECKOUT - CART
     * =================================*/
    function checkout_steps($step = 1, $data = array())
    {
        $data = array('step' => $step, 'data' => $data);
        return $this->load->view('site/widget/product/cart/checkout_step', $data);
    }



    //Mode: che do hien thi gio hang
    // edit: cho phep khach sua gio hang: them, sua , xoa
    // info: chi hien thi cho xem
    function cart($options = [], $temp = '', $temp_options = array())
    {
        static $cart = null;
        if (is_null($cart))
            if (!isset($options['cart']))
                $cart = $this->cart_get($options);
            else
                $cart = $options['cart'];
        $mode = 'allow_edit';  // hien thi gio hang o che do cho phep sua doi
        if (isset($options['cart_mode']))
            $mode = $options['cart_mode'];
        $this->data = $cart;
        //pr($cart);
        $this->data['is_popup'] = t('input')->is_ajax_request();
        $this->data['cart_mode'] = $mode;
        $temp = (!$temp) ? 'cart' : $temp;
        $temp = 'tpl::_widget/product/cart/' . $temp;
        return $this->_display_temp($temp, $temp_options);
    }

    function cart_get($options = [])
    {
        static $cart = null;
        if (is_null($cart)) {
            $cartItems = t('cart')->contents();
            //pr($cartItems);
            $count = $total = $total_tax = $total_shipping = 0;
            $products = array();
            if (count($cartItems)) {
                foreach ($cartItems as $item) {
                    $tmp = array();
                    foreach ($item as $key => $value) {
                        $tmp[$key] = $value;
                    }
                    $tmp['total_price'] = 0;
                    if ($tmp['price']) {
                        $tmp['total_price'] = $item['qty'] * $tmp['price'] + $tmp['additional_amount'];
                        $total += $tmp['total_price'];
                    }
                    $tmp['tax_value'] = 0;
                    if (isset($options['tax_rates']) && $options['tax_rates']) {
                        $product_tax_value = $this->cart_process_tax_fee($tmp, $options['tax_rates']);
                        $tmp['tax_value'] = $product_tax_value;
                        $total_tax += $product_tax_value;
                    }
                    $products[] = (object)$tmp;
                    $count += $tmp['qty'];
                }

            }
            if (isset($options['shipping']) && $options['shipping_methods']) {
                $total_shipping = $this->cart_process_shipping_fee($options['shipping']);
            }
           // $total = $total + $total_tax + $total_shipping;

            $cart = array(
                'total' => $total,
                'total_tax' => $total_tax,
                'total_shipping' => $total_shipping,
                'list' => $products,
                'count' => $count,

            );
        }
        return $cart;
    }

    // tinh thue uu tien theo khu vuc giao hang den
    function cart_process_tax_fee($tmp, $tax_rates)
    {
        //----------------------------
        // Get tax for this item
        //----------------------------
        $product_tax_value = 0;
        if ($tax_rates && $tmp['price'] && $tmp['tax_class']) {
            $found = null;
            foreach ($tax_rates as $rate) {
                foreach ($tmp['tax_class'] as $rate_id => $piority) {
                    if ($rate_id == $rate->id) {
                        if ($found) {
                            if ($found->piority < $piority) {
                                $found = $rate;
                                $found->piority = $piority;
                            }
                        } else {
                            $found = $rate;
                            $found->piority = $piority;
                        }
                    }
                }
            }

            if ($found) {
                if ($found->type == "P") {
                    $product_tax_value = $tmp['total_price'] * $found->rate / 100;
                } else {
                    $product_tax_value = $found->rate;
                }
            }
        }
        return $product_tax_value;
    }

    function cart_process_shipping_fee($shipping_method)
    {
        $shipping_method = model('shipping_rate')->get_info($shipping_method);
        $shipping_amount = 0;
        if ($shipping_method) {
            $shipping_amount = $shipping_method->cost;
        }
        return $shipping_amount;
    }

    /**===============================
     * HANDLE FILTER
     * =================================*/
    function filter($filter = array(), $temp = '', $temp_options = array())
    {
        //== lay ra thong so loc da luu
        $total_rows = mod('product')->sess_data_get('list_total_rows');
        $filter_input = mod('product')->sess_data_get('list_filter_input');
        $sort_orders = mod('product')->sess_data_get('list_sort_orders');
        $sort_order = mod('product')->sess_data_get('list_sort_order');
        //pr($filter_input);
        if ($filter_input) {
            $filter = array_merge($filter, $filter_input);
        }


        // loc theo tag
        $where = array();
        $input['where']["table"] = 'product';
        //$input['where']["table_cat"] = 'product';
        $input['where']["status"] = 1;
        $input['where']["feature"] = 1;
        $input['order'] = ['tag.id', 'desc'];// ['tag.id', 'desc'] ['tag.count_view', 'desc']['tag.id', 'random'],
        $input['limit'] = array(0, 5);
        $input['join'] = array(array('tag_value tv', 'tv.tag_id = tag.id'));
        $tags = model('tag')->get_list($input);
        //pr_db($tags);
        // $tags = model('tag')->get_list(["status"=>1,"feature"=>1,'']);
        $this->data['tags'] = $tags;
        $this->data['action'] = current_url();
        $this->data['filter'] = $filter;
        $this->data['sort_order'] = $sort_order;
        $this->data['sort_orders'] = $sort_orders;
        $this->data['total_rows'] = $total_rows;


        // loc theo loai dong

        $this->data['type_cats'] = model('type_cat')->get_hierarchy_data();

        // loc theo cac loai danh muc
        $cat_types = mod('cat')->get_cat_types();
        foreach ($cat_types as $t) {
            $this->data['cat_type_' . $t] = model('cat')->get_type($t);
        }
        // Lay danh sach country, city
        $this->data['manufactures'] = model('manufacture')->get_list();
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

        $temp = (!$temp) ? 'filter' : $temp;
        $temp = 'tpl::_widget/product/filter/filter_' . $temp;
        return $this->_display_temp($temp, $temp_options);

    }


    /**===============================
     * HANDLE LIST
     * =================================*/

    /**
     * Hien thi danh sach cung the loai
     */
    function same_cat($cat_id = null, $options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $feature = array_get($options, 'feature', false);
        $product_id = array_get($options, 'product_id', false);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'random'));

        // Create list
        if ($cat_id)
            $filter['cat_id'] = $cat_id;
        if ($product_id)
            $filter['id!'] = $product_id;
        if ($feature)
            $filter['feature'] = 1;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu

        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }
    function same_manufacture($manufacture_id = null, $options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $feature = array_get($options, 'feature', false);
        $product_id = array_get($options, 'product_id', false);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'random'));

        // Create list
        if ($manufacture_id)
            $filter['manufacture_id'] = $manufacture_id;
        if ($product_id)
            $filter['id!'] = $product_id;
        if ($feature)
            $filter['feature'] = 1;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu

        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }


    function same_author($author_id = null, $options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $feature = array_get($options, 'feature', false);
        $product_id = array_get($options, 'product_id', false);
        $limit = array_get($options, 'limit', 5);
        $order = array_get($options, 'order', array('id', 'random'));

        // Create list
        if ($author_id)
            $filter['author_id'] = $author_id;
        if ($product_id)
            $filter['id!'] = $product_id;
        if ($feature)
            $filter['feature'] = 1;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu

        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }
    function slide_show($options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);

        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'desc'));

        $filter['slide'] = TRUE;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);

    }

    function feature($options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);

        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('id', 'random'));

        // Get list
        $filter['feature'] = TRUE;
        //$this->data['url'] = site_url('product_list/home') . '?feature=1' . $type;

        // ==
        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);

        $list = $this->get_list($filter, $input);


        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);

    }

    function newest($options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('updated', 'desc'));


        //$this->data['url'] = site_url('product_list/home') . '?order=id|desc' . $type;

        $input = array();
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);
        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }

    function viewest($options = [], $temp = '', $temp_options = array())
    {
        $filter = array_get($options, 'filter', []);
        $limit = array_get($options, 'limit', 8);
        $order = array_get($options, 'order', array('view_total', 'desc'));
        // Get list
        $input = array();
        // $this->data['url'] = site_url('product_list/home') . '?order=view_total|desc' . $type;
        $input['order'] = $order;
        $input['limit'] = array(0, $limit);
        $list = $this->get_list($filter, $input);
        //== Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->display_list($list, $temp, $temp_options);
        else
            $this->display_list($list, $temp, $temp_options);
    }

    /**
     * Tao danh sach hien thi
     */
    function get_list($filter, $input, $cache = FALSE)
    {
        // Gan filter
        $filter['show'] = '1';
        // Neu su dung cache
        if ($cache) {
            // Tai cac file thanh phan
            $this->load->driver('cache');
            // Lay du lieu trong cache
            $cache_name = 'w_product_' . $cache;
            $list = $this->cache->file->get($cache_name);
            // Neu khong ton tai thi lay trong data va cap nhat lai cache
            if ($list === FALSE) {
                $list = model('product')->filter_get_list($filter, $input);
                $list = $this->_get_list_add_info($list);
                $this->cache->file->save($cache_name, $list, 5 * 60);
            }
        } // Neu khong su dung cache thi get truc tiep tu data
        else {

            $list = model('product')->filter_get_list($filter, $input);
            // pr_db();
            $list = $this->_get_list_add_info($list);
        }
        $this->_ajax_pagination($filter, $input['limit'][1]);

        return $list;
    }

    /*
     * Them thuoc tinh vao Khoa hoc
     */
    function _get_list_add_info($list = array())
    {
        //Danh sach cach product_server dang duoc bat
        /* $inputs = array();
         $inputs['order'] = array('sort_order', 'asc');
         $inputs['where']['status'] = config('status_on', 'main');
         $product_servers = $this->product_server_model->get_list($inputs);*/

        // Tai cac file thanh phan
        //$this->load->helper('file');

        // Xu ly danh sach
        foreach ($list as $row) {
            $row = mod('product')->add_info($row);
            /*foreach (array('cat', 'tag') as $p) {
                $row->$p = model('product')->info_get($p, $row->id);
                foreach ($row->$p as $r) {
                    $r = site_create_url($p, $r);
                }
                // Get cat_ids
                if ($p == 'cat') {
                    $row->_cat_id = array();
                    foreach ($row->cat as $r) {
                        $row->_cat_id[] = $r->id;
                    }
                }
            }*/
        }

        return $list;
    }


    function display_list($list, $temp = '', $temp_options = array())
    {
        $this->data['list'] = $list;
        $this->data['pages_config'] = array_get($temp_options, 'pages_config', null);
        $this->data['load_more'] = array_get($temp_options, 'load_more', null);

        $temp = (!$temp) ? 'default' : $temp;
        $temp = 'tpl::_widget/product/display/list/list_' . $temp;
        // Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
        else
            $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    function display_pagination($pages_config = array(), $temp = '', $temp_options = array())
    {
        $this->data['pages_config'] = $pages_config;

        $temp = (!$temp) ? 'default' : $temp;
        $temp = 'tpl::_widget/product/display/pagination/' . $temp;
        // Su ly hien thi temp hay tra ve du lieu
        $return = array_get($temp_options, 'return_data', false);
        if ($return)
            return $this->_display($this->_make_view($temp, __FUNCTION__), $return);
        else
            $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    function _ajax_pagination($filter, $limit)
    {
        if (isset($filter['hide']))
            unset($filter['hide']);
        $pages_query = http_build_query($filter);
        $this->data['ajax_pagination'] = true;
        $this->data['ajax_pagination_url'] = site_url('product_list/filter_ac?' . $pages_query . '&per_page=' . $limit);
        $total = model('product')->filter_get_total($filter);
        $this->data['ajax_pagination_total'] = ceil($total / $limit);

    }

    /**===============================
     * HANDLE ACTION
     * =================================*/
    /**
     * Thêm vào gi? hàng
     */
    function action_add_cart($product, $temp = '')
    {
        $id = $product->id;

        $this->data['can_do'] = $product->_can_order;
        $this->data['product'] = $product;
        $this->data['product_order_quick'] = mod("product")->setting('product_order_quick');

        $this->data['url_add_cart'] = site_url('product_cart/del/' . $id);
        $this->data['url_add_cart_del'] = site_url('product_cart/del/' . $id);


        $temp = (!$temp) ? 'add_cart' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    /**
     * Vote
     */
    function action_vote($product, $temp = '')
    {
        $id = $product->id;
        $can_do = true;
        $voted = false;
        $user = user_get_account_info();
        if ($user) {
            //kiem tra da luu hay chua
            $data = array();
            $data ['table_name'] = 'product';
            $data ['table_id'] =$id;
            $data ['user_id'] =$user->id;
            $voted = model('social_vote')->get_info_rule(array('table_name' => 'product', 'table_id' => $id, 'user_id' =>$user->id));
        }
       // pr($voted);
        $url_vote= site_url('product/vote/' . $id );;
        $this->data['can_do'] = $can_do;
        $this->data['product'] = $product;
        $this->data['voted'] = $voted;
        $this->data['url_like'] = $url_vote. "?act=like";
        $this->data['url_like_del'] =  $url_vote. "?act=like_del";
        $this->data['url_dislike'] =  $url_vote. "?act=dislike";
        $this->data['url_dislike_del'] = $url_vote. "?act=dislike_del";


        $temp = (!$temp) ? 'vote' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    /**
     * Yeu thich
     */
    function action_favorite($product, $temp = '')
    {
        $id = $product->id;
        $can_do = true;
        $favorited = false;
        $user = user_get_account_info();
        if ($user) {
            // $can_do = true;
            $favorited = model('product_to_favorite')->check_exits(array('product_id' => $id, 'user_id' => $user->id));
        } else {
            $favorieds = $list = mod('product')->guest_owner_get("favorited");;
            // pr($favorieds);
            if (in_array($id, $favorieds)) {
                $favorited = TRUE;
            }
        }

        $this->data['can_do'] = $can_do;
        $this->data['product'] = $product;
        $this->data['favorited'] = $favorited;

        $this->data['url_favorite'] = site_url('product/favorite/' . $id);
        $this->data['url_favorite_del'] = site_url('product/favorite_del/' . $id);


        $temp = (!$temp) ? 'favorite' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }


    /**
     * Danh gia
     */
    function action_raty($product, $temp = '')
    {
        $id = $product->id;
        $can_do = false;
        $rated = false;
        $user = user_get_account_info();
        if ($user) {
            $can_do = true;
            $rated = model('product_to_raty')->check_exits(array('product_id' => $id, 'user_id' => $user->id));
        }


        $this->data['can_do'] = $can_do;
        $this->data['product'] = $product;
        $this->data['rated'] = $rated;

        $this->data['url_raty'] = site_url('product/raty/' . $id);


        $temp = (!$temp) ? 'raty' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    /**
     * Gui yeu cau
     */
    function action_contact($temp = '')
    {
        // Tai cac file thanh phan
        //$this->lang->load('site/product_request');
        $can_do = false;
        $user = user_get_account_info();
        if ($user) {
            $can_do = true;
        }
        $this->data['can_do'] = $can_do;
        $this->data['url_contact'] = site_url('product/contact');
        $this->data['captcha'] = site_url('captcha');

        // Hien thi view
        $temp = (!$temp) ? 'contact' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }

    /**
     * Gui yeu cau
     */
    function action_request($temp = '')
    {
        // Tai cac file thanh phan
        //$this->lang->load('site/product_request');
        $can_do = false;
        $user = user_get_account_info();
        if ($user) {
            $can_do = true;
        }
        $this->data['can_do'] = $can_do;
        $this->data['url_request'] = site_url('product/request');
        $this->data['captcha'] = site_url('captcha');

        // Hien thi view
        $temp = (!$temp) ? 'request' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }


    /**
     * Bao cao
     */
    function action_report($product, $temp = '')
    {
        $id = $product->id;
        $can_do = false;
        $reported = false;
        $user = user_get_account_info();
        if ($user) {
            $can_do = true;
            $reported = model('product_to_report')->check_exits(array('product_id' => $id, 'user_id' => $user->id));
        }

        $this->data['product'] = $product;
        $this->data['can_do'] = $can_do;
        $this->data['reported'] = $reported;
        $this->data['captcha'] = site_url('captcha');
        $this->data['url_report'] = site_url('product/report/' . $id);
        // Hien thi view
        $temp = (!$temp) ? 'report' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }


    /**
     * Dang ky theo doi phim
     */
    function action_subscribe($product, $temp = '')
    {
        $id = $product->id;

        if (!$product || $product->type == mod('product')->config('product_type_product'))
            return;

        // neu la phim bo thi kiem tra xem da up het so tap chua
        if ($product->type == mod('product')->config('product_type_series')) {
            if ($product->episode >= $product->episode_total)
                return;
        }


        /*if(!user_is_login())
        {
            return false;
        }*/
        $can_do = false;
        $subscribed = false;
        if (!user_is_login()) {
            $subscribeds = get_cookie('subscribed_products');
            if (!empty($subscribeds) && $subscribeds != 'null')// neu chua luu thi luu lai
            {
                $subscribeds = json_decode($subscribeds);
                //$subscribeds=security_encrypt($subscribeds,'decode');
            } else
                $subscribeds = array();


            if (in_array($id, $subscribeds)) {
                $subscribed = true;

            }


        } else {
            $user = user_get_account_info();
            $can_do = true;
            $subscribed = model('product_subscribe')->check_exits(array('product_id' => $id, 'user_id' => $user->id));
        }

        $this->data['can_do'] = $can_do;
        $this->data['product'] = $product;
        $this->data['subscribed'] = $subscribed;
        $this->data['url_subscribe'] = site_url('product/subscribe/' . $id);
        $this->data['url_subscribe_del'] = site_url('product/subscribe_del/' . $id);

        $temp = (!$temp) ? 'subscribe' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));

    }


    /**
     * Action khac
     */

    function action_comment($row,$temp = '')
    {
        $this->data['row'] = $row;

        $this->data['url_comment'] = $row->_url_comment;
        // Hien thi view
        $temp = (!$temp) ? 'comment' : $temp;
        $temp = 'tpl::_widget/product/action/'.$temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    function action_share($row,$temp = '')
    {
        $this->data['url_share'] = $row->_url_view;
        // Hien thi view
        $temp = (!$temp) ? 'share' : $temp;
        $temp = 'tpl::_widget/product/action/'.$temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    function action_close($temp = '')
    {
        // Hien thi view
        $temp = (!$temp) ? 'close' : $temp;
        $temp = 'tpl::_widget/product/action/'.$temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }
    function action_toggle_light($temp = '')
    {

        // Hien thi view
        $temp = (!$temp) ? 'toggle_light' : $temp;
        $temp = 'tpl::_widget/product/action/' . $temp;
        $this->_display($this->_make_view($temp, __FUNCTION__));
    }


}