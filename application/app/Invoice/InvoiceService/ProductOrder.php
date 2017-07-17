<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;

// use App\PlanOrder\Model\MovieOrderModel;

class ProductOrder extends InvoiceService
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
        return ServiceType::PRODUCTORDER;
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
            'name' => 'Mua tin bài',
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
            //pr_db($invoice);


            if (!$invoice) {
                set_message(lang('notice_can_not_do'));

                $this->_redirect();
            }
            $invoice->invoice_order = $invoice_order;
            $data['invoice'] = $invoice;
            $product_orders = objectExtract(['service_key' => 'ProductOrder'], $invoice->_orders);
            $product_ids = array_gets($product_orders, 'product_id');
            $data['products'] = null;
            if ($product_ids)
                $data['products'] = model('product')->filter_get_list(['id' => $product_ids]);

            return view('tpl::product_order/view', $data, true);

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
        // mod('product_owner')->add( $invoice_order->product_id, $invoice_order->user_id);

        //= tin hoa hong khoa hoc cho
        //mod("product")->bonus($invoice_order,$product);

    }


}