<?php namespace App\Payment\PayGate\Paypal;

use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Library\Payment\PaymentResultInputRequest;
use App\Payment\Library\Payment\PaymentResultInputResponse;
use App\Payment\Library\Payment\PaymentResultOutputRequest;
use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;

class Service extends PayGateService
{
    /**
     * Payment co su dung view hien thi rieng luc hien thi cong thanh toan hay khong
     *
     * @return bool
     */

    // Cac bien giao tiep cua payment
    var $api_endpoint = 'https://api-3t.paypal.com/nvp';
    var $url = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
   // var $api_endpoint	= 'https://api-3t.sandbox.paypal.com/nvp';
   // var $url 			= 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
    var $currency = 'USD';

    public function useView()
    {
        return false;
    }

    /**
     * Thuc hien thanh toan
     *
     * @param PaymentPayRequest $request
     * @return PaymentPayResponse
     */
    function paymentPay(PaymentPayRequest $request)
    {
        $return_url = $request->url_result;
        $tran_id = $request->tran->id;
        $amount = $request->amount;

        $amount = $this->_pp_get_amount($amount);

        $tran_detail = array(
            'L_NAME0' => 'Payment for invoice ' . $tran_id,
            'L_AMT0' => $amount,
            'L_QTY0' => 1,
        );

        $payment_data = array(
            'METHOD' => 'SetExpressCheckout',
            'VERSION' => '64.0',
            'USER' => $this->setting('user'),
            'PWD' => $this->setting('pwd'),
            'SIGNATURE' => $this->setting('sign'),
            'AMT' => $amount,
            'CURRENCYCODE' => $this->currency,
            'RETURNURL' => url_add_uri($return_url, 'return'),
            'CANCELURL' => url_add_uri($return_url, 'cancel'),
            'PAYMENTACTION' => 'Sale'
        );

        $payment_data = array_merge($payment_data, $tran_detail);

        $res_set = $this->_pp_get_res($payment_data);

        $ack = strtoupper($res_set["ACK"]);
        if ($ack == 'SUCCESS') {
            $token = urldecode($res_set["TOKEN"]);

            $url = $this->url . $token;
            return PaymentPayResponse::redirect($url);
        } else {
            exit('Error: ' . $res_set['L_LONGMESSAGE0']);
        }
    }

    /**
     * Lay payment result input
     *
     * @param PaymentResultInputRequest $request
     * @return PaymentResultInputResponse|null
     */
    public function paymentResultInput(PaymentResultInputRequest $request)
    {
        write_file_log('paypal_log_result_input.txt', current_url(1));
        write_file_log('paypal_log_result_input.txt', t('input')->post());
     /*   if ($request->page == 'notify') {
            $tran_id = t('input')->post('order_id');
            return new PaymentResultInputResponse([
                'tran_id' => $tran_id,
            ]);
        }*/
        return null;
    }

    /**
     * Xu ly ket qua thanh toan tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    public function paymentResult_(PaymentResultRequest $request)
    {
        $_ip = t('input')->ip_address();
        $result = t('input')->post();
        if (is_array($result)) {
            $result['ip'] = $_ip;
        }
        write_file_log('paypal_log_result.txt', current_url(1));
        write_file_log('paypal_log_result.txt', $result);


        //lay cac bien request
        $amount = $request->amount;
        $tran_id = $request->tran->id;

        //mac dinh thanh cong
        $status = PaymentStatus::SUCCESS;
        $error = '';

        //kiem tra IP
        /* if ( ! in_array($_ip, $ips))
         {
             $status = PaymentStatus::FAILED;
             $error  = lang('error_ip_is_not_valid');
             return $this->_ouput($request, $status, $error);
         }*/


        // Kiem tra ma so giao dich
        if ($tran_id != t('input')->post('order_id')) {
            $status = PaymentStatus::FAILED;
            $error = lang('error_tran_not_exist');;
            return $this->_ouput($request, $status, $error);
        }

