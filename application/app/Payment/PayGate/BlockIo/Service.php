<?php namespace App\Payment\PayGate\BlockIo;

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

require_once APPPATH.'/app/Payment/PayGate/BlockIo/blockio/BlockIo.php';

class Service extends PayGateService
{
    var $block_io  = '';
   
    
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

    /*
     * 
     */
    public function test()
    {
        $this->_connect();
        $balance = $this->_get_balance();
        return $balance;
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
        $amount      = $request->amount;
        $tran_id     = $request->tran->id;
        $paygate     = $this->factory;
        
        $invoice_order = $request->tran->invoice->invoice_orders[0];
        if(!$invoice_order) return ;
        
        $url = $invoice_order->url('view');
        
        $this->_connect();
        $receive_address = $this->_create_receive_address($return_url, $invoice_order->id, $tran_id, $amount);
        
        $this->_log('Address: '.$receive_address.' URL : '.$return_url);
        
        if ($receive_address)
        {
            model('invoice_order')->update($invoice_order->id, array('btc_address' => $receive_address));
        }
        return PaymentPayResponse::redirect($url);
      
    }

    public function paymentResultOutput(PaymentResultOutputRequest $request)
    {
        //nếu đúng trả về mã 200
        $content = null;

        $inputData   = file_get_contents('php://input');
        
        if(!$inputData || $inputData == '{"type":"ping"}') {
            return false;
        }
        $payResponse = json_decode($inputData);
        if(!$payResponse) {
            return false;
        }
        if(!isset($payResponse->data))
        {
            return false;
        }
        //confirm
        $reponse = $payResponse->data;
        $confirmations = (int)$reponse->confirmations;
        if ($confirmations >= $this->setting('confirmations')) {
            $content = '200';
        }
        
        $this->_log('paymentResultOutput: '. $content);
        return PaymentResultOutputResponse::content($content);
    }

