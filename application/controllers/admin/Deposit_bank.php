<?php

use App\Invoice\Model\InvoiceOrderModel;
use App\Currency\Model\CurrencyModel;
use App\Purse\PurseFactory;
use App\User\UserFactory;
use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Deposit as DepositReason;


class Deposit_bank extends MY_Controller
{
    /**
     * Khoi tao doi tuong
     */
    public function __construct()
    {
        parent::__construct();

        t('lang')->load('site/deposit_bank');
    }

    /**
     * List
     */
    public function index_()
    {
        $this->data['statuss'] = $this->_mod()->statuss();
        $this->data['renewtv_times'] = config('renewtv_times', 'main');
        $this->data['renewtv_types'] = config('renewtv_types', 'main');

        $list = array();
        $list['filter'] = true;
        $list['filter_fields'] = array('id', 'type', 'code', 'time', 'status', 'user_id', 'created', 'created_to');
        $list['input'] = ['relation' => 'user'];
        $list['actions'] = array('view', 'active');
        $this->_list($list);
    }

    /**
     * Hoan thanh
     */
    public function complete()
    {
        $id = $this->uri->rsegment(3);
        $info = $this->_model()->get_info($id);
        if (!$info) return;
        $user = model('user')->get_info($info->user_id);


        $data = array();
        $data['status'] = 'completed';
        $this->_model()->update($info->id, $data);

        //update order
        $data = array();
        $data['status'] = 'paid';
        model('invoice')->update($info->invoice_id, $data);

        $data = array();
        $data['invoice_status'] = 'paid';
        $data['order_status'] = 'completed';
        model('invoice_order')->update($info->invoice_order_id, $data);


        //== Su ly cong tien
        // lay vi VND
        $currency = CurrencyModel::findWhere(['code' => 'VND']);
        if (!$currency) {
            throw new Exception('Currency VND not found');
            return;
        }

        $user = $user ? (array)$user : [];
        if (!$user) {
            throw new Exception('User not found');
            return;
        }
        $user = UserFactory::auth()->user($user);
        $purse = PurseFactory::purse()->get($user, $currency);
        if (!$purse) {
            throw new Exception("Can't get purse of user");
            return;
        }

        // tim invoice_order

        $invoice_order =  InvoiceOrderModel::find($info->invoice_order_id);
        $reason = DepositReason::make($invoice_order);
        AccountantFactory::balance()->add($purse, $info->amount, $reason);


        set_message(lang('notice_update_success'));


        //==gui email
        /*mod('email')->send('deposit_bank_active', $user['email'], array(
            'order_id' => $info->id,
            'type' => $info->type,
            'bank' => $info->bank,
            'acc_name' => $info->acc_name,
            'acc_id' => $info->acc_id,
            'amount' => currency_format_amount($info->amount),
            'date' => $info->date,
        ));*/

    }

    /**
     * Huy bo
     */
    public function cancel()
    {
        $id = $this->uri->rsegment(3);
        $info = $this->_model()->get_info($id);
        if (!$info) return;

        $data = array();
        $data['status'] = 'canceled';
        $this->_model()->update($info->id, $data);

        //update Invoice
        $data = array();
        $data['order_status'] = 'canceled';
        model('invoice_order')->update($info->invoice_order_id, $data);


        set_message(lang('notice_update_success'));
    }


}