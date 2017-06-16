<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;

// use App\PlanOrder\Model\MovieOrderModel;

class productOrder extends InvoiceService
{
    /**
     * Khoi tao doi tuong
     */
    public function __construct()
    {
        // t('lang')->load('admin/lesson_order');
    }

    /**
     * Lay loai dich vu (lay theo ServiceType::***)
     *
     * @return string
     */
    public function type()
    {
        return ServiceType::productORDER;
    }


    /**
     * Lay mo ta cua order
     *
     * @param InvoiceOrderModel $invoice_order
     * @return null|string|array
     */
    public function getOrderDesc(InvoiceOrderModel $invoice_order)
    {
       // $povie_order = MovieOrderModel::findByInvoiceOrder($invoice_order->id);
        return $invoice_order->desc;
    }

    /**
     * Lay thong tin
     *
     * @return array
     */
    public function info()
    {
        return [
            'name' => 'Mua khóa học',
        ];
    }

    /**
     * View invoice
     *
     * @param InvoiceOrderModel $invoice_order
     * @return string|null
     */
    public function view(InvoiceOrderModel $invoice_order)
    {
        if (get_area() == 'admin') {

            $invoice = model('invoice')->get_info($invoice_order->invoice_id);
            $invoice = mod('invoice')->add_info($invoice);
            if (!empty($invoice->info_contact)) {
                if ($invoice->info_contact->city)
                    $invoice->info_contact->_city = model('city')->get_info($invoice->info_contact->city);
                if ($invoice->info_contact->country)
                    $invoice->info_contact->_country = model('country')->get_info($invoice->info_contact->country);
            }

            // $invoice->info_shipping = json_decode($invoice->info_shipping);
            if (!empty($invoice->info_shipping)) {
                if ($invoice->info_shipping->city)
                    $invoice->info_shipping->_city = model('city')->get_info($invoice->info_shipping->city);
                if ($invoice->info_shipping->country)
                    $invoice->info_shipping->_country = model('country')->get_info($invoice->info_shipping->country);
            }

            if (!$invoice) {
                set_message(lang('notice_can_not_do'));

                $this->_redirect();
            }
            $invoice->invoice_order = $invoice_order;
            $data['invoice'] = $invoice;

            $product_orders = objectExtract(['service_key' => 'productOrder'], $invoice->_orders);
            $lesson_orders = objectExtract(['service_key' => 'LessonOrder'], $invoice->_orders);
            $product_ids = array_gets($product_orders, 'product_id');
            $lesson_ids = array_gets($lesson_orders, 'product_id');

            $data['products'] = null;
            if ($product_ids)
                $data['products'] = model('product')->filter_get_list(['id' => $product_ids]);
            $data['lessons'] = null;
            if ($lesson_ids)
                $data['lessons'] = model('product')->filter_get_list(['id' => $lesson_ids]);

            return view('tpl::lesson_order/view', $data, true);

        } else {
            $data['invoice_order'] = $invoice_order;
            return view('tpl::product/order/view', $data, true);

        }

    }

    /*
     * Tạo đơn hàng và cộng % hoa hồng cho thành viên
     */
    function active($invoice_order)
    {
        if (!in_array($invoice_order->service_key, array($this->type()))) {
            return;
        }

        $user = model('user')->get_info($invoice_order->user_id);
        if (!$user)
            return;

        $product = mod('product')->get_info($invoice_order->product_id);
        if (!$product)
            return;

        /*if (!user_can_do($user, 'plan'))
        {
            return ;
        }*/
        //= hoan thanh don hang
        $invoice_order->update(['order_status' => "completed"]);


        //= them vao bang owner
        mod('lesson_owner')->add("product", $invoice_order->product_id, $invoice_order->user_id);

        //= tin hoa hong khoa hoc cho
        mod("product")->bonus($invoice_order,$product);

    }


}