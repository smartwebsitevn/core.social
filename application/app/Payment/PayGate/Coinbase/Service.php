<?php namespace App\Payment\PayGate\Coinbase;

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

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
use Coinbase\Wallet\Resource\Checkout;
use Coinbase\Wallet\Resource\Order;
use Coinbase\Wallet\Value\Money;

class Service extends PayGateService
{
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
    
   
    
    /**
     * Thuc hien thanh toan
     *
     * @param PaymentPayRequest $request
     * @return PaymentPayResponse
     */
    public function paymentPay(PaymentPayRequest $request)
    {
        t('lang')->load('modules/payment/bank_transfer');
        
        //load file thanh phan
        $return_url  = $request->url_result;
        $tran_id     = $request->tran->id;
        
        $paygate = $this->factory;

        $invoice_id = $request->tran->invoice->id;
        
		$payment = $this->model;

		$amount = $request->amount;

		$tran = $request->tran;
		
		$guide = $this->setting('guide');
		
		$data = compact('paygate', 'payment', 'guide', 'amount', 'tran', 'invoice_id');
		
		$data = array_merge($data, [
		    'action'  => $request->url_result,
		    'format_amount' => currency_format_amount($amount, $payment->currency_id),
		]);
		
        return PaymentPayResponse::tpl($paygate->viewPath('payment_pay'), $data);
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
        
        $this->save_payment_result($request->tran->id, t("input")->get_post());
        $response = [
            'status'  => PaymentStatus::NONE,
            'amount'  => $request->amount,
            'tran_id' => $request->tran->id,
            'payment_tran_id' => now(),
            'payment_tran' => ['id' => now()],
        ];
        
        return new PaymentResultResponse($response);
     
    }
    
 
    

    
}