<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;

// use App\PlanOrder\Model\MovieOrderModel;

class LessonOrder extends InvoiceService
{
    /**
     * Khoi tao doi tuong
     */
    public function __construct()
    {
        // t('lang')->load('site/lesson_order');
    }

    /**
     * Lay loai dich vu (lay theo ServiceType::***)
     *
     * @return string
     */
    public function type()
    {
        return ServiceType::LESSONORDER;
    }


    /**
     * Lay mo ta cua order
     *
     * @param InvoiceOrderModel $invoice_order
     * @return null|string|array
     */
    public function getOrderDesc(InvoiceOrderModel $invoice_order)
    {
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
            'name' => 'Mua bài học',
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

        if (get_area() == 'site') {
            $data['invoice_order'] = $invoice_order;
            return view('tpl::lesson/order/view', $data, true);
        }
        return null;

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

        $lesson = mod("product")->get_info($invoice_order->product_id);
        if (!$lesson)
            return;

        /*if (!user_can_do($user, 'plan'))
        {
            return ;
        }*/
        // hoan thanh don hang
        $invoice_order->update(['order_status' => "completed"]);
        // them vao bang owner
        mod('lesson_owner')->add("lesson", $invoice_order->product_id, $invoice_order->user_id);

        //= tin hoa hong
        mod("product")->bonus($invoice_order, $lesson);
    }

}