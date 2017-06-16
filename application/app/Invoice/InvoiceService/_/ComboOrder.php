<?php namespace App\Invoice\InvoiceService;

use App\Invoice\Library\InvoiceService;
use App\Invoice\Library\ServiceType;
use App\Invoice\Model\InvoiceOrderModel as InvoiceOrderModel;
use App\Payment\Model\PaymentModel;
use App\Payment\PaymentFactory as PaymentFactory;

// use App\PlanOrder\Model\MovieOrderModel;

class ComboOrder extends InvoiceService
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
        return ServiceType::COMBOORDER;
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
            'name' => 'Mua Combo',
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

        //= hoan thanh don hang
        $invoice_order->update(['order_status' => "completed"]);

        $options = $invoice_order->order_options;
        $services = json_decode(array_get($options, 'combo_services'));
        //pr($services);

        //== su ly add khoa hoc
        if (isset($services->products) && $services->products) {
            $product_watch_expired = null;
            $lesson_owner_options =[];
            if (isset($services->product_watch_expired_type) && $services->product_watch_expired_type > 0) {
                // neu la cong gop
                if ($services->product_watch_expired_type == 1) {
                    foreach ($services->products as $id) {
                        $product = mod('product')->get_info($id);
                        if ($product) {
                            if ($product->watch_config)// neu co thiet lap rieng
                            {
                                $product_watch_expired += (int)$product->watch_expired * 24 * 60 * 60;
                            } else {
                                $product_watch_expired += (int)mod("product")->setting('premium_product_exprie_time') * 24 * 60 * 60;

                            }
                        }
                    }
                }
                elseif($services->product_watch_expired_type == 2){
                    $product_watch_expired = (int)$services->product_watch_expired_value * 24 * 60 * 60;
                }
            }
            if($product_watch_expired){
                $lesson_owner_options["watch_expired"] = $product_watch_expired;
            }
            //pr($lesson_owner_options);
            foreach ($services->products as $id) {
                $product = mod('product')->get_info($id);
                if ($product) {
                  //  mod('lesson_owner')->add("product", $id, $invoice_order->user_id,$lesson_owner_options);
                    //= tin hoa hong
                  //  mod("product")->bonus($invoice_order, $product);

                }
            }
        }

        //== su ly add bai hoc
        if (isset($services->lessons) && $services->lessons) {
            $lesson_watch_expired = null;
            $lesson_owner_options =[];
            if (isset($services->lesson_watch_expired_type) && $services->lesson_watch_expired_type > 0) {
                // neu la cong gop
                if ($services->lesson_watch_expired_type == 1) {
                    foreach ($services->lessons as $id) {
                        $lesson = mod("product")->get_info($id);
                        if ($lesson) {
                            if ($lesson->watch_config)// neu co thiet lap rieng
                            {
                                $lesson_watch_expired += (int)$lesson->watch_expired * 24 * 60 * 60;
                            } else {
                                $lesson_watch_expired += (int)mod("product")->setting('premium_lesson_exprie_time') * 24 * 60 * 60;

                            }
                        }
                    }
                }
                elseif($services->lesson_watch_expired_type == 2){
                    $lesson_watch_expired = (int)$services->lesson_watch_expired_value * 24 * 60 * 60;

                }
            }
            if($lesson_watch_expired){
                $lesson_owner_options["watch_expired"] = $lesson_watch_expired;
            }
            foreach ($services->lessons as $id) {
                $lesson = mod("product")->get_info($id);
                if ($lesson) {
                    mod('lesson_owner')->add("lesson", $id, $invoice_order->user_id,$lesson_owner_options);
                    //= tin hoa hong
                    mod("product")->bonus($invoice_order, $lesson);

                }
            }
        }
    }


}