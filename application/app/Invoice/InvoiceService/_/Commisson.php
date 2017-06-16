<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\User\Model\UserModel;
use App\Purse\Job\ChangePurseBalance;
use App\Purse\PurseFactory;

class Commisson extends InvoiceService
{
    /**
     * Khoi tao doi tuong
     */
    public function __construct()
    {
        //t('lang')->load('site/Commisson');
    }

    /**
     * Lay loai dich vu (lay theo ServiceType::***)
     *
     * @return string
     */
    public function type()
    {
        return ServiceType::COMMISSON;
    }

    /**
     * Lay thong tin
     *
     * @return array
     */
    public function info()
    {
        return [
            'name' => 'Hoa hồng'
        ];
    }


    /**
     * Lay mo ta cua order
     *
     * @param InvoiceOrderModel $invoice_order
     * @return null|string|array
     */
    public function getOrderDesc(InvoiceOrderModel $invoice_order)
    {
        return '';
        $options = $invoice_order->order_options;
        return lang('received_from_account', [
            'user_aff' => array_get($options, 'user_aff'),
        ]);
    }

    /*
     * Tạo đơn hàng và cộng % hoa hồng cho thành viên
     */
    function active($invoice_order)
    {

        /*if(!in_array($invoice_order->service_key, array('UserUpgrade')))
        {
            return;
        }*/

       // pr($invoice_order);
        $user_options = array_get($invoice_order, 'user_options');
        if (!$user_options)
            return;


        if (!$user_options->status || !$user_options->user_id || $user_options->amount <= 0) return;
        $user = model('user')->get_info($user_options->user_id);
        if (!$user) return;


        $amount_invoice = $invoice_order->amount;
        //echo '<br>$amount_invoice:' . $amount_invoice;
        $opt_amount = $user_options->amount;
        $opt_amount_type = $user_options->amount_type;
        $amount = 0;
        if ($opt_amount_type == 1) {
            // neu tien hoa hong lon hon tien ban hang thi huy
           // if ($opt_amount > $amount_invoice)         return;
            $amount = $opt_amount;

        } else {
            //pr($amount_invoice * $opt_amount / 100);
            $amount =  $amount_invoice * $opt_amount / 100;
        }
        //echo '<br>';        pr($amount);

        if ($amount < 0) return;

        //lấy tiền tệ mặc định
        $currency = currency_get_default();
        $currency_id = $currency->id;



        //tao Invoice
        $invoice = $this->createInvoice($amount, $user);

        //tao Invoice_order
        $invoice_order_new = $this->createInvoiceOrder($invoice,$invoice_order, $user);

        //cộng tiền vào ví btc cho thành viên
        $this->deposit($user->id, $amount, $currency_id, $invoice_order_new);
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
    protected function createInvoiceOrder($invoice, $invoice_order_old, $user)
    {
        //nếu chưa được nhận hoa hồng nào từ thành viên này
        $order_options = array(
            'amount' => $invoice->amount,
        );
        //- info order
        $options = new \App\Invoice\Library\CreateInvoiceOrderOptions([
            'invoice' => $invoice,
            'service_key' => 'Commisson',
            'amount' => $invoice->amount,
            'user_id' => $user->id,
            'order_status' => 'completed',
            'order_options' => $order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
            'title' =>$invoice_order_old->title,
            'desc' => "Hoa hồng từ:".$invoice_order_old->title,
            //'desc' => "Hoa hồng từ:"$invoice_order_old->desc,
            //'profit' => $product_profit,
           // 'fee_tax' => $product_fee_tax,
            'product_id' => $invoice_order_old->product_id,
            'qty' =>$invoice_order_old->qty,

        ]);


        /*$options = new \App\Invoice\Library\CreateInvoiceOrderOptions([
            'invoice' => $invoice,
            'service_key' => 'Commisson',
            'amount' => $invoice->amount,
            //'amount_currency' => $invoice->amount,
            'order_status' => 'completed',
           // 'currency_id' => $currency_id,
            'order_options' => $order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
        ]);*/

        $invoice_order = \App\Invoice\InvoiceFactory::invoiceOrder()->create($options);
        return $invoice_order;
    }

    /**
     * Thuc hien nap tien cho user
     *
     * @param int $user_id
     * @param float $amount
     * @param array $sms
     * @return int
     */
    public function deposit($user_id, $amount, $currency_id, $invoice_order)
    {

        //lấy thông tin thành viên
        $user = UserModel::find($user_id);
        $purses = PurseFactory::purse()->userPurses($user);
        $purse = '';
        foreach ($purses as $row) {
            if ($row->currency_id == $currency_id) {
                $purse = $row;
            }
        }
        if (!$purse) return false;


        //nạp tiền cho thành viên
        //lưu log trừ tiền log_balance
        $reason_options = @json_encode(array('invoice_order' => $invoice_order));

        $status = '+';
        $data = array(
            'status' => $status,
            'purse_id' => $purse->id,
            'purse_amount' => $amount,
            'reason_key' => 'Commisson',
            'purse_balance' => $purse->balance + $amount,
            'reason_options' => $reason_options,
            'amount' => $amount,
            'balance' => 0,//Tổng số dư của các ví sau thay đổi (tính theo tiền tệ mặc định)
            'desc' => $status . currency_format_amount($amount, $currency_id),
            'user_id' => $user_id,
            'currency_id' => $currency_id,
            'created' => now(),
            'ip' => t('input')->ip_address(),
            'user_agent' => t('input')->user_agent(),
            'referer       ' => site_url(),
        );

        model('log_balance')->create($data);

        //cộng tiền vào ví cho thành viên
        return (new ChangePurseBalance(
            $purse, $amount, $status
        ))->handle();

        return $invoice_order;
    }
}

