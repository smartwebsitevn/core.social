<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_checkout extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Lay thong tin user

        if(mod('product')->setting('turn_off_function_order'))
            redirect();

        $this->data['user'] = user_get_account_info();

        $checkout = $this->_get_info_checkout();
        $this->data['checkout'] =$checkout;
        $this->data['countries'] = model('country')->filter_get_list(['show' => 1]);
        $this->data['cities'] = null;
        if (isset($checkout['country']) &&$checkout['country'])
            $this->data['cities'] = model('city')->filter_get_list(array('country_id' =>$checkout['country']));
        // Tai cac file thanh phan
        $this->load->language('site/product');
        $this->load->language('site/checkout');
    }
    protected function _get_mod()
    {
        return 'product';
    }

    /**
     * Checkout page render
     *
     */
    public function index()
    {
        $params= $this->_get_params('info');

        if($shipping_to_other_address = $this->input->post('shipping_to_other_address'))
            $params =array_merge($params, $this->_get_params('info_shipping'));
        if($get_gtgt = $this->input->post('get_gtgt'))
            $params =array_merge($params, $this->_get_params('info_company'));

        $form = array();
        $form['validation']['params'] =$params;
        $form['submit'] = function () use($shipping_to_other_address,$get_gtgt) {
            $data = $this->_get_inputs('info');
            // if($shipping_to_other_address)
            $data =array_merge($data,$this->_get_inputs('info_shipping'));
            //if($get_gtgt)
            $data =array_merge($data,$this->_get_inputs('info_company'));


            //pr($checkout);
            $order_quick= mod('product')->setting('product_checkout_quick');
            if ($order_quick)// che do dat hang nhanh
            {
                //pr($data);
                $invoice = $this->_create_order($data);

                $notice= mod('notice')->get('product_order_success',[]);
                $rs = array('msg_modal_title' => $notice->name,'msg_modal' => $notice->content);
                $this->_response($rs);

            }
            else{
                $this->_set_info_checkout($data);
                $location = site_url('product_checkout/confirm');

            }


            return $location;
        };
        $form['form'] = function () {
            $this->_display();
        };
        $this->_form($form);
    }