        // Kiem tra amount
        $amount_pay = fv(t('input')->post('total_amount'));
        $amount = fv($amount);
        if ($amount_pay < $amount) {
            $status = PaymentStatus::FAILED;
            $error = lang('error_payment_amount_invalid');
            return $this->_ouput($request, $status, $error);
        }

        // Kiem tra trang thai giao dich
        if (t('input')->post('transaction_status') != 4) {
            $status = PaymentStatus::FAILED;
            $error = lang('error_payment_result_unsuccessful');
            return $this->_ouput($request, $status, $error);
        }

        //neu thanh cong
        return $this->_ouput($request, $status, $error);
    }

    public function paymentResult(PaymentResultRequest $request)
    {
        $_ip = t('input')->ip_address();
        $result = t('input')->post();
        if (is_array($result)) {
            $result['ip'] = $_ip;
        }
        write_file_log('paypal_log_result.txt', current_url(1));
        write_file_log('paypal_log_result.txt', $result);

        //lay cac bien request
        $amount = $request->amount;
        $tran_id = $request->tran->id;

        //mac dinh thanh cong
        $status = PaymentStatus::SUCCESS;
        $error = '';
        //==========
        // Result handle
        $rs = t('uri')->segment(4);
       // pr($rs);
        // Kiem tra ma so giao dich
        if ($rs != 'return') {
            $status = PaymentStatus::FAILED;
            $error = lang('error_tran_invalid');;
            return $this->_ouput($request, $status, $error);
        }


        // Chuyen amount sang tien giao dich cua payment
        $amount = $this->_pp_get_amount($amount);

        // Lay input
        $token = t('input')->get('token');
        $payer_id = t('input')->get('PayerID');

        // Xac thuc thong tin tra ve tu payment
        $payment_data = array(
            'METHOD' => 'GetExpressCheckoutDetails',
            'VERSION' => '64.0',
            'USER' => $this->setting('user'),
            'PWD' => $this->setting('pwd'),
            'SIGNATURE' => $this->setting('sign'),
            'TOKEN' => $token
        );

        $res_get = $this->_pp_get_res($payment_data);
        $ack = strtoupper($res_get["ACK"]);
        if ($ack != 'SUCCESS')
        {
            $status = PaymentStatus::FAILED;
            $error  = lang('error_tran_invalid');;
            return $this->_ouput($request, $status, $error);
        }



        // Kiem tra $payer_email co duoc phep su dung hay khong
        /*if (!$this->_pp_check_payer_email($tran_id, $res_get['EMAIL']))
        {
            $status = PaymentStatus::FAILED;
            $error  = 'Payer email not allowed use:'.$res_get['EMAIL'];
            return $this->_ouput($request, $status, $error);
        }*/

        // Thuc hien giao dich
        $payment_data = array(
            'METHOD' => 'DoExpressCheckoutPayment',
            'VERSION' => '64.0',
            'USER' => $this->setting('user'),
            'PWD' => $this->setting('pwd'),
            'SIGNATURE' => $this->setting('sign'),
            'TOKEN' => $token,
            'PAYERID' => $payer_id,
            'AMT' => $amount,
            'CURRENCYCODE' => $this->currency,
            'PAYMENTACTION' => 'Sale'
        );

        $res_do = $this->_pp_get_res($payment_data);
        $ack = strtoupper($res_do["ACK"]);

        if ($ack != 'SUCCESS')
        {
            $status = PaymentStatus::FAILED;
            $error  = lang('error_tran_invalid');;
            return $this->_ouput($request, $status, $error);
        }

        // Luu thong tin vao data
        $data = array();
        $data['payer_id'] = $payer_id;
        $data['payer_email'] = $res_get['EMAIL'];
        $data['tran_id'] = $res_do['TRANSACTIONID'];
        $data['tran_time'] = $res_do['ORDERTIME'];
        $data['amt'] = $amount;
        $data['currency_code'] = $res_do['CURRENCYCODE'];
        $data['status'] = $res_do['PAYMENTSTATUS'];
        $this->paymentResultOutputSave($tran_id, $data);

        // Tra thong tin ve he thong
        $payment_status = strtoupper($res_do["PAYMENTSTATUS"]);

        // Kiem tra ma so giao dich
        if ($payment_status != 'COMPLETED') {
            $status = PaymentStatus::FAILED;
            $error = lang('error_can_not_pay_tran');;
            return $this->_ouput($request, $status, $error);
        }

        //neu thanh cong
        return $this->_ouput($request, $status, $error);
    }

    /**
     * Du lieu tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    private function _ouput(PaymentResultRequest $request, $status, $error = '')
    {
        $payment_tran_id = t('input')->post('transaction_id');
        $response = [
            'status' => $status,
            'amount' => $request->amount,
            'tran_id' => $request->tran->id,
            'payment_tran_id' => $payment_tran_id,
            'payment_tran' => ['id' => $payment_tran_id],
        ];

        //neu co loi thi them thong bao loi
        if ($error) {
            $response['error'] = $error;
        }
        write_file_log('paypal_log_response.txt', $response);
        return new PaymentResultResponse($response);
    }

    public function paymentResultOutput(PaymentResultOutputRequest $request)
    {
        /*
        if (is_url_notiy)
        {
            return PaymentResultOutputResponse::content(json_encode([]));
        }
        */

        return null;
    }

    /**
     * Hien thi thong tin giao dich phat sinh ben cong thanh toan
     *
     * @param array $payment_tran
     * @return string|array
     */
    public function viewPaymentTran(array $payment_tran)
    {
        return $payment_tran;

    }


    //======================================================


    /**
     * Kiem tra $payer_email co duoc phep su dung hay khong
     */
    private function _pp_check_payer_email($tran_id, $payer_email)
    {
        // Neu khong ton tai email
        if (!valid_email($payer_email)) {
            return FALSE;
        }

        // Lay user_id
        $tran =  model('tran')->get_info($tran_id, 'user_id');
        if (!$tran || !$tran->user_id) {
            return FALSE;
        }

        // Lay paypal_emails
        $paypal_emails = model('user_verify')->get_paypal_emails($tran->user_id);
        if (!in_array($payer_email, $paypal_emails)) {
            return FALSE;
        }

        return TRUE;
    }


    /*
     * ------------------------------------------------------
     *  Paypal function
     * ------------------------------------------------------
     */
    /**
     * Xu ly amount theo dieu kien cua paypal
     */
    private function _pp_get_amount($amount)
    {
        $amount = number_format($amount, 2);

        return $amount;
    }

    /**
     * Lay du lieu tu api endpoint cua paypal
     */
    private function _pp_get_res($params)
    {
        $curl = curl_init($this->api_endpoint);
        $curl_query = http_build_query($params);

        curl_setopt($curl, CURLOPT_PORT, 443);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_query);

        $response = curl_exec($curl);

        if (!$response) {
            exit('Failed: ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
        } else {
            curl_close($curl);
        }

        $resArray = $this->_pp_deformat_nvp($response);

        return $resArray;
    }

    private function _pp_deformat_nvp($nvpstr)
    {
        $intial = 0;
        $nvpArray = array();

        while (strlen($nvpstr)) {
            //postion of Key
            $keypos = strpos($nvpstr, '=');
            //position of value
            $valuepos = strpos($nvpstr, '&') ? strpos($nvpstr, '&') : strlen($nvpstr);

            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval = substr($nvpstr, $intial, $keypos);
            $valval = substr($nvpstr, $keypos + 1, $valuepos - $keypos - 1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] = urldecode($valval);
            $nvpstr = substr($nvpstr, $valuepos + 1, strlen($nvpstr));
        }

        return $nvpArray;
    }
}
