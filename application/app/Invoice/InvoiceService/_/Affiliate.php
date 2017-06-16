<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\User\Model\UserModel;
use App\Purse\Job\ChangePurseBalance;
use App\Purse\PurseFactory;

class Affiliate extends InvoiceService
{
    /**
     * Khoi tao doi tuong
     */
    public function __construct()
    {
        t('lang')->load('site/affiliate');
    }

    /**
     * Lay loai dich vu (lay theo ServiceType::***)
     *
     * @return string
     */
    public function type()
    {
        return ServiceType::AFFILIATE;
    }

    /**
     * Lay thong tin
     *
     * @return array
     */
    public function info()
    {
        return [
            'name' => 'Referral commissions'
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
        $options = $invoice_order->order_options;
        return lang('received_from_account', [
            'user_aff' => array_get($options, 'user_aff'),
        ]);
    }

    /*
     * Tạo đơn hàng và cộng % hoa hồng affiliate cho thành viên theo % đơn hàng
     */
    function active($invoice_order)
    {        //pr($invoice_order);

        if (!in_array($invoice_order->service_key, array('productOrder', 'LessonOrder', 'ComboOrder'))) {
            return;
        }

        if (!mod("product")->setting('affiliate_turn_on') ||
            (!mod("product")->setting('affiliate_commission_constant') && !mod("product")->setting('affiliate_commission_percent')) ) {
            return;
        }

        // lay thong tin nguoi mua hang
        $user_aff = model('user')->get_info($invoice_order->user_id);

        if (!$user_aff)
            return;
       // echo '<br>user_affiliate_number:'.$user_aff->user_affiliate_number;
        //echo '<br>user_affiliate_number config:'. mod("product")->setting('affiliate_commission_number');
        if (mod("product")->setting('affiliate_commission_number'))
        {
            //pr(9);
            if($user_aff->user_affiliate_number >= mod("product")->setting('affiliate_commission_number')){
               // pr("qua so lan");
                return;

            }
        }

        // lay thong tin nguoi dc huong hoa hong
        $user = model('user')->get_info($user_aff->user_affiliate_id);//thong tin nguoi gioi thieu neu co
        if (!$user || $user->blocked == config('verify_yes', 'main'))
            return;

        // kiem tra so lan da cong hoa hong cho nguoi gioi thieu


        //=================================
        $amount = $invoice_order->amount;
        //echo '<br>amount:'.$amount;
        if (!$amount)
            return;

        $commission_constant =mod("product")->setting('affiliate_commission_constant');
        $commission_percent =mod("product")->setting('affiliate_commission_percent') * 0.01 *$amount;
        $amount = currency_handle_input($commission_constant) + $commission_percent;

       // echo '<br>amount:'; pr($amount);
        if (!$amount)
            return;

        $this->commission($user, $user_aff, $amount);

       // pr("stop");

    }

    /**
     * Tao invoice tính hoa hồng truc tiep
     */

    function commission($user, $user_aff, $amount)
    {
        if ($amount <= 0) return;
        //lấy tiền tệ mặc định
        $currency = currency_get_default();
        $currency_id = $currency->id;

        //nếu chưa được nhận hoa hồng nào từ thành viên này
        $order_options = array(
            'user_aff' => $user_aff->email,
            'amount' => $amount,
           // 'currency_id' => $currency_id,
        );
        //tang so lan cong hoa hong
        model("user")->update_stats($user_aff->id,['user_affiliate_number'=>1]);

        //tao Invoice
        $invoice = $this->createInvoice($amount, $user);

        //tao Invoice_order
        $invoice_order = $this->createInvoiceOrder($invoice, $currency_id, $order_options);

        //cộng tiền vào ví btc cho thành viên
        $this->deposit($user->id, $user_aff, $amount, $currency_id, $invoice_order);
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
    protected function createInvoiceOrder($invoice, $currency_id, $order_options)
    {
        $options = new \App\Invoice\Library\CreateInvoiceOrderOptions([
            'invoice' => $invoice,
            'service_key' => 'Affiliate',
            'amount' => $invoice->amount,
            //'amount_currency' => $invoice->amount,
            'order_status' => 'completed',
            //'currency_id' => $currency_id,
            'order_options' => $order_options, // Khai bao thong tin order (dung de tao mo ta va tim kiem)
        ]);

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
    public function deposit($user_id, $user_aff, $amount, $currency_id, $invoice_order)
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
            'reason_key' => 'Affiliate',
            'purse_balance' => $purse->balance + $amount,
            'reason_options' => $reason_options,
            'amount' => $amount,
            'balance' => 0,//Tổng số dư của các ví sau thay đổi (tính theo tiền tệ mặc định)
            'desc' => $status . currency_format_amount($amount, $currency_id) . ' from ' . $user_aff->email,
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

