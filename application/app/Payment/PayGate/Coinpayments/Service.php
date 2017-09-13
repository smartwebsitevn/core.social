<?php namespace App\Payment\PayGate\Coinpayments;

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
    var $api = null;
    
    /**
     * Thuc hien thanh toan
     *
     * @param PaymentPayRequest $request
     * @return PaymentPayResponse
     */
    private function _connect()
    {
		require(APPPATH.'app/Payment/PayGate/Coinpayments/lib/coinpayments.inc.php');
		$this->api = new  \CoinPaymentsAPI();
        $this->api->Setup($this->setting('private_key'), $this->setting('public_key'));
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
	    $business = $this->setting('business');
		$return_url = $request->url_result;
		$tran_id    = $request->tran->id;
		$tran_info  = 'Payment for invoice '.$tran_id;
		$amount     = $request->amount;
		
		//lấy tiền tệ mặc định
		//connect
		$this->_connect();
		$req = array(
		    'amount'    => $amount,
		    'currency1' =>$request->tran->currency_code,
		    'currency2' =>  $this->setting('currency'),
		   // 'address'   => $tran_id, // send to address in the Coin Acceptance Settings page
		    'item_name' => $tran_info,
		    'ipn_url'   => $return_url,
		);
		//pr($req);
		// See https://www.coinpayments.net/apidoc-create-transaction for all of the available fields
		$result = $this->api->CreateTransaction($req);
		write_file_log('log_pay_post.txt', $result);
		if ($result['error'] == 'ok') {
		    $invoice_order = $request->tran->invoice->invoice_orders[0];
		    if(!$invoice_order) return ;
		    
		    $url = $invoice_order->url('view');
		    
		    return PaymentPayResponse::redirect($url);
		}else{
		    pr($result['error']);
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
	    if($request->page == 'notify')
	    {
	        $tran_id = t('input')->post('order_id');
	        return new PaymentResultInputResponse([
	            'tran_id' => $tran_id,
	        ]);
	    }
	    return null;
	}
	
	/**
	 * Xu ly ket qua thanh toan tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	public function paymentResult(PaymentResultRequest $request)
	{ 
	    $result      = t('input')->post();
	    write_file_log('log_result_post.txt', $result);   
	    if(!$result)
	    {
	        $result   = file_get_contents('php://input');
	        $result   = json_decode($result);
	        write_file_log('log_result.txt', $result);
	    }   
	    $payment_tran_id = now();
	
	    //lay cac bien request
	    $amount     = $request->amount;
	    $tran_id    = $request->tran->id;
	    
	    $this->save_payment_result($tran_id, $result);
	    
	    //mac dinh thanh cong
	    $status = PaymentStatus::NONE;
	    $error  = '';
	    
	    // Kiem tra trang thai giao dich
	    if (!isset($result->error) || !isset($result->result))
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = 'Khong co du lieu tra ve';
	        return $this->_ouput($request, $status, $error, $payment_tran_id);
	    }
	
	    //lấy dữ liệu result
	    $result_output   = $result->result;
	    $payment_tran_id = $result_output->dest_tag; 
	    $address         = $result_output->address;
	    
	    // Kiem tra ma so giao dich
	    if ($tran_id != $address)
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_tran_not_exist');
	        return $this->_ouput($request, $status, $error, $payment_tran_id);
	    }

	    // Kiem tra trang thai giao dich
	    if ($result->error != 'ok')
	    {
	        $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_result_unsuccessful');
	        return $this->_ouput($request, $status, $error, $payment_tran_id);
	    }
	    
	    //neu thanh cong
	    $status = PaymentStatus::SUCCESS;
	    return $this->_ouput($request, $status, $error, $payment_tran_id);
	}
	
	/**
	 * Du lieu tra ve
	 *
	 * @param PaymentResultRequest $request
	 * @return PaymentResultResponse
	 */
	private function _ouput(PaymentResultRequest $request, $status, $error = '', $payment_tran_id = '')
	{
	    $response = [
	        'status'  => $status,
	        'amount'  => $request->amount,
	        'tran_id' => $request->tran->id,
	        'payment_tran_id' => $payment_tran_id,
	        'payment_tran' => ['id' => now()],
	    ];
	     
	    //neu co loi thi them thong bao loi
	    if($error)
	    {
	        $response['error'] = $error;
	    }
	    write_file_log('log_response_ouput.txt', $response);
	    return new PaymentResultResponse($response);
	}
	
	/**
	 * Chuyen tien
	 */
	public function transfer($amount, $receiving_merchant_id, $currency_code = '')
	{
	    if(!$currency_code)
	    {
	        $currency_code = $this->setting('currency');
	    }
	    
	    $this->_connect(); 
	    $result = $this->api->CreateTransfer($amount, $currency_code, $receiving_merchant_id);
	    if ($result['error'] == 'ok')
	    {
	        $this->_log($log.' Giao dich thanh cong ' . json_encode($result));
	        return TRUE;
	    } else {
	        $this->_log($log.' Giao dich that bai ' . json_encode($result));
	         return FALSE;
	    } 
	}
	
}
