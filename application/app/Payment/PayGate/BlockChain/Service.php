<?php namespace App\Payment\PayGate\BlockChain;

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
    public function get_xpub()
    {
        $xpubs = $this->setting('xpub');
        $xpubs = explode("\n", $xpubs);
        $xpub  = array_rand($xpubs, 1);

        return (isset($xpubs[$xpub])) ? trim($xpubs[$xpub]) : '';
    }

    /**
     * Co the thuc hien thanh toan qua payment hay khong
     *
     * @return bool
     */
    public function canPayment()
    {
        return true;
    }

    /**
     * Co the thuc hien rut tien qua payment hay khong
     *
     * @return bool
     */
    public function canWithdraw()
    {
        return true;
    }

    function test()
    {
        $bc = $this->_bc();
        try {
            $rs = $bc->Rates->get();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return FALSE;
        }
        return $rs;
    }

    /**
     * Thuc hien thanh toan
     *
     * @param PaymentPayRequest $request
     * @return PaymentPayResponse
     */
    public function paymentPay(PaymentPayRequest $request)
    {
        //load file thanh phan
        $return_url  = $request->url_result;
        $tran_id     = $request->tran->id;
        $paygate     = $this->factory;

        $invoice_order = $request->tran->invoice->invoice_orders[0];
        if(!$invoice_order) return ;

        $url = $invoice_order->url('view');

        $receive_address = $this->_create_receive_address($return_url);

        write_file_log('log_block_chain_paymentPay.txt', 'Address: '.$receive_address.' URL : '.$return_url);

        if ($receive_address)
        {
            model('invoice_order')->update($invoice_order->id, array('btc_address' => $receive_address));
        }
        return PaymentPayResponse::redirect($url);
    }


    /**
     * Xu ly ket qua thanh toan tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    public function paymentResult(PaymentResultRequest $request)
    {
        $_ip = t('input')->ip_address();
        $amount = $request->amount;
        $tran   = $request->tran;

        $data_get = t('input')->get();
        $data_get['ip'] = $_ip;
        $data_get['amount'] = $amount;

        write_file_log('log_block_chain_paymentResult.txt', $data_get);
        $this->save_payment_result($request->tran->id, $data_get);


        $invoice_order = $request->tran->invoice->invoice_orders[0];
        if(!$invoice_order) return ;

        //mac dinh thanh cong
        $status = PaymentStatus::SUCCESS;
        $error  = '';

        $bc = $this->_bc();
        // Kiem tra ip

        //lay IP cau hinh va kiem tra
        $ips = array();
        $ip  = $this->setting('ip');
        $ip  = explode(',', $ip) ;
        if(is_array($ip))
        {
            foreach ($ip as $i)
            {
                $ips[] = trim($i);
            }
        }

        if ( ! in_array($_ip, $ips))
        {
            /*
            $status = PaymentStatus::FAILED;
            $error  = lang('error_ip_is_not_valid');
            return $this->_ouput($request, $status, $error);
            */
        }

        // Kiem tra amount
        $amount_pay = t('input')->get('value');
        $amount_pay = $this->_get_amount_res($amount_pay) + 0.001; // Fix nguoi nhan chiu phi
        if (fv($amount_pay) < fv($amount)) {
            $status = PaymentStatus::FAILED;
            $error  = lang('error_payment_amount_invalid');
            return $this->_ouput($request, $status, $error);
        }

        // Kiem tra dia chi nhan
        $receive_address= $invoice_order->btc_address;
        $address = t('input')->get('address');
        if ($address != $receive_address) {
            $status = PaymentStatus::FAILED;
            $error  = lang('error_payment_btc_address_invalid', $address);
            return $this->_ouput($request, $status, $error);
        }

        // Kiem tra confirmations (la so lan ma btc da confirm thuong la >4 lan )
        $confirmations = (int)t('input')->get('confirmations');
        $confirmations_setting = (int)$this->setting('confirmations');
        $confirmations_setting = max(1, $confirmations_setting);

        //cập nhật số lần btc_confirm
        model('invoice_order')->update($invoice_order->id, array('btc_confirm' => $confirmations));

        if ($confirmations < $confirmations_setting) {
            $status = PaymentStatus::NONE;
            $error  = lang('error_payment_confirmations_invalid', $confirmations);
            return $this->_ouput($request, $status, $error);
        }

        // Cap nhat trang thai thanh toan
        //neu thanh cong
        return $this->_ouput($request, $status, $error);
    }

    public function paymentResultOutput(PaymentResultOutputRequest $request)
    {
        // Kiem tra confirmations (la so lan ma btc da confirm thuong la >4 lan )
        $confirmations = (int)t('input')->get('confirmations');
        $confirmations_setting = (int)$this->setting('confirmations');
        $confirmations_setting = max(1, $confirmations_setting);

        $content = NULL;
        if ($confirmations >= $confirmations_setting)
        {
            $content = '*ok*';
        }
        write_file_log('log_block_chain_result_output.txt', $content);

        return PaymentResultOutputResponse::content($content);
    }

    /**
     * Du lieu tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    private function _ouput(PaymentResultRequest $request, $status, $error = '')
    {
        $transaction_hash = t('input')->get('transaction_hash');

        $response = [
            'status'  => $status,
            'amount'  => $request->amount,
            'tran_id' => $request->tran->id,
            'payment_tran_id' => $transaction_hash,
            'payment_tran' => ['id' => now()],
        ];

        //neu co loi thi them thong bao loi
        if($error)
        {
            $response['error'] = $error;
        }

        write_file_log('log_block_chain_output.txt', $response);

        return new PaymentResultResponse($response);
    }

    /*
     * Chuyển tiền
     */
    function transfer($amount, $btc_address, $content_transfer)
    {
        // Lay so du truoc khi giao dich
        $balance_pre = $this->_get_balance();

        $content = 'balance_pre : '.$balance_pre. ' - btc_address: '.$btc_address. ' - content_transfer: ' .$content_transfer;
        write_file_log('log_block_chain_transfer_response_pre.txt', $content);

        // Xac thuc so du truoc khi giao dich
        if (!$balance_pre || $balance_pre < $amount)
        {
            write_file_log('log_block_chain_transfer_response.txt', "So du khong du de thanh toan {$balance_pre} < {$amount}");
            return FALSE;
        }

        $bc = $this->_bc();
        $response = null;
        try
        {
            $response = $bc->Wallet->send($btc_address, $amount, $this->setting('address'), "0.0001", $content_transfer);
            write_file_log('log_block_chain_transfer_response.txt', $response);
        } catch (\Exception $e)
        {
            $response = $e->getMessage();
            write_file_log('log_block_chain_transfer_response.txt', $response);
            return FALSE;
        }

        return true;
    }

    // --------------------------------------------------------------------
    /**
     * Lay amount tu amount tra ve cua payment
     *
     * @param int $amount
     * @return float
     */
    protected function _bc()
    {
        $blockchain = new \Blockchain\Blockchain($this->setting('key'));
        $blockchain->setServiceUrl($this->setting('url_api'));
        $blockchain->Wallet->credentials($this->setting('wid'), $this->setting('password'));

        return $blockchain;
    }

    /**
     * Lay rate usd
     *
     * @return false|float
     */
    function _get_rate()
    {
        $rates=file_get_contents("https://blockchain.info/ticker");
        $rates =json_decode($rates);

        if(!isset($rates->USD)){
          //  history('payment', $this->code, __METHOD__ . " error: cannot get rate");
            return FALSE;
        }
        // echo '<br>Chuyen doi:';
        // $response = $rates['USD']->m15* $this->_get_amount_res((float)$response);
        // ti gia qui doi so voi USD

        $rate=[];
        $rate['last']=$rates->USD->last;
        $rate['sell']=$rates->USD->sell;
        $rate['buy']=$rates->USD->buy;

        return  $rate ;
    }
    function get_rate_()
    {
        $bc = $this->_bc();
        try {
            $rs = $bc->Rates->get();
        } catch (\Exception $e) {
            $response = $e->getMessage();
            return FALSE;
        }

        $rate = [];
        $rate['last'] = $rs['USD']->last;
        $rate['sell'] = $rs['USD']->sell;
        $rate['buy']  = $rs['USD']->buy;

        return  $rate ;
    }

    /**
     * Lay balace
     *
     * @return false|float
     */
    protected function _get_balance()
    {
        $bc = $this->_bc();
        try {
            $response = $bc->Wallet->getBalance();

        } catch (\Exception $e) {
            $response = $e->getMessage();
            write_file_log('log_block_chain_get_balance.txt', $response);
            return FALSE;
        }
        write_file_log('log_block_chain_get_balance.txt', $response);

        return $response ;
    }

    /**
     * Lay amount de request len payment
     *
     * @param float $amount
     * @return int
     */
    protected function _get_amount_req($amount)
    {
        $v = $amount * $this->setting('round');

        return round($v);
    }



    /**
     * Lay amount tu amount tra ve cua payment
     *
     * @param int $amount
     * @return float
     */
    protected function _get_amount_res($amount)
    {
        $v = $amount / $this->setting('round');
        $precision = $this->setting('round') / 10;

        return round($v, $precision);
    }


    /**
     * Tao address nhan
     *
     * @param string $callback
     * @return string|false
     */
    protected function _create_receive_address($callback)
    {
        $xpub = $this->get_xpub();
        $bc = $this->_bc();
        try {
            //v2
            $response = $bc->ReceiveV2->generate($this->setting('key'), $xpub, $callback);
            write_file_log('log_block_chain_create_receive_address.txt', $response);
            // Show receive address to user:
        } catch (\Exception $e)
        {
            $response = $e->getMessage();
            write_file_log('log_block_chain_create_receive_address_error.txt', $response);
            return FALSE;
        }

        return $response->getReceiveAddress();
    }



}