    /**
     * Xu ly ket qua thanh toan tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    public function paymentResult(PaymentResultRequest $request)
    {  
        $amount = $request->amount;
        $tran   = $request->tran;
        //mac dinh thanh cong
        $status = PaymentStatus::SUCCESS;
        $error  = '';
        
        $inputData   = file_get_contents('php://input');
        $this->_log('paymentResult $inputData:'. $inputData);

        $this->save_payment_result($request->tran->id, $inputData);


        //thông tin đơn hàng
        $invoice_order = $request->tran->invoice->invoice_orders[0];
        if(!$invoice_order)
        {
            $this->_log('Empty invoice order');
            $status = PaymentStatus::FAILED;
            return $this->_ouput($request, $status, $error);
        }
        
        if(!$inputData || $inputData == '{"type":"ping"}') {
            $status = PaymentStatus::NONE;
            return $this->_ouput($request, $status, $error);
        }
        
        $payResponse = json_decode($inputData);
        if(!$payResponse) {
            $status = PaymentStatus::NONE;
            return $this->_ouput($request, $status, $error);
        }

        //return NULL;
        if (!isset($payResponse->data)) {
            $status = PaymentStatus::FAILED;
	        return $this->_ouput($request, $status, $error);
        }
        
        $reponse = $payResponse->data;
        // Kiem tra amount
        $amount_pay = $reponse->amount_received;
        if (fv($amount_pay) < fv($amount)) {
             
            $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_amount_invalid');
	        return $this->_ouput($request, $status, $error, $reponse);
        }
        
       // Kiem tra dia chi nhan
        $receive_address= $invoice_order->btc_address;
        $address = $reponse->address;
        if ($address != $receive_address) 
        {
            $status = PaymentStatus::FAILED;
            $error  = lang('error_payment_btc_address_invalid', $address);
            return $this->_ouput($request, $status, $error, $reponse);
        }
        
        // Kiem tra confirmations (la so lan ma blockio da confirm thuong la >4 lan )
        $confirmations = (int)$reponse->confirmations;
        if ($confirmations < $this->setting('confirmations')) 
        {
            $status = PaymentStatus::NONE;
            $error  = lang('error_payment_confirmations_invalid', $confirmations);
            return $this->_ouput($request, $status, $error, $reponse);
        }

        //neu thanh cong
        return $this->_ouput($request, $status, $error, $reponse);
    }
    
    /**
     * Du lieu tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    private function _ouput(PaymentResultRequest $request, $status, $error = '', $reponse = '')
    {
        $response = [
            'status'  => $status,
            'amount'  => $request->amount,
            'tran_id' => $request->tran->id,
            'payment_tran_id' => (isset($reponse->txid)) ? $reponse->txid : now(),
            'payment_tran' => ['id' => now()],
        ];
    
        //neu co loi thi them thong bao loi
        if($error)
        {
            $response['error'] = $error;
        }
    
        write_file_log('log_blockio_output.txt', $response);
    
        return new PaymentResultResponse($response);
    }
    
    /**
     * Chuyen tien
     */
    public function transfer($amount, $btc_address, $content_transfer = '')
    {
        $this->_connect();
        // Lay so du truoc khi giao dich
        $balance_pre = $this->_get_balance();
        
        // Xu ly input
        $amount = max(0, floatval($amount));
        $log = 'balance_pre : '.$balance_pre. ' - btc_address: '.$btc_address. ' - content_transfer: ' .$content_transfer;
        
        // Xac thuc so du truoc khi giao dich
        if (!$balance_pre || $balance_pre < $amount) 
        {
            $this->_log($log. ": So du khong du de thanh toan {$balance_pre} < {$amount}");
            return FALSE;
        }
    
        //$response = $this->block_io->withdraw_from_addresses(array('amounts' => $amount, 'from_addresses' => $this->setting('address'), 'to_addresses' => $btc_address));
        $response = $this->block_io->withdraw(array('amounts' => $amount, 'to_addresses' => $btc_address));
         
        $this->_log($log.' Giao dich thanh cong ' . json_encode($response));
    
        return TRUE;
    }
    
    
    
    /**
     * Lay balace
     *
     * @return false|float
     */
    protected function _get_balance($type = 'network')
    {
        $getBalanceInfo = $this->block_io->get_balance();
         
        return isset($getBalanceInfo->data->available_balance) ? $getBalanceInfo->data->available_balance : 0;
    }
    
    /**
     * kết nối tới cổng thanh toán
     *
     */
    private function _connect()
    {  
        $this->block_io = new \BlockIo(
            $this->setting('apiKey'),
            $this->setting('pin'),
            $this->setting('version')
        );
    }
    
    /**
     * Tao address nhan
     *
     * @param string $callback
     * @return string|false
     */
    protected function _create_receive_address($callback, $invoice_order_id, $tran_id, $amount)
    {
        $request_id = $invoice_order_id.'_'.now();
        $label = 'blockio_'.$request_id;
        
        //cap nhat request ID
        model('invoice_order')->update($invoice_order_id, array('request_id' => $label));
        model('tran')->update($tran_id, array('request_id' => $label));
        
        $getNewAddressInfo = $this->block_io->get_new_address(array('label' => $label));
        $address = isset($getNewAddressInfo->data->address) ? $getNewAddressInfo->data->address : '';
        $this->block_io->create_notification(array('url' => $callback, 'type' => 'address', 'address' => $address));
        
        $data = array(
            'invoice_order_id' => $invoice_order_id,
            'amount'   => $amount,
            'label'    => $label,
            'address'  => $address,
            'created'  => now()
        );
        model('btc_address_list')->create($data);
        
        return $address;
    }
    

    protected function _log($content)
    {
        $log = t('input')->ip_address() . ': ' . get_date(now(), "full");
        file_put_contents('log_blockio.txt', $log . $content . PHP_EOL, FILE_APPEND);
    
    }
    
}