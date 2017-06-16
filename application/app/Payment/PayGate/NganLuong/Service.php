<?php namespace App\Payment\PayGate\NganLuong;

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
        $amount      = $request->amount;

        $tran_info = 'Payment for invoice '.$tran_id;
	    $url = $this->_nl_create_url($return_url, $tran_info, $tran_id, $amount);
    
        return PaymentPayResponse::redirect($url);
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
            $tran_id =  $this->CI->input->get('order_code');
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
        //write_file_log('log_request.txt', $request);
        //write_file_log('log_result.txt', $this->CI->input->get());
        
        //lay cac bien request
        $amount     = $request->amount;
        $tran_id    = $request->tran->id;

        //mac dinh thanh cong
        $status = PaymentStatus::SUCCESS;
        $error  = '';
        
        $data = array();
        
        //Lấy mã đơn hàng
        $data['tran_id'] =  $this->CI->input->get("order_code");
        
        //Lấy thông tin giao dịch
        $data['transaction_info'] =  $this->CI->input->get("transaction_info");
        
        //Lấy tổng số tiền thanh toán tại ngân lượng
        $data['price'] =  $this->CI->input->get("price");
        
        //Lấy mã giao dịch thanh toán tại ngân lượng
        $data['payment_id'] =  $this->CI->input->get("payment_id");
        
        //Lấy loại giao dịch tại ngân lượng (1=thanh toán ngay ,2=thanh toán tạm giữ)
        $data['payment_type'] =  $this->CI->input->get("payment_type");
        
        //Lấy thông tin chi tiết về lỗi trong quá trình giao dịch
        $data['error_text'] =  $this->CI->input->get("error_text");
        
        //Lấy mã kiểm tra tính hợp lệ của đầu vào
        $data['secure_code'] =  $this->CI->input->get("secure_code");
        
        
        // Kiem tra ma so giao dich
        if ($tran_id != $data['tran_id'])
        {
            $status = PaymentStatus::FAILED;
	        $error  = lang('error_tran_not_exist');;
	        return $this->_ouput($request, $status, $error);
        }
        
        // Kiem tra amount
        $amount_pay = fv($data['price']);
        $amount 	= fv($amount);
        if ($amount_pay < $amount)
        {
            $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_amount_invalid');
	        return $this->_ouput($request, $status, $error);
        }
        
        // Xac thuc ket qua tra ve
        $check = $this->_nl_check_result($data['transaction_info'], $data['tran_id'], $data['price'], $data['payment_id'], $data['payment_type'], $data['error_text'], $data['secure_code']);
        if (!$check)
        {
            $status = PaymentStatus::FAILED;
	        $error  = lang('error_payment_result_unsuccessful');
	        return $this->_ouput($request, $status, $error);
        }
        
        //neu thanh cong
        return $this->_ouput($request, $status, $error);
    }
    
    public function paymentResultOutput(PaymentResultOutputRequest $request)
    {
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
    
        return __METHOD__;
    
        return [
            'aaa' => 'AAA',
            'bbb' => 'BBB',
        ];
    }
    
    
    /**
     * Du lieu tra ve
     *
     * @param PaymentResultRequest $request
     * @return PaymentResultResponse
     */
    private function _ouput(PaymentResultRequest $request, $status, $error = '')
    {
        $response = [
            'status'  => $status,
            'amount'  => $request->amount,
            'tran_id' => $request->tran->id,
            'payment_tran_id' => now(),
            'payment_tran' => ['id' => now()],
        ];
    
        //neu co loi thi them thong bao loi
        if($error)
        {
            $response['error'] = $error;
        }
        
        //write_file_log('log_response.txt', $response);
        
        return new PaymentResultResponse($response);
    }
    

    /*
     * ------------------------------------------------------
     *  NganLuong function
     * ------------------------------------------------------
     */
    /**
     * Ham tao url chuyen den nganluong
     */
    private function _nl_create_url($return_url, $transaction_info, $order_code, $price)
    {
        // Mảng các tham số chuyển tới nganluong.vn
        $arr_param = array(
            'merchant_site_code'=>	strval($this->setting('merchant_id')),
            'return_url'		=>	strtolower(urlencode($return_url)),
            'receiver'			=>	strval($this->setting('business')),
            'transaction_info'	=>	strval($transaction_info),
            'order_code'		=>	strval($order_code),
            'price'				=>	strval($price)
        );
    
        $secure_code ='';
        $secure_code = implode(' ', $arr_param) . ' ' . $this->setting('secure_pass');
        $arr_param['secure_code'] = md5($secure_code);
    
        /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
        $redirect_url = $this->setting('url');
        if (strpos($redirect_url, '?') === FALSE)
        {
            $redirect_url .= '?';
        }
        else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
        {
            // Nếu biến $redirect_url có '?' nhưng không kết thúc bằng '?' và có chứa dấu '&' thì bổ sung vào cuối
            $redirect_url .= '&';
        }
    
        /* Bước 3. tạo url*/
        $url = '';
        foreach ($arr_param as $key=>$value)
        {
            if ($url == '')
            {
                $url .= $key . '=' . $value;
            }
            else
            {
                $url .= '&' . $key . '=' . $value;
            }
        }
        return $redirect_url.$url;
    }
    
    /**
     * Hàm thực hiện xác minh tính đúng đắn của các tham số trả về từ nganluong.vn
     */
    private function _nl_check_result($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code)
    {
        // Tạo mã xác thực từ chủ web
        $str = '';
        $str .= ' ' . strval($transaction_info);
        $str .= ' ' . strval($order_code);
        $str .= ' ' . strval($price);
        $str .= ' ' . strval($payment_id);
        $str .= ' ' . strval($payment_type);
        $str .= ' ' . strval($error_text);
        $str .= ' ' . strval($this->setting('merchant_id'));
        $str .= ' ' . strval($this->setting('secure_pass'));
    
        // Mã hóa các tham số
        $verify_secure_code = '';
        $verify_secure_code = md5($str);
    
        // Xác thực mã của chủ web với mã trả về từ nganluong.vn ($payment_type == '1' -> Thanh toan ngay)
        if ($verify_secure_code === $secure_code && $payment_type == '1')
        {
            return TRUE;
        }
    
        return FALSE;
    }
    
}