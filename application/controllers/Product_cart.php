<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_cart extends MY_Controller
{
    private  $product_order_quick=false;
    public function __construct()
    {
        parent::__construct();
        if (mod('product')->setting('turn_off_function_order'))
            redirect();
        // Tai cac file thanh phan
        $this->load->language('site/product');


        $this->product_order_quick =mod("product")->setting('product_order_quick');
        $this->product_checkout_quick =mod("product")->setting('product_checkout_quick');
    }
    protected function _get_mod()
    {
        return 'product';
    }
    public function index()
    {

        if ($this->input->is_ajax_request()) {
            $this->_display('index', NULL);
        } else {
            $print = $this->input->get('print', TRUE);
            $data['print'] = $print;
            if ($print == 'true')
                $this->_display('print', NULL);
            else
                $this->_display('index');
        }
    }

    /**
     * Add product to cart
     * @return string    Bao loi
     *
     */
    public function add()
    {
        // neu che do mua hang la tung san pham, thi xoa san pham truoc di
        if($this->product_order_quick ){
            t('cart')->destroy();
        }
        //$this->destroy();
        $id = $this->input->post_get('id');
        $data = $this->input->post();
        $qty = array_get($data, 'qty', 1);
        $options = array_get($data, 'options', []);
        $addons = array_get($data, 'addons', []);
        $cart_options = array_merge($options, $addons);
        // pr($cart_options);
        if (!is_numeric($id)){
            $this->_response();
        }

        $product = model('product')->get_info($id);
        if (!$product /*|| !mod('product')->can_do($product, 'order')*/)
            $this->_response();

        $product = mod('product')->add_info_price($product);
        $product = mod('product')->add_info_vat($product);
        $product = mod('product')->add_info_image($product);
        $product = mod('product')->url($product);

        if(isset($data['update_price']) && $data['update_price']){
            $this->_cart_get_total($product, $qty,  $options, $addons);

        }
        else{
            $this->_cart_check_update($product, $qty, $cart_options);
        }
        // Insert product
        $product = mod('product')->add_info_price_cart($product, $qty, $options, $addons);
        $data = array(
            'id' => $product->id,
            'name' => $product->name,
            'thumb' => $product->image->url_thumb,
            'qty' => $qty,
            'price' => $product->_price_amount,
            'price_html' => $product->_price,
            'url_view' => $product->_url_view,
            'tax_class' => $product->_tax_rate_ids,
            'additional_amount' => $product->_additional_amount,
            'option_html' => $product->_option_html,
            'options' => $options
        );

        t('cart')->product_name_rules = '\d\D';
        t('cart')->insert($data);

        $rs = array('msg_toast' => lang('notice_add_to_cart_success'));

        $this->load_cart($rs);
    }
    public function _cart_check_update($product, $qty, $cart_options)
    {
        $flagUpdate = false;
        // pr(t('cart')->total_items());
        //pr(t('cart')->contents() );
        foreach (t('cart')->contents() as $rowid => $row) {
            $row = (object)$row;
            if ($row->id == $product->id && $row->options == $cart_options) {
                //neu da co thi them so luong
                $data = array(
                    'rowid' => $rowid,
                    'qty' => $row->qty + $qty,
                );
                // pr($data);
                t('cart')->update($data);

                $rs = array('msg_toast' => lang('notice_add_to_cart_success'));
                $this->load_cart($rs);

                break;
            }
        }
        //pr(t('cart')->contents());
        if ($flagUpdate) {
            $this->_response(array('msg_toast' => lang('notice_add_to_cart_success')));
        }
    }
    public function _cart_get_total($product, $qty,  $options, $addons)
    {
        $product = mod('product')->add_info_price_cart($product, $qty, $options, $addons);
        $price_suffix= $product->price_suffix?'/'.$product->price_suffix:'';

        //  pr($product);
        $total_price= $qty * $product->_price_amount + $product->_additional_amount;
        $total_price = currency_format_amount($total_price).$price_suffix;
        $this->_response(array('total' =>$total_price));
    }
    /**
     * Update product to cart
     * @return string    Bao loi
     *
     */
    public function update($rowid = null, $qty = null)
    {
        if (!$rowid)
            $rowid = $this->input->post('rowid');
        if (!$qty)
            $qty = $this->input->post('qty');

        $flagUpdate = null;
        foreach (t('cart')->contents() as $row) {
            $row = (object)$row;
            if ($row->rowid == $rowid) {
                $flagUpdate = $row;
                break;
            }
        }

        if ($flagUpdate) {
            $product = model('product')->get_info($flagUpdate->id);
            $product = mod('product')->add_info_price($product);
            $product = mod('product')->add_info_vat($product);
            $product = mod('product')->add_info_price_cart($product, $qty, $flagUpdate->options);

            $data = array(
                'rowid' => $flagUpdate->rowid,
                'qty' => $qty,
                'price' => $product->_price_amount,
                'price_html' => $product->_price,
                'additional_amount' => $product->_additional_amount,
                'option_html' => $product->_option_html
            );

            t('cart')->update($data);
            $rs = array('msg_toast' => lang('notice_cart_update_success'));
            $this->load_cart($rs);

        }
    }


    /**
     * Delete product in cart
     * @return string    Bao loi
     *
     */
    public function delete()
    {
        $rowid = $this->input->post('rowid');

        $flagDelete = false;
        foreach (t('cart')->contents() as $row) {
            $row = (object)$row;
            if ($row->rowid == $rowid) {
                $flagDelete = $row->rowid;
                break;
            }
        }

        if ($flagDelete) {
            $data = array(
                'rowid' => $flagDelete,
                'qty' => 0
            );
            t('cart')->update($data);
            $rs = array('msg_toast' => lang('notice_cart_delete_success'));
            $this->load_cart($rs);
        }
        /* else
         {
             $this->_response(array('msg_toast' => lang('product_not_found_in_cart')));

         }*/
    }


    public function destroy()
    {
        t('cart')->destroy();
        $rs = array('msg_toast' => lang('notice_cart_destroy_success'));
        $this->load_cart($rs);
    }


    /**
     * Get shipping methods
     * base on shipping address
     *
     */
    public function location()
    {
        $currency = model('currency')->get_default();
        $country_id = $this->input->post('country_id');
        $city_id = $this->input->post('city_id');
        if (!is_numeric($country_id) || !is_numeric($city_id))
            return;

        $filter = array();
        $filter['country_id'] = $country_id;

        // Find all geo zone of this country
        $geo_zone_to_city = model('geo_zone_to_city')->filter_get_list($filter);
        $geo_zone_id = array();
        $tmp = array_gets($geo_zone_to_city, 'geo_zone_id');
        foreach ($tmp as $id) {
            $flag = false;

            foreach ($geo_zone_to_city as $row) {
                if ($row->geo_zone_id != $id)
                    continue;

                if (!$row->negative) {
                    if (($city_id == $row->city_id || $row->city_id == 0) && $city_id != 0)
                        $flag = true;
                }
            }

            foreach ($geo_zone_to_city as $row) {
                if ($row->geo_zone_id != $id)
                    continue;

                if ($row->negative) {
                    if ($city_id == $row->city_id || $city_id == 0)
                        $flag = false;
                }
            }

            if ($flag)
                $geo_zone_id[] = $id;
        }

        $shipping_methods = null;
        if ($geo_zone_id) {
            $shipping_methods = model('shipping_rate')->filter_get_list(array(
                'show' => 1,
                'geo_zone_id' => $geo_zone_id
            ),
                array('order' => array('sort', 'ASC'))
            );
        }

        $tax_rates = null;
        if ($geo_zone_id) {
            $tax_rates = model('tax_rate')->filter_get_list(
                array(
                    'geo_zone_id' => $geo_zone_id
                ),
                array('order' => array('created_date', 'DESC'))
            );
        }


        $output = array();
        if ($shipping_methods) {
            $output['shipping_home'] = true;
            $output['shipping_methods'] = $shipping_methods;
        } else
            $output['shipping_home'] = false;

        if ($tax_rates)
            $output['tax_rates'] = $tax_rates;

        set_output('text', json_encode($output));
    }

    /**
     * Ajax load widget cart products
     * @return array        Danh sach san pham trong gio hang
     *
     */
    public function load_cart($rs = [])
    {
        if ($this->product_order_quick) {

            $rs=[];// huy bo thong bao
            if ($this->product_checkout_quick) {
                //== kiem tra san pham co phai la loai dau gia khong
                $cart = widget('product')->cart_get();
                $product_id =$cart['list'][0]->id;
                $product =model('product')->get_info($product_id,'price_is_auction');
                $tmp= 'cart_checkout_quick';
                if($product->price_is_auction){
                    $tmp= 'cart_checkout_auction_quick';
                }
                //pr($product_id);
               // pr($cart);

                $rs["checkout"] = widget("product")->cart(['cart_mode' => 'view'], $tmp, ['return' => 1]);
            } else {
                $rs["location"] = site_url('product_checkout');

            }
        } else {
            // t('cart')->destroy();
            $rs["cart"] = widget("product")->cart(null, 'cart', ['return' => 1]);
            $rs["cart_mini"] = widget("product")->cart(null, 'cart_mini', ['return' => 1]);
        }

        $this->_response($rs);
    }

}