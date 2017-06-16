<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service_order_mod extends MY_Mod
{
    /**
     * Lay status id
     *
     * @param string $name
     * @return int|false
     */
    public function status($name)
    {
        return config("service_status_{$name}", 'main');
    }

    /**
     * Lay status name
     *
     * @param int $id
     * @return string
     */
    public function status_name($id)
    {
        return array_get($this->statuss(), $id);
    }

    /**
     * Lay statuss
     *
     * @return array
     */
    public function statuss()
    {
        return config('service_statuss', 'main');
    }

    /**
     * Lay setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function setting($key = null, $default = null)
    {
        $setting = module_get_setting($this->_get_mod());

        $setting['types'] = $this->make_setting_types($setting['types']);

        return array_get($setting, $key, $default);
    }

    /**
     * Tao setting types
     *
     * @param unknown $val
     * @return multitype:
     */
    protected function make_setting_types($val)
    {
        $val = explode("\n", $val);
        $val = array_map('trim', $val);
        $val = array_filter($val);

        return $val;
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
            case 'view':
            case 'edit':
            case 'del':
            {
                return true;
            }

            case 'upgrade_config':
            case 'upgrade_service': {
                return true;
                if (isset($row->status)) {
                    return (in_array($row->status, array('active'))) ? true : false;
                }
            }
            case 'renew': {
                if (isset($row->status)) {
                    return (in_array($row->status, array('active', 'expired', 'suspended'))) ? true : false;
                }
            }
            case 'suspend': {
                if (isset($row->status)) {
                    return (!in_array($row->status, array('suspend'))) ? true : false;
                }
            }

        }

        return false;
    }

    /**
     * Them cac thong tin phu
     *
     * @param object $row
     * @return object
     */
    public function add_info($row)
    {
        $row = parent::add_info($row);

        if (isset($row->status)) {
            $row->_status = $this->status_name($row->status);
        }

        if (isset($row->amount)) {
            $row->amount = (float)$row->amount;
            $row->_amount = number_format($row->amount);
        }


        return $row;
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
        // Lay config
        $statuss = $this->statuss();

        // Lay gia tri cua filter dau vao
        $input = array();
        foreach ($fields as $f) {
            $v = $this->input->get($f);
            $v = security_handle_input($v, in_array($f, array()));

            if (
            ($f == 'status' && !in_array($v, $statuss))
            ) {
                $v = '';
            }

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
            switch ($f) {
                case 'status': {
                    $v = $this->status($v);
                    break;
                }
                case 'created': {
                    $created_to = $input['created_to'];
                    $v = (strlen($created_to)) ? array($v, $created_to) : $v;
                    $v = get_time_between($v);
                    $v = (!$v) ? NULL : $v;
                    break;
                }
            }

            if (is_null($v)) continue;

            $filter[$f] = $v;
        }

        return $filter;
    }


    /**
     * Tao order
     *
     * @param array $input
     *    array        'cart'            Thong tin cart (la ket qua tra ve cua fun mod('topup_offline')->get_cart())
     *    string        'tran_status'    = 'pending'
     *    string        'tran_payment'    = ''
     *    string        'order_status'    = 'pending'
     *    int            'user_id'        = 0
     *    int            'user_balance'    = 0
     *    string        'user_ip'        = ''
     *    array        'contact'        = array()
     * @param array $output
     * @return int
     */
    public function create(array $input, &$output = array())
    {
        // Xu ly input
        $user = array_get($input, 'user');
        $title = array_get($input, 'title', '');
        $day = array_get($input, 'day', 0);
        $amount = array_get($input, 'amount', 0);
        $order_options = array_get($input, 'order_options', array());
        //tao Invoice
        $invoice = $this->createInvoice($amount, $user);
        //tao Invoice_order
        $invoice_order = $this->createInvoiceOrder($invoice, $order_options);


        $data = array();
        //$data['type']             = 'new'; //loai mua moi
        if($title)
            $data['title'] = $title;
        $data['amount'] = $amount;
        $data['user_id'] = $user->id;
        $data['invoice_id'] = $invoice->id;
        $data['invoice_order_id'] = $invoice_order->id;
        $data['status'] = 'active';

        // kiem tra xem user nay da tao dich vu gia han truoc chua, neu
        $service_order_old = $this->_model()->get_info_rule(array('user_id' => $user->id));
        //pr($service_order_old);
        $days_time = convert_day($day);
        if ($service_order_old) {
            // neu da co thi cap nhap lai thong tin service
            // Tinh ngay het han cho user
            $expire = max($service_order_old->expire_to, now());
            $expire = add_time($expire, $days_time);
            $data['expire_from']  = now();
            $data['expire_to'] = $expire;
            $data['last_update_status'] = now();
            $service_order = $this->_model()->update($service_order_old->id, $data);

        } else {
            $data['created'] = now();
            $data['expire_from'] = $data['created'];
            $data['expire_to'] = add_time(now(), $days_time);
            $service_order = $this->_model()->create($data);
        }
        $output = compact('invoice', 'invoice_order', 'service_order');
    }

    /**
     * Tao invoice
     *
     * @return InvoiceModel
     */
    protected function createInvoice($amount, $user)
    {
        $options = new \App\Invoice\Library\CreateInvoiceOptions([
            'amount' => $amount, // tinh theo tien te mac dinh
            'user_id' => $user->id, // Ma thanh vien
            'status' => 'paid', // unpaid or paid
        ]);
        $invoice = \App\Invoice\InvoiceFactory::invoice()->create($options);
        return $invoice;
    }

    /**
     * Tao invoice order
     *
     * @param InvoiceModel $invoice
     * @return InvoiceOrderModel
     */
    protected function createInvoiceOrder($invoice, $order_options, $service_order = '')
    {
        $options = new \App\Invoice\Library\CreateInvoiceOrderOptions([
            'invoice' => $invoice,
            'service_key' => 'ServiceOrder',
            'amount' => $invoice->amount,
            'order_status' => 'completed',
            'order_options' => $order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
        ]);

        $invoice_order = \App\Invoice\InvoiceFactory::invoiceOrder()->create($options);
        return $invoice_order;
    }
}