// --------------------------------------------------
// Confirm Order
// --------------------------------------------------

    public function confirm()
    {
        if (t('cart')->total_items() == 0)
            redirect();

        $params =$this->_get_params('confirm');


        $form = array();
        $form['validation']['params'] =$params;//$this->_get_params('confirm');
        $form['submit'] = function ()  {
            $data = $this->_get_inputs('confirm');

            $checkout = $this->_get_info_checkout($data);
            //pr($checkout);
            $invoice = $this->_create_order($checkout);
            if (isset($checkout['payment_id']) && $checkout['payment_id'])
                return $this->_create_tran($invoice, $checkout['payment_id']);
            else
                return site_url('product_checkout/success') . '?inv=' . $invoice['invoice']->id;
        };
        $form['form'] = function () {
            $checkout = $this->data['checkout'];
            $geo_zone_id = $this->_get_geozone($checkout['country'], $checkout['city']);
            $shipping_methods = $this->_get_shipping_method($geo_zone_id);
            $tax_rates = $this->_get_tax_rate($geo_zone_id);
            $this->data['amount'] = t('cart')->total();
            $this->data['geo_zone_id'] = $geo_zone_id;
            $this->data['shipping_methods'] = $shipping_methods;
            $this->data['tax_rates'] = $tax_rates;
            $this->_set_info_checkout(compact('geo_zone_id', 'shipping_methods', 'tax_rates'));
            $this->_display();
        };
        $this->_form($form);
    }

    public function _create_order($checkout)
    {
        $cart = widget('product')->cart_get($checkout);
        $input = [];
        //$input['voucher'] = $voucher;
       // $input['amount'] = t('cart')->total();
        $input['amount'] =$cart['total'];
        $input['cart'] = $cart;
        $input['data'] = $checkout;
        $input['user'] = $this->data['user'];
        $invoice = mod("product")->invoice_create_order($input);
        // huy bo gio hang
        $this->_del_info_checkout();
        //pr($invoice);
        /* $info_contact = $invoice['invoice']->info_contact;
          mod('email')->send('order_active', $info_contact->email, array(
              'name' => $info_contact->name,
              'fee_shipping' => currency_format_amount_default($invoice->fee_shipping),
              'fee_tax' => currency_format_amount_default($invoice->fee_tax),
              'total_amount' => $invoice->_total_amount,
              'shipping_name' => $invoice->_shipping_name,
              'payment_name' => $invoice->_payment_name,
              'id' => $invoice->_id,
              'orders' => $invoice->_title
          ));*/

        return $invoice;
    }

    /**
     * Tạo tran
     */
    private function _create_tran($invoice, $payment_id)
    {
        $location = $this->_mod()->transaction_create($invoice['invoice'], $payment_id);
        return $location;
    }


    /**
     * Show successful page
     *
     */
    public function success()
    {
        // Invoice information
        $invoice_id = $this->input->get('inv');
        $invoice = model('invoice')->get_info($invoice_id);
        $this->data['invoice'] = mod('invoice')->add_info($invoice);

        $this->_display();
    }


    /**
     * Load cities
     */
    public
    function loadCity()
    {
        $id = $_POST['value'];
        $refer = $_POST['refer'];
        $source = $_POST['source'];

        $model = model($source)->filter_get_list(array('country_id' => $id));

        set_output('text', json_encode($model));
    }

    /*
    * ------------------------------------------------------
    *  Prepare data handle
    * ------------------------------------------------------
    */
    protected function _fields($step = 'info')
    {
        // Thiet lap setting mac dinh
        $f = array();
        $f['info'] = array('name', 'email', 'phone', 'city', /*'country',*/ 'address',);
        if( mod("product")->setting('product_order_quick'))
        {
            $cart = widget('product')->cart_get();
            $product_id =$cart['list'][0]->id;
            $product =model('product')->get_info($product_id,'price_is_auction');
            if($product->price_is_auction){
                array_push( $f['info'],'auction_price', 'auction_intro');

            }
        }
        $f['info_shipping'] = array('shipping_to_other_address','shipping_name', 'shipping_email', 'shipping_phone', 'shipping_city', /*'shipping_country',*/ 'shipping_address',);
        $f['info_company'] = array('get_gtgt','company_name', 'company_tax_code', 'company_address',);
        //$f['shipping'] = array('shipping', 'shipping_note','payment', 'payment_id', );
       // $f['payment'] = array( 'payment', 'payment_id',);
         $f['confirm'] = array('shipping', 'shipping_note','payment', 'payment_id', );
       // pr( $f['confirm']);
        return isset($f[$step]) ? $f[$step] : array();
    }


    protected function _get_params($step)
    {
        // $step = $this->input->post('step');
        // if (!$type) return;
        $params = array();
        $params = $this->_fields($step);
        $params [] = '_';

        return $params;
    }

    /**
     * Lay input
     */
    /**
     * Lay input
     */
    protected function _get_inputs($step)
    {
        $data = array();
        $fields = $this->_fields($step);
        foreach ($fields as $f) {
            $v = $this->input->post($f);
            if (is_array($v)) {
                $v = json_encode($v);
            }
            $data[$f] = $v;
        }

        return $data;
    }

    /**
     * Get geo zone base on country and city selected
     * @return  array List of zone id
     *
     */
    protected function _get_geozone($country_id, $city_id)
    {
        $geo_zone_id = array();
        $filter = array(
            'country_id' => $country_id
        );

        if (!$country_id)
            return 0;

        $geo_zone_to_city = model('geo_zone_to_city')->filter_get_list($filter);
        $ids = array_gets($geo_zone_to_city, 'geo_zone_id');
        foreach ($ids as $id) {
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

        return $geo_zone_id;
    }

    protected function _get_tax_rate($geo_zone_id)
    {
        $tax_rates = array();
        if ($geo_zone_id) {
            $tax_rates = model('tax_rate')->filter_get_list(
                array(
                    'geo_zone_id' => $geo_zone_id
                ),
                array('order' => array('created_date', 'DESC'))
            );
        }
        return $tax_rates;
    }

    protected function _get_shipping_method($geo_zone_id)
    {
        $shipping_methods = array();
        if ($geo_zone_id) {
            $shipping_methods = model('shipping_rate')->filter_get_list(array(
                'show' => 1,
                'geo_zone_id' => $geo_zone_id
            ),
                array('order' => array('sort', 'ASC'))
            );
        }
        return $shipping_methods;
    }


    /**
     * Gan dieu kien cho cac bien
     */
    function _set_rules($params)
    {
        $rules = array();

        /*$fields_rule = $this->_fields();
        // pr($fields_rule);
        if ($fields_rule){
            foreach ($params as $key) {
                $fields_rule_default = "required|trim|xss_clean|filter_html" ;
                if (isset($fields_rule[$key]) && $fields_rule[$key]){
                    if(!is_array($fields_rule[$key]))
                        $rules[$key] = array($key,$fields_rule_default.'|'. $fields_rule[$key]);
                    else
                        $rules[$key] = array($fields_rule[$key][0],$fields_rule_default.'|'. $fields_rule[$key][1]);
                }
            }
        }
        return $rules;*/
        $rules['_'] = array('no', 'trim');
        $rules['name'] = array('name', 'required|trim|max_length[255]|xss_clean|filter_html');
        $rules['phone'] = array('phone', 'required|trim|xss_clean|filter_html');
        $rules['email'] = array('email', 'trim|xss_clean|valid_email');
        $rules['country'] = array('country', 'trim|xss_clean|filter_html');
        $rules['city'] = array('city', 'required|trim|xss_clean|filter_html|callback__check_city');
        $rules['address'] = array('address', 'trim|xss_clean|min_length[6]|max_length[255]|filter_html');
        $rules['note'] = array('note', 'trim|xss_clean|min_length[6]|max_length[' . (1024 * 512) . ']|filter_html');

        $rules['shipping_name'] = array('name', 'required|trim|max_length[255]|xss_clean|filter_html');
        $rules['shipping_phone'] = array('phone', 'required|trim|xss_clean|filter_html');
        $rules['shipping_email'] = array('email', 'trim|xss_clean|valid_email');
        $rules['shipping_country'] = array('country', 'trim|xss_clean|filter_html');
        $rules['shipping_city'] = array('city', 'trim|xss_clean|filter_html');
        $rules['shipping_address'] = array('address', 'trim|xss_clean|min_length[6]|max_length[255]|filter_html');
        $rules['shipping_note'] = array('note', 'trim|xss_clean|min_length[6]|max_length[' . (1024 * 512) . ']|filter_html');

        $rules['company_name'] = array('company_name', 'required|trim|max_length[255]|xss_clean|filter_html');
        $rules['company_tax_code'] = array('company_tax_code', 'required|max_length[100]|trim|xss_clean|filter_html');
        $rules['company_address'] = array('company_address', 'required|trim|xss_clean|min_length[6]|max_length[255]|filter_html');


        $rules['voucher'] = array('voucher', 'trim|xss_clean|filter_html|callback__check_voucher');
        $rules['shipping'] = array('shipping', 'trim|xss_clean|filter_html|callback__check_shipping');

        $rules['payment'] = array('payment', 'required|trim|xss_clean|filter_html|callback__check_payment');
        $rules['payment_id'] = array('payment', 'trim|xss_clean|filter_html|callback__check_payment_id');


        //== price auction
        $rules['auction_price'] = array('auction_price', 'required|trim|xss_clean|filter_html|max_length[20]');
        $rules['auction_intro'] = array('auction_intro', 'required|trim|xss_clean|filter_html|max_length[255]');


        $this->form_validation->set_rules_params($params, $rules);
    }

    function _check_voucher($value)
    {
        $type = $this->input->post('type', true);
        // neu la combo thi ko cho ap dung voucher
        if ($type == 'combo') {
            return false;
        }


        $product_id = $this->input->post('product_id', true);
        $code = model('voucher')->get_info_rule(array('key' => trim($value), 'status' => 0));
        if (!$code) {
            return false;
        }
        if (!in_array($code->type, ['coupon', 'buyout'])) {
            return FALSE;
        }
        $e = get_date($code->expired);
        if ($e > now()) {
            return false;
        }

        $setting = json_decode($code->setting);
        switch ($code->type) {
            case 'coupon':
                // kiem tra xem co ap dung rieng cho khoa
                if ($type == 'product' && $setting->product_id) {
                    if (!in_array($product_id, $setting->product_id))
                        return false;
                }
                // kiem tra xem co ap dung rieng cho bai
                if ($type == 'lesson' && $setting->lesson_id) {
                    if (!in_array($product_id, $setting->lesson_id))
                        return false;
                }
                break;
            case 'buyout':
                if ($type == 'product' && $setting->product_id) {
                    if ($product_id != $setting->product_id)
                        return false;
                }
                if ($type == 'lesson' && $setting->lesson_id) {
                    if ($product_id != $setting->lesson_id)
                        return false;
                }
                break;

        }


        return TRUE;

    }

    /**
     * Kiem tra kieu van chuyen
     */
    function _check_coupon($value)
    {
        $where = array();
        $where['code'] = $value;
        $coupon = $this->coupon_model->get_info_rule($where);
        if (!$coupon || ($coupon->number_usered >= $coupon->number_user)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra city
     */
    function _check_city($value)
    {
        $row = model('city')->check_id($value);
        if (!$row) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra city
     */
    function _check_country($value)
    {
        $row = model('country')->check_id($value);
        if (!$row) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra phone
     */
    public function _check_phone($value)
    {
        if (!$value) return TRUE;
        $phone = handle_phone($value);

        if (!valid_phone($phone)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra email nay da duoc su dung chua
     */
    public function _check_email($value)
    {
        t('load')->helper('email');
        if (!valid_email($value) && !valid_phone($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_email_phone_invalid'));
            return FALSE;
        }

        /*if (!valid_email($value)) {
           if (model('user')->has_user($value)) {
               $this->form_validation->set_message(__FUNCTION__, lang('notice_email_already_used'));
               return FALSE;
           }
       }
      else {
           $value = handle_phone($value);
           if (model('user')->has_user($value)) {
               $this->form_validation->set_message(__FUNCTION__, lang('notice_phone_already_used'));
               return FALSE;
           }

       }*/

        return TRUE;
    }

    /**
     * Kiem tra phuong thưc van chuyen
     */
    public function _check_shipping($value)
    {
        return TRUE;
    }

    /**
     * Kiem tra phuong thưc thanh toan
     */
    public function _check_payment($value)
    {
        return TRUE;
    }

    /**
     * Kiem tra phuong thưc thanh toan
     */
    public function _check_payment_id($value)
    {
        $payment = $this->input->post('payment');
        if ($payment == 'payment' && !$value) {
            $this->form_validation->set_message(__FUNCTION__, lang('form_validation_required'));
            return FALSE;
        }
        return TRUE;
    }

    //  ==Lay thong tin giao hang
    public function _get_info_checkout($data=null)
    {
        //$this->_mod()->sess_data_del();
        // neu co truyen data thi merger voi data cu
        if($data)
            $this->_set_info_checkout($data);

        $info = $this->_mod()->sess_data_get();
        $cookie = $this->_get_cookie();
        // cac thong tin dc luu tru trong cookie va session de dung lai
        $_infos = array(
            'user_id', 'name' , 'email' , 'phone' , 'address',      'country', 'city', //'district',
            'shipping', 'shipping_note', 'payment', 'payment_id',       'shipping_name', 'shipping_email', 'shipping_phone', 'shipping_city', 'shipping_country', 'shipping_address',
            'company_name', 'company_tax_code', 'company_address',
        );
        // $info = set_default_value([], $_infos);
        //pr($info);
        foreach($_infos as $k){
            if(!isset($info[$k]))
            {
                $v='';
                if(isset($cookie[$k]))
                    $v = $cookie[$k] ;
                $info[$k] =$v;

            }
        }
       // pr($info);


        $info['country'] = 230; // set mac dinh la vietnam

        //pr($user);
        //== Neu da dang nhap, thi kiem tra cac thong tin ca nhan da co chua, neu chua co thi lay theo tk
        $user = $this->data['user'];
        if ($user) {
            foreach([ 'user_id', 'name' , 'email' , 'phone' , 'address', 'country', 'city',] as $k){
                if(!$info[$k] && isset($user->$k))
                    $info[$k] = $user->$k;
            }
        }


        foreach([ 'country', 'shipping_country',] as $k){
            if( isset($info[$k]) && $info[$k])
            {
                $country = model('country')->get_info($info[$k], 'name');
                if ($country)
                    $info[$k.'_name'] =$country->name;

            }
        }
        foreach([ 'city', 'shipping_city',] as $k){
            if( isset($info[$k]) && $info[$k])
            {
                $city = model('city')->get_info($info[$k], 'name');
                if ($city)
                    $info[$k.'_name'] =$city->name;

            }
        }
        foreach([ 'district', 'shipping_district',] as $k){
            if( isset($info[$k]) && $info[$k])
            {
                $district= model('district')->get_info($info[$k], 'name');
                if ($district)
                    $info[$k.'_name'] =$district->name;

            }
        }

        $info = $info ? $info : null;
       // pr($info);
        return $info;
    }

    public function _set_info_checkout($info)
    {
        $checkout=$this->_get_info_checkout();
        //pr($checkout,0);
        $checkout = array_merge($checkout,$info);
       // pr($checkout);
        $this->_mod()->sess_data_set_array($checkout);
        // luu vao cookie
        $this->_set_cookie($checkout);
    }

    public function _del_info_checkout()
    {
        t('cart')->destroy();
        $this->_mod()->sess_data_del();
    }


    function _set_cookie($data)
    {
        $cookie = json_encode($data);
        $cookie = security_encrypt($cookie, 'encode');
        set_cookie('product_info_checkout', $cookie,30*24*60*60);
    }
    function _get_cookie()
    {
        // Lay cookie
        $cookie = get_cookie('product_info_checkout', TRUE);
        $cookie = security_encrypt($cookie, 'decode');
        $cookie = @json_decode($cookie,1);
        return $cookie;
    }

}
