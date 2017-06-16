<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mod Library Class
 *
 * @author        ***
 * @version        2015-04-03
 */

// --------------------------------------------------------------------

/**
 * Class de goi cac mod
 */
class Mod_library
{

    /**
     * Goi cac mod duoc yeu cau
     */
    public function __get($key)
    {
        return t('lib')->driver('mod', $key);
    }

}

// --------------------------------------------------------------------

/**
 * Class xay dung cho cac mod
 */
class MY_Mod
{
    /**
     * Cho phep su dung cac thuoc tinh cua controller
     */
    public function __get($key)
    {
        return t($key);
    }

    /**
     * Lay doi tuong cua model hien tai
     *
     * @return MY_Model
     */
    protected function _model()
    {
        return model($this->_get_mod());
    }

    /**
     * Lay key cua mod hien tai
     *
     * @return string
     */
    protected function _get_mod()
    {
        $key = strtolower(get_class($this));

        $key = preg_replace('#_mod$#', '', $key);

        return $key;
    }

    function get_key()
    {

        return $this->_get_mod();
    }

    /**
     * Lay thong tin cua cac table
     *
     * @param string $table
     * @param array $order
     * @return array
     */
    public function get_table($table, $order = array('order', 'asc'))
    {
        $tbl = model('module')->table_get_db_name($this->_get_mod(), $table);

        return model('db')->get($tbl, $order);
    }

    /**
     * Lay row cua table
     *
     * @param string $table
     * @param string $id
     * @return false|object
     */
    public function get_table_row($table, $id)
    {
        $tbl = model('module')->table_get_db_name($this->_get_mod(), $table);

        return model('db')->row($tbl, $id);
    }


    /**
     * Lay config
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function config($key = null, $default = null)
    {
        static $config = array();
        if (!$config) {
            t('config')->load('mod/' . $this->_get_mod(), true, true);
            $config = config('mod/' . $this->_get_mod(), '');
        }


        return array_get($config, $key, $default);
    }


    public function setting($key = null, $default = null)
    {
        static $setting = array();
        if (!$setting) {
            $setting = module_get_setting($this->_get_mod());
            $setting = $setting ?: array();
        }
        /*$setting = array_add($setting, 'fail_count_max', 10);
        $setting = array_add($setting, 'fail_block_timeout', 60);

        $setting['offline_providers'] = $this->get_table('offline_providers');
        */

