<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\Payment\PaymentFactory as PaymentFactory;
use App\User\UserFactory as UserFactory;
use App\Invoice\InvoiceFactory as InvoiceFactory;

class Payment_widget extends MY_Widget
{
    /**
     * Hien thi danh sach cac cong thanh toan chon
     */
    function list_choice($amount=null, $temp = '')
    {
        $user = UserFactory::auth()->user();
        $purses = App\Purse\PurseFactory::purse()->userPurses($user);
        $balance = $purses->sortByDesc('balance')->first()->balance;

        $payments = PaymentFactory::paymentManager()->listActive();
        $list = [];
        foreach ($payments as $payment) {
            //== Check xem duoc phep su dung cong thanh toan
            if ($payment->paygate_key == "Purse") {
                //$purses = App\Purse\PurseFactory::purse()->userPurses($user)->whereLoose('currency_id', $invoice->currency_id);
                if ($balance < $amount) {
                    continue;
                }
            }
            if (   !$payment->can('payment')
                || !PaymentFactory::payment()->canUseByUser($payment, $user)
            )
                continue;

            //== Su ly tien, phi theo cong thanh toan
            $payment_amount = currency_convert_amount($amount, $payment->currency_id);
            // echo $payment;
            $payment_fee = PaymentFactory::payment()->getFee($payment, $payment_amount);
            //pr($fee);
            $payment_amount = $payment_amount +$payment_fee;

            $list[] = [
                'payment' => $payment,
                'amount' => $amount,
                'payment_id' => $payment->id,
                'format_amount' => currency_format_amount($payment_amount, $payment->currency_id),
            ];
        }
        $this->data['payments'] = $list;
        // Hien thi view
        $temp = ($temp == '') ? 'tpl::_widget/payment/list_choice' : $temp;
        $this->load->view($temp, $this->data);
    }

    /**
     * Hien thi danh sach cac cong thanh toan de thanh toan giao dich
     */
    function list_checkout($payments, $temp = '')
    {
        /*
                foreach ($payments as $payment)
                {
                    $payment->redirect 	= ( ! in_array($payment, array('balance', 'banking'))) ? TRUE : FALSE;


        } */
        $this->data['payments'] = $payments;
        // Hien thi view
        $temp = ($temp == '') ? 'tpl::_widget/payment/list_checkout' : $temp;
        $this->load->view($temp, $this->data);
    }

    /**
     * @param $invoice :
     * @param string $temp
     */
    function list_checkout_invoice($invoice, $temp = '')
    {
        if (is_numeric($invoice)) {
            $invoice = $invoice = \App\Invoice\Model\InvoiceModel::find($invoice);
        }
        $payments = PaymentFactory::paymentManager()->listActive();

        $list = [];
        foreach ($payments as $payment) {
            if (!$this->canUsePayment($payment, $invoice)) continue;

            $amount = $this->getPaymentAmount($payment, $invoice);

            $list[] = [
                'payment' => $payment,
                'amount' => $amount,
                'url_pay' => $invoice->url('payment') . '&payment_id=' . $payment->id,
                'format_amount' => currency_format_amount($amount, $payment->currency_id),
            ];
        }
        $this->data['payments'] = $list;
        // Hien thi view
        $temp = ($temp == '') ? 'tpl::_widget/payment/list_checkout' : $temp;
        $this->load->view($temp, $this->data);
    }

    /**
     * Kiem tra co the su dung payment de thanh toan hay khong
     *
     * @param PaymentModel $payment
     * @return bool
     */
    private function canUsePayment($payment, $invoice)
    {
        $user = UserFactory::auth()->user();
        if ($payment->paygate_key == "Purse") {
            //$purses = App\Purse\PurseFactory::purse()->userPurses($user)->whereLoose('currency_id', $invoice->currency_id);
            $purses = App\Purse\PurseFactory::purse()->userPurses($user);
            $balance = $purses->sortByDesc('balance')->first()->balance;
            if ($balance < $invoice->amount) {
                return false;
            }
        }
        return (
            $payment->can('payment')
            && PaymentFactory::payment()->canUseByUser($payment, $user)
            && PaymentFactory::payment()->canUseForInvoice($payment, $invoice)
        );
    }

    /**
     * Lay so tien can thanh toan cua invoice tuong ung voi payment
     *
     * @param PaymentModel $payment
     * @return float
     */
    private function getPaymentAmount($payment, $invoice)
    {
        $amount = InvoiceFactory::invoice()->getAmountCurrency($invoice, $payment->currency_id);

        $fee = PaymentFactory::payment()->getFee($payment, $amount);

        return $amount + $fee;
    }
}