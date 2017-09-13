<?php namespace App\Payment\PayGate\NganLuongV3;

use App\Payment\Library\PayGateService;
use App\Payment\Library\Payment\PaymentPayRequest;
use App\Payment\Library\Payment\PaymentPayResponse;
use App\Payment\Library\Payment\PaymentResultInputRequest;
use App\Payment\Library\Payment\PaymentResultInputResponse;
use App\Payment\Library\Payment\PaymentResultOutputRequest;
//use App\Payment\Library\Payment\PaymentResultOutputResponse;
use App\Payment\Library\Payment\PaymentResultRequest;
use App\Payment\Library\Payment\PaymentResultResponse;
use App\Payment\Library\PaymentStatus;

class Service extends PayGateService
{
    
    public $version		= '3.1';
    public $url 		= 'https://www.nganluong.vn/checkout.api.nganluong.post.php';
    public $cur_code    ='vnd';
    public $function	= 'SetExpressCheckout';
    public $payment_method		= 'NL';

    
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
        $user        = $request->user;
        $tran_info = 'Payment for invoice '.$tran_id;
	    $url = $this->_nl_create_url($return_url, $tran_info, $tran_id, $amount, $user);
    

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
       
        //lay cac bien request
        $amount     = $request->amount;
        $tran_id    = $request->tran->id;
        
        $this->save_payment_result($tran_id, $this->CI->input->get());
         
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
    private function _nl_create_url($return_url, $transaction_info, $order_code, $price,$user)
    {
        // Mảng các tham số chuyển tới nganluong.vn
       
        $arr_param = array(
            'cur_code'				=> $this->cur_code,
            'function'				=> $this->function,
            'version'				=> $this->version,
            'merchant_id'			=> strval($this->setting('merchant_id')), //Mã merchant khai báo tại NganLuong.vn
            'receiver_email'		=> strval($this->setting('business')),
            'merchant_password'		=> MD5($this->setting('merchant_pass')), //MD5(Mật khẩu kết nối giữa merchant và NganLuong.vn)
            'order_code'			=> strval($order_code), //Mã hóa đơn do website bán hàng sinh ra
            'total_amount'			=> strval($price), //Tổng số tiền của hóa đơn
            'payment_method'		=> $this->payment_method, //Phương thức thanh toán
            'payment_type'			=> '1', //Kiểu giao dịch: 1 - Ngay; 2 - Tạm giữ; Nếu không truyền hoặc bằng rỗng thì lấy theo chính sách của NganLuong.vn
            'order_description'		=> strval(urlencode($transaction_info)), //Mô tả đơn hàng
            'tax_amount'			=> '0', //Tổng số tiền thuế
            'fee_shipping'			=> '0', //Phí vận chuyển
            'discount_amount'		=> '0', //Số tiền giảm giá
            'return_url'			=> strtolower(urlencode($return_url)), //Địa chỉ website nhận thông báo giao dịch thành công
            'cancel_url'			=> '', //Địa chỉ website nhận "Hủy giao dịch"
            'buyer_fullname'		=> $user['name'], //Tên người mua hàng
            'buyer_email'			=> strval($user['email']), //Địa chỉ Email người mua
            'buyer_mobile'			=> strval($user['phone']), //Điện thoại người mua
            'buyer_address'			=> $user['address'], //Địa chỉ người mua hàng
            'total_item'			=> '1' //Tổng số sản phẩm trong đơn hàng
        );
        
       
        /* Bước 2. Kiểm tra  biến $redirect_url xem có '?' không, nếu không có thì bổ sung vào*/
        $redirect_url = $this->url;
               
        if (strpos($redirect_url, '?') === FALSE)
        {
            $redirect_url .= '?';
        }
        
        $post_field = '';
        foreach ($arr_param as $key => $value){
            if ($post_field != '') $post_field .= '&';
            $post_field .= $key."=".$value;
        }

        $post_field=$this->CheckoutCall($post_field);
        return isset($post_field->checkout_url) ? $post_field->checkout_url : site_url();
    }
    
    
    
    function CheckoutCall($post_field){
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->url);
        curl_setopt($ch, CURLOPT_ENCODING , 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
    
        if ($result != '' && $status==200){
            $xml_result = str_replace('&','&amp;',(string)$result);
            $nl_result  = simplexml_load_string($xml_result);
            $nl_result->error_message = $this->GetErrorMessage($nl_result->error_code);
        }
        else $nl_result->error_message = $error;
        return $nl_result;
        	
    }
    	
    function GetErrorMessage($error_code) {
        $arrCode = array(
            '00' => 'Thành công',
            '99' => 'Lỗi chưa xác minh',
            '06' => 'Mã merchant không tồn tại hoặc bị khóa',
            '02' => 'Địa chỉ IP truy cập bị từ chối',
            '03' => 'Mã checksum không chính xác, truy cập bị từ chối',
            '04' => 'Tên hàm API do merchant gọi tới không hợp lệ (không tồn tại)',
            '05' => 'Sai version của API',
            '07' => 'Sai mật khẩu của merchant',
            '08' => 'Địa chỉ email tài khoản nhận tiền không tồn tại',
            '09' => 'Tài khoản nhận tiền đang bị phong tỏa giao dịch',
            '10' => 'Mã đơn hàng không hợp lệ',
            '11' => 'Số tiền giao dịch lớn hơn hoặc nhỏ hơn quy định',
            '12' => 'Loại tiền tệ không hợp lệ',
            '29' => 'Token không tồn tại',
            '80' => 'Không thêm được đơn hàng',
            '81' => 'Đơn hàng chưa được thanh toán',
            '110' => 'Địa chỉ email tài khoản nhận tiền không phải email chính',
            '111' => 'Tài khoản nhận tiền đang bị khóa',
            '113' => 'Tài khoản nhận tiền chưa cấu hình là người bán nội dung số',
            '114' => 'Giao dịch đang thực hiện, chưa kết thúc',
            '115' => 'Giao dịch bị hủy',
            '118' => 'tax_amount không hợp lệ',
            '119' => 'discount_amount không hợp lệ',
            '120' => 'fee_shipping không hợp lệ',
            '121' => 'return_url không hợp lệ',
            '122' => 'cancel_url không hợp lệ',
            '123' => 'items không hợp lệ',
            '124' => 'transaction_info không hợp lệ',
            '125' => 'quantity không hợp lệ',
            '126' => 'order_description không hợp lệ',
            '127' => 'affiliate_code không hợp lệ',
            '128' => 'time_limit không hợp lệ',
            '129' => 'buyer_fullname không hợp lệ',
            '130' => 'buyer_email không hợp lệ',
            '131' => 'buyer_mobile không hợp lệ',
            '132' => 'buyer_address không hợp lệ',
            '133' => 'total_item không hợp lệ',
            '134' => 'payment_method, bank_code không hợp lệ',
            '135' => 'Lỗi kết nối tới hệ thống ngân hàng',
            '140' => 'Đơn hàng không hỗ trợ thanh toán trả góp',);
    
        return $arrCode[(string)$error_code];
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