        return array_get($setting, $key, $default);
    }

    /**
     * Tao filter tu input
     *
     * @param array $fields
     * @param array $input
     * @return array
     */
    public function create_filter(array $fields, &$input = array())
    {
        // Lay gia tri cua filter dau vao
        $input = array();
        foreach ($fields as $f) {
            $v = $this->input->get($f);
            //$v = security_handle_input($v, in_array($f, array()));
            $input[$f] = $v;
        }

        if (!empty($input['id'])) {
            foreach ($input as $f => $v) {
                $input[$f] = ($f != 'id') ? '' : $v;
            }
        }

        // Tao bien filter
        $filter = array();
        $query = url_build_query($input, TRUE);
        foreach ($query as $f => $v) {
              if (is_null($v)) {
                continue;
            }

            $filter[$f] = $v;
        }

        return $filter;
    }

    /**
     * Ly danh sach
     *
     * @param array $filter
     * @param array $input
     * @return array
     */
    public function get_list(array $filter = array(), array $input = array())
    {
        $list = $this->_model()->filter_get_list($filter, $input);
        foreach ($list as $row) {
            $row = $this->add_info($row);
            $row = $this->url($row);
        }

        return $list;
    }

    /**
     * Lay thong tin
     *
     * @param int $id
     * @return object
     */
    public function get_info($id)
    {
        $row = $this->_model()->get_info($id);

        if ($row) {
            $row = $this->add_info($row);
            $row = $this->url($row);
        }

        return $row;
    }

    /**
     * Them cac thong tin phu
     *
     * @param object $row
     * @return object
     */
    public function add_info($row)
    {

        $this->add_info_time($row);
        $this->add_info_status($row);
        $this->add_info_content($row);
        $this->add_info_list_comma($row);
        $this->add_info_list_json($row);
        $this->add_info_relation_cat($row);
        $this->add_info_relation_cat_multi($row);
        $this->add_info_url($row);
        $this->add_info_image($row);
        //$this->add_info_images($row);
        //$this->add_info_files($row);

        return $row;
    }

    public function add_info_time($row)
    {
        if (!$row)
            return $row;
        foreach (array('created', 'updated', 'processed') as $p) {
            if (isset($row->$p) && $row->$p) {
                $row->{'_' . $p} = ($row->$p) ? format_date($row->$p) : '';
                $row->{'_' . $p . '_time'} = ($row->$p) ? format_date($row->$p, 'time') : '';
                $row->{'_' . $p . '_full'} = ($row->$p) ? format_date($row->$p, 'full') : '';
            }
        }
        return $row;
    }

    public function add_info_status($row)
    {
        if (!$row)
            return $row;
        foreach (array('is_feature', 'is_new', 'is_soon', 'is_slide', 'is_live', 'status') as $p) {
            if (isset($row->$p)) {
                $row->{'_' . $p} = ($row->$p) ? 'on' : 'off';
            }
        }
        return $row;
    }

    public function add_info_image($row)
    {
        if (!$row)
            return $row;
        foreach ($this->_model()->fields_type_image as $p) {
            $p_name = $p . '_name';
            if (isset($row->$p_name)) {
                $row->$p = file_get_image_from_name($row->$p_name, public_url('site/images/no_' . $p . '.png'));
            }
        }
        return $row;
    }

    public function add_info_images($row, $field = "images")
    {
        if (!$row)
            return $row;
        $list = model("file")->get_list_of_mod($this->_get_mod(), $row->id, $field);
        if ($list) {
            foreach ($list as $r) {
                $r = file_add_info($r);
            }
        }
        $row->$field = $list;
        return $row;
    }

    public function add_info_files($row, $field = "files")
    {
        if (!$row)
            return $row;
        $list = model("file")->get_list_of_mod($this->_get_mod(), $row->id, $field);
        if ($list) {
            foreach ($list as $r) {
                $r = file_add_info($r);
            }
        }
        $row->$field = $list;
        return $row;
    }

    public function add_info_content($row)
    {
        if (!$row)
            return $row;
        foreach ($this->_model()->fields_type_content as $p) {
            if (isset($row->$p) && $row->$p) {
                $row->{$p} = handle_content($row->$p, 'output');
            }
        }
        return $row;
    }
    public function add_info_list_comma($row)
    {
        if (!$row)
            return $row;
        foreach ($this->_model()->fields_type_list_comma as $p) {
            if (isset($row->$p) && $row->$p) {
                $row->{'_' . $p} = is_string($row->{$p}) ? explode(',',$row->{$p}) : $row->{$p};

            }
        }
    }

    public function add_info_list_json($row)
    {
        if (!$row)
            return $row;
        foreach ($this->_model()->fields_type_list_json as $p) {
            if (isset($row->$p) && $row->$p) {
                $row->{'_' . $p} = is_string($row->{$p}) ? json_decode($row->{$p}) : $row->{$p};

            }
        }
        return $row;
    }

    public function add_info_relation_cat($row)
    {
        if (!$row)
            return $row;
        foreach ($this->_model()->fields_type_relation_cat as $p) {
            $p_id = $p . '_id';
            if (isset($row->$p_id) && $row->$p_id) {
                $name = '';
                $it = model('cat')->get($row->$p_id);
                if ($it) {
                    $name = $it->name;
                }
                $row->{"_" . $p} = $it;
                $row->{"_" . $p . "_name"} = $name;
            }
        }
        return $row;
    }

    public function add_info_relation_cat_multi($row)
    {
        if (!$row)
            return $row;
        foreach ($this->_model()->fields_type_relation_cat_multi as $p) {
            $p_id = $p . '_id';
            if (isset($row->$p_id) && $row->$p_id) {
                $names = array();
                $list = array();
                $ids = explode(',', $row->$p_id);
                foreach ($ids as $id) {
                    $it = model('cat')->get($id);
                    if ($it) {
                        $list[] = $it;
                        $names[] = $it->name;
                    }
                }
                $row->{"_" . $p_id} = $ids;
                $row->{"_" . $p} = $list;
                $row->{"_" . $p . "_name"} = implode(', ', $names);
            }
        }
        return $row;
    }

    public function add_info_url($row)
    {
        if (!$row)
            return $row;
        $row = $this->url($row);
        return $row;
    }

    /**
     * Kiem tra co the thuc hien hanh dong hay khong
     *
     * @param object $row
     * @param string $action
     * @return boolean
     */
    public function can_do($row, $action)
    {
        if (!$row) return false;

        switch ($action) {
            case 'add': {
                return true;
            }
            case 'edit': {
                return true;
            }

            case 'del': {
                //pr($row,false);
                if (isset($row->protected) && $row->protected)
                    return false;

                return true;
            }

            case 'view': {
                if (isset($row->status)) {
                    return ($row->status == config('status_on', 'main')) ? true : false;
                }
                return true;
            }

            case 'translate': {
                return ($this->_model()->translate_auto && config('language_multi', 'main'));
            }
            case 'on': {
                return !$row->status;
            }

            case 'off': {
                return $row->status;
            }

            case 'feature': {
                return true;
            }

            case 'feature_del': {
                $p = obj_true_name($row, ["feature", 'is_feature']);
                return $row->$p;
            }

            case 'show_in_menu': {
                $p = obj_true_name($row, ['show_in_menu', 'is_in_menu']);
                return !$row->$p;

            }

            case 'show_in_menu_del': {
                $p = obj_true_name($row, ['show_in_menu', 'is_in_menu']);
                return $row->$p;
            }


        }

        return false;
    }

    public function action($row, $action, $note = '')
    {
        // Lay thong tin
        if (is_numeric($row)) {
            $row = $this->_model()->get_info($row);
        }

        // Xu ly action voi tran
        if ($this->can_do($row, $action)) {

            switch ($action) {
                case 'on': {
                    $this->_model()->update_field($row->id, 'status', 1);
                    break;
                }
                case 'off': {
                    $this->_model()->update_field($row->id, 'status', 0);
                    break;
                }
                case 'show': {
                    $p = obj_true_name($row, ["show", 'is_show']);
                    $this->_model()->update_field($row->id, $p, 1);
                    break;
                }
                case 'show_del': {
                    $p = obj_true_name($row, ["show", 'is_show']);
                    $this->_model()->update_field($row->id, $p, 0);
                    break;
                }
                case 'feature': {
                    $p = obj_true_name($row, ["feature", 'is_feature']);
                    $this->_model()->update_field($row->id, $p, 1);//now()
                    break;
                }
                case 'feature_del': {
                    $p = obj_true_name($row, ["feature", 'is_feature']);
                    $this->_model()->update_field($row->id, $p, 0);
                    break;
                }
                case 'slide': {
                    $p = obj_true_name($row, ['slide', 'is_slide']);
                    $this->_model()->update_field($row->id, $p, 1);//now()
                    break;
                }
                case 'slide_del': {
                    $p = obj_true_name($row, ['slide', 'is_slide']);
                    $this->_model()->update_field($row->id, $p, 0);
                    break;
                }
                case 'show_in_menu': {
                    $p = obj_true_name($row, ['show_in_menu', 'is_in_menu']);
                    $this->_model()->update_field($row->id, $p, 1);
                    break;

                }
                case 'show_in_menu_del': {
                    $p = obj_true_name($row, ['show_in_menu', 'is_in_menu']);
                    $this->_model()->update_field($row->id, $p, 0);
                }
            }
            // if($note)    $this->_model()->update_field($row->id, 'note', $note);
            // Luu log
            // $this->log($row, $action);
        }


        // Neu la action del thi chay cuoi cung
        if ($this->can_do($row, $action)) {
            switch ($action) {
                // Xoa don hang
                case 'del': {
                    $this->del($row->id);
                    break;
                }
            }

            // Luu log
            //$this->log($row, $action);
        }
    }

    /**
     * Tao url
     *
     * @param object $row
     * @return object
     */
    public function url($row)
    {
        return $row;
    }

    /**
     * @param $row tao cac url cho tin theo
     * $model : ten model
     * chi dung cho admin
     */
    function url_lang($row, $model)
    {
        static $langs = array();
        $row->_lang_url = array();
        if (isset($row->lang) && $row->lang) {
            $langcur = $this->uri->langcur;
            foreach (explode(',', $row->lang) as $l) {
                // lay lang
                foreach (lang_get_list() as $lang_list) {
                    if ($lang_list->directory != $l)
                        continue;

                    $this->uri->langcur = $lang_list->directory;
                    // lay thong tin o tieng $lang_list->directory
                    $info = model($model)->get_info($row->{model($model)->key}, '', $lang_list->id);
                    // tao url theo tieng nay
                    $row->_lang_url[$lang_list->directory] = $this->url($info)->_url_view;
                }
            }
            $this->uri->langcur = $langcur;
        }
        return $row;
    }

    /**
     * -------------------
     * Tags
     * -------------------
     *
     */
    public function tags_get($row)
    {
        $row->tags = model('tag')->getTag($row->id, $this->_get_mod());
        return $row;
    }

    public function tags_set($id, $input, $options = array())
    {
        if (!$input) return;
        $input = escape($input);
        if (strpos($input, ',') !== false) {
            $input = explode(',', $input);
        } else {
            $input = array($input);
        }

        $tags = array();
        foreach ($input as $tag) {
            if (trim($tag))
                $tags[] = trim($tag);
        }
        $info = $this->_model()->get_info($id);

        if (isset($options["table_cat"]) && isset($info->cat_id) && $info->cat_id)
            $options["table_cat"] = $info->cat_id;

        model('tag')->updateTag($tags, $this->_get_mod(), $id, 'tags', $options);
    }

    /**
     * Lay list all
     *
     * @return array
     */
    public function all()
    {
        static $list;

        if (is_null($list)) {
            $list = $this->_model()->get_list(array(
                'select' => $this->_get_mod() . '.*',
            ));
        }

        return $list;
    }

    /**
     * Lay thong tin
     *
     * @param int|array $where
     * @return false|object
     */
    public function find($where)
    {
        $row = array_filter($this->all(), function ($row) use ($where) {
            if (!is_array($where)) {
                $key = $this->_model()->key;

                $where = array($key => $where);
            }

            foreach ($where as $key => $val) {
                if ($row->$key != $val) return false;
            }

            return true;
        });

        $row = head($row);

        if ($row) {
            $row = $this->add_info($row);
            $row = $this->url($row);
        }

        return $row;
    }

    /*
     * ------------------------------------------------------
     * ------------------------------------------------------
     *  Invoice  handle
     * ------------------------------------------------------
     * ------------------------------------------------------
     */
    public function invoice_create($input)
    {
        $key = array_get($input, 'service_key');
        $amount = array_get($input, 'amount');
        $user_id = array_get($input, 'user_id', 0);
        $info_contact = array_get($input, 'info_contact', []);
        $info_shipping = array_get($input, 'info_shipping', []);
       // $info_payment = array_get($input, 'info_payment', []);
        $info_note = array_get($input, 'info_note', '');
        $fee_tax = array_get($input, 'fee_tax', 0);
        $fee_shipping = array_get($input, 'fee_shipping', 0);

        $invoice_status = array_get($input, 'invoice_status', App\Invoice\Library\InvoiceStatus::UNPAID);
        $invoice_options = array_get($input, 'invoice_options', array(
            'pre_key' => setting_get('config-invoice_pre_key'),
            'pre_number' => setting_get('config-invoice_pre_number'),
            'shipping_method' => 0,
            'payment_method' => 0
        ));
        $products = array_get($input, 'products', []);

        //=======================
        $options1 = new App\Invoice\Library\CreateInvoiceOptions([
            'amount' => $amount,
            'user_id' => $user_id,
            'status' => $invoice_status, // unpaid or paid
            'info_contact' => $info_contact,
            'info_shipping' => $info_shipping,
           // 'info_payment' => $info_payment,
            'note' => $info_note,
            'fee_shipping' => $fee_shipping,
            'fee_tax' => $fee_tax,
            'params' => $invoice_options,

        ]);
        //== Tao invoice order
        $invoice = App\Invoice\InvoiceFactory::invoice()->create($options1);

        //== Tao invoice order
        $invoice_order = null;
        if ($products) {
            $invoice_order = [];
            $input2['service_key'] = $key;
            $input2['user_id'] = $user_id;
            foreach ($products as $product) {

                if (is_object($product))
                    $product = (array)$product;

                $input2['amount'] = array_get($product, 'amount', 0);
                $input2['profit'] = array_get($product, 'profit', 0);
                $input2['fee_tax'] = array_get($product, 'fee_tax', 0);
                $input2['product_id'] = array_get($product, 'product_id', 0);
                $input2['product_title'] = array_get($product, 'product_title', '');
                $input2['product_quatity'] = array_get($product, 'product_quatity', 0);
                $input2['product_desc'] = array_get($product, 'product_desc', '');

                $invoice_order[] = $this->invoice_order_create($invoice, $input2);

            }
        } else {
            $invoice_order = $this->invoice_order_create($invoice, $input);

        }
        //pr($invoice_order);
        return compact('invoice', 'invoice_order');


    }

    public function invoice_order_create($invoice, $input)
    {
        $key = array_get($input, 'service_key');
        $user_id = array_get($input, 'user_id', 0);
        $amount = array_get($input, 'amount');
        $profit = array_get($input, 'profit', 0);
        $fee_tax = array_get($input, 'fee_tax', 0);

        //- info order
        $invoice_order_status = array_get($input, 'invoice_order_status', App\Invoice\Library\OrderStatus::PENDING);
        $invoice_order_options = array_get($input, 'invoice_order_options', []);


        $product_id = array_get($input, 'product_id', 0);
        $product_title = array_get($input, 'product_title', '');
        $product_quatity = array_get($input, 'product_quatity', 0);
        $product_desc = array_get($input, 'product_desc', '');


        $options2 = new App\Invoice\Library\CreateInvoiceOrderOptions([
            'invoice' => $invoice,
            'service_key' => $key,
            'amount' => $amount,
            'profit' => $profit,
            'fee_tax' => $fee_tax,

            'user_id' => $user_id,
            'order_status' => $invoice_order_status,
            'order_options' => $invoice_order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
            'title' => $product_title,
            'desc' => $product_desc,
            'product_id' => $product_id,
            'qty' => $product_quatity,

        ]);
        $invoice_order = App\Invoice\InvoiceFactory::invoiceOrder()->create($options2);
        return $invoice_order;
    }

    public function invoice_update($invoice, $input)
    {
        if (!is_object($invoice)) {
            $invoice = model('invoice')->get_info($invoice);
            if (!$invoice || $invoice->status != 'unpaid')
                return false;

            $invoice = mod('invoice')->add_info($invoice);
        }

        $data = $data_order = [];
        if (isset($input['amount'])) {
            $data['amount'] = $data_order['amount'] = $input['amount'];
        }
        if (isset($input['invoice_status'])) {
            $data['status'] = $input['invoice_status'];
            $data_order['invoice_status'] = $input['invoice_status'];
        }
        if (isset($input['invoice_order_status'])) {
            $data_order['order_status'] = $input['invoice_order_status'];
        }
        if (isset($input['invoice_order_options'])) {
            $data_order['order_options'] = $input['invoice_order_options'];
        }
        if ($data) {
            model("invoice")->update($invoice->id, $data);
        }
        // update order con
        if ($data_order) {
            if ($invoice->_orders) {
                foreach ($invoice->_orders as $row) {
                    if (isset($data_order['order_options'])) {
                        if (is_string($row->order_options))
                            $row->order_options = (array)json_decode($row->order_options);
                        if ($row->order_options)
                            $data_order['order_options'] = array_merge($row->order_options, $data_order['order_options']);
                        $data_order['order_options'] = json_encode($data_order['order_options']);
                    }
                    model("invoice_order")->update($row->id, $data_order);
                }
            }
        }

    }

    /*
    * ------------------------------------------------------
    * ------------------------------------------------------
    *  Transaction  handle
    * ------------------------------------------------------
    * ------------------------------------------------------
    */
    function transaction_create($invoice, $payment_id)
    {
        $user = App\User\UserFactory::auth()->user();
        //tao tran
        $options = new App\Transaction\Library\CreateTranOptions([
            'invoice' => $invoice,
        ]);
        $tran = App\Transaction\TranFactory::tran()->create($options);
        //cập nhật tran và cổng thanh toán
        $payment = App\Payment\Model\PaymentModel::find($payment_id);
        $handler = new App\Payment\Job\PaymentPay($tran, $payment, $user);
        $output  = $handler->handle();

        $redirect = $output->get('redirect');
        //$location = $invoice['invoice_order']->url('view');
        if($redirect)
        {
            $location = $redirect;
        }else{
            $location = $invoice->url('payment').'&payment_id='.$payment_id;
        }
        return $location;
    }
    /*
     * ------------------------------------------------------
     *  Session Action  handle
     * ------------------------------------------------------
     */


    /*
     * ------------------------------------------------------
     *  Action Time handle
     * ------------------------------------------------------
     */
    /**
     * Lay time cua action
     * @param string $action Ten action
     * @return    int
     */
    public function sess_action_time_get($action = '')
    {
        return $this->sess_action_get('time', $action);
    }

    /**
     * Luu time cua action
     * @param string $action Ten action
     * @param int $time Timestamp
     * @return    null
     */
    public function sess_action_time_set($action, $time = '')
    {
        $time = (int)$time;
        $time = (!$time) ? now() : $time;

        $this->sess_action_set('time', $action, $time);
    }

    /**
     * Kiem tra time cua action
     * @param string $action Ten action
     * @param int $need Time can phai doi
     * @param int $wait Time da doi
     * @return    bool
     */
    public function sess_action_time_check($action, $need, &$wait = 0)
    {
        $time = $this->sess_action_time_get($action);
        $wait = now() - $time;

        return ($wait >= $need) ? TRUE : FALSE;
    }


    /*
     * ------------------------------------------------------
     *  Action Count handle
     * ------------------------------------------------------
     */
    /**
     * Lay count cua action
     * @param string $action Ten action
     * @return    int
     */
    public function sess_action_count_get($action = '')
    {
        return $this->sess_action_get('count', $action);
    }

    /**
     * Luu count cua action
     * @param string $action Ten action
     * @param int $count So count
     * @return    null
     */
    public function sess_action_count_set($action, $count)
    {
        $count = (!is_numeric($count)) ? 0 : $count;

        $this->sess_action_set('count', $action, $count);
    }

    /**
     * Thay doi count cua action
     *
     * @param string $action
     * @param int $value
     * @return int
     */
    public function sess_action_count_change($action, $value)
    {
        $count = $this->sess_action_count_get($action) + 1;

        $this->sess_action_count_set($action, $count);

        return $count;
    }

    /**
     * Lay value cua action theo type
     * @param string $type Ten type
     * @param string $action Ten action (Neu khong khai bao action thi se lay toan bo value)
     * @return mixed
     */
    function sess_action_get($type, $action = '')
    {
        $info = $this->sess_get();
        if (!isset($info['action_' . $type]))
            return;

        $action_type = $info['action_' . $type];
        $action_type = (!is_array($action_type)) ? array() : $action_type;

        if ($action) {
            return (isset($action_type[$action])) ? $action_type[$action] : FALSE;
        }
        return $action_type;
    }

    /**
     * Luu value cua action theo type
     * @param string $type Ten type
     * @param string $action Ten action
     * @param mixed $value Gia tri
     * @return null
     */
    function sess_action_set($type, $action, $value)
    {
        $action_type = $this->sess_action_get($type);
        $action_type[$action] = $value;
        $data = array();
        $data['action_' . $type] = $action_type;
        $this->sess_set($data);
    }

    /*
         * ------------------------------------------------------
         *   Session Data handle
         * ------------------------------------------------------
         */

    /**
     * Lay data theo name
     * @param string $action Ten action
     * @return    int
     */
    public function sess_data_get($name = '')
    {
        $info = $this->sess_get();
        if (!isset($info['data']))
            return;

        $info = (!is_array($info['data'])) ? array() : $info['data'];

        if ($name) {
            return (isset($info[$name])) ? $info[$name] : FALSE;
        }
        return $info;
    }

    /**
     * Luu data theo name
     * @param string $action Ten action
     * @param int $count So count
     * @return    null
     */
    public function sess_data_set($name, $value)
    {
        $info = $this->sess_data_get();
        $info[$name] = $value;
        $data = array();
        $data['data'] = $info;
        $this->sess_set($data);
    }

    /**
     * Luu data theo name
     * @param string $action Ten action
     * @param int $count So count
     * @return    null
     */
    public function sess_data_set_array($list)
    {
        $info = $this->sess_data_get();
        if (is_array($list) && $list) {
            foreach ($list as $k => $v) {
                $info[$k] = $v;
            }
        }

        //pr($info);
        $data = array();
        $data['data'] = $info;
        $this->sess_set($data);
    }

    public function sess_data_del($name = '')
    {
        $info = $this->sess_get();
        if ($name)
            $info['data'][$name] = '';
        else
            $info['data'] = '';
        $this->sess_set($info);
    }

    /**=========================================================
     * Session handle
     * ==========================================================*/
    function sess_set($data, $name = '')
    {
        return t('session')->set_userdata($this->sess_name($name), $data);
    }

    function sess_get($name = '')
    {
        return t('session')->userdata($this->sess_name($name));
    }

    function sess_del($name = '')
    {
        return t('session')->unset_userdata($this->sess_name($name));
    }

    function sess_name($name = '')
    {
        if (!$name)
            $name = $this->_get_mod();
        return $name;
    }



}
