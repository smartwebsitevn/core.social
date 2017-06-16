<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use App\Currency\Model\CurrencyModel;
use App\Deposit\Command\DepositCardCommand;
use App\Deposit\Job\DepositCard;
use App\Deposit\Library\CardDeposit;
use App\Deposit\Model\CardTypeModel;
use App\Purse\Model\PurseModel;
use App\Purse\PurseFactory;
use App\User\Model\UserModel;
use App\User\UserFactory;
use App\Accountant\AccountantFactory as AccountantFactory;
use App\Accountant\ChangeBalanceReason\Deposit as DepositReason;

class Renew_voucher extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();

        if (!user_is_login()) {
            redirect_login_return();
        }

        if (mod("product")->setting('premium_turn_off_function_renew_voucher')) {
            redirect();
        }


        $user = user_get_account_info();
        $this->data['user'] = $user;
        t('lang')->load('site/renew_voucher');
    }

    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $rules = array();
        $rules['key']           = array('key', 'required|trim|min_length[5]|max_length[50]|filter_html|xss_clean|callback__check_key');
        $rules['security_code'] = array('security_code', 'required|trim|callback__check_security_code');
        $this->form_validation->set_rules_params($params, $rules);
    }
    /*
            * Kiem tra su ton tai cua key
            */
    function _check_key($value){
        $code = model('voucher')->get_info_rule(array('key' => trim($value), 'status' => 0));
        if (!$code) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }
        // kiem tra xem co phai loai voucher vip ko
        $e = get_date($code->expired);
        if ($e > now()) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_key_expried'));
            return FALSE;
        }
        // kiem tra xem co phai loai voucher vip ko
        if (!in_array($code->type,['vip','buyout','coupon'])) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        $setting = json_decode($code->setting);

        switch($code->type){
            case 'vip':
                if (!(int)$setting->time) {
                    $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
                    return FALSE;
                }
                break;
            case 'buyout':
                if (!$setting->product_id && !$setting->lesson_id ) {
                    $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
                    return FALSE;
                }
                break;

        }

        return TRUE;

    }

    /**
     * Kiem tra ma bao mat
     */
    public function _check_security_code($value)
    {
        if (!lib('captcha')->check($value, 'four')) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return FALSE;
        }

        return TRUE;
    }

    function index()
    {
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->model('voucher_model');

        // Xu ly form
        if ($this->input->post('_submit'))
        {
            // Gan dieu kien cho cac bien
            $params = array('key', 'security_code');
            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run())
            {
                // Lay thong tin thanh vien
                $voucher = model('voucher')->get_info_rule(array( 'key' => $this->input->post('key')));
                // Neu thanh cong
                if ($voucher)
                {
                    switch($voucher->type){
                        case 'vip':
                            $this->_index_submit_voucher_vip($voucher,$result);
                            break;
                        case 'buyout':
                            $this->_index_submit_voucher_buyout($voucher,$result);
                            break;
                        case 'coupon':
                            $this->_index_submit_voucher_coupon($voucher,$result);
                            break;
                    }

                }


            }
            else
            {
                foreach ($params as $param)
                {
                    $result[$param] = form_error($param);
                }
            }
            // Form output
            $this->_form_submit_output($result);
        }
        $this->data['action'] = current_url();
        $this->data['captcha'] = site_url('captcha/four');
        // Hien thi view
        $this->_display();
    }
    /**
     * Xu ly index submit
     *
     * @return string
     */
    protected function _index_submit_voucher_vip($voucher,&$result = array())
    {
        $setting = json_decode($voucher->setting);
        $input=$output=[];
        $input['user']=$this->data['user'];
        $input['day']=$setting->time;
        // don hang
        mod('service_order')->create($input, $output);
        //Cap nhat su dung trang thai cho Ma Voucher
        mod('voucher')->used($voucher->id,$this->data['user']->id,$output["invoice"]->id,$output["invoice_order"]->id);

        set_message(lang('notice_renew_success'));
        // Khai bao du lieu tra ve
        $result['complete'] = TRUE;
        $result['location'] = site_url('user');

    }


    protected function _index_submit_voucher_buyout($voucher,&$result = array())
    {
        $setting = json_decode($voucher->setting);
        if (!$setting->product_id && !$setting->lesson_id ) {
            return FALSE;
        }

        $user=$this->data['user'];
        $input=[];
        $input['voucher']=$voucher;
        $input['user']=$user;
        $input['data_info']= array(
            'name'=>$user->name,
            'phone'=>$user->phone,
            'address'=>$user->address,
            'email'=>$user->email,
            'country'=>$user->country,
            'city'=>$user->city,
            'note'=>'Voucher Buyout'
        );;

        if ($setting->product_id) {
            //foreach($setting->product_id as $product_id ){
                $model = model('product')->get_info($setting->product_id);
                if($model){
                    $input['type']='product';
                    $input['model']=$model;
                    $invoice= mod("product")->invoice_create($input);
                }
            //}
        }
        elseif ($setting->lesson_id) {
           // foreach($setting->lesson_id as $lesson_id ){
                $model = model('lesson')->get_info($setting->lesson_id);
                if($model){
                    $input['model']=$model;
                    $input['type']='product';
                    $invoice= mod("product")->invoice_create($input);
                }
            //}
        }


        // them vao bang so huu
        mod('lesson_owner')->add($input['type'],$model->id, $user->id);

        $output = array(
            'location' => site_url('my-products')
        );
        set_message(lang('notice_renew_success'));
        $this->_response($output);
    }

    /**
     * Xu ly index submit
     *
     * @return string
     */
    protected function _index_submit_voucher_coupon($voucher,&$result = array())
    {
        $setting = json_decode($voucher->setting);
        //pr($setting);
        $input['limit']= array(0, 10);
        $lessons= $products=null;
        if($setting->lesson_id){
            $lessons= model('lesson')->filter_get_list(['ids'=>$setting->lesson_id,'show'=>1],$input);
           // pr_db();
            if($lessons){
                foreach($lessons as $row){
                    $row =mod("lesson")->add_info($row);
                }
            }
        }

        if($setting->product_id) {
            $products = model('product')->filter_get_list(['ids' => $setting->product_id,'show'=>1], $input);
            //pr_db();
            if ($products) {
                foreach ($products as $row) {
                    $row = mod("product")->add_info($row);
                }
            }
        }
        if($products || $products){
            $e = ["pos"=>"#result_voucher","data"=>view('tpl::renew_voucher/result',['products'=>$products,'lessons'=>$lessons],true)];
            $output['element']=$e;
        }
        else{
            $output['msg_modal']="Voucher này là loại giảm giá cho tất cả khóa học và bài học, vui lòng chọn mua khóa học, bài học sau đó nhập mã này vào. Xin cảm ơn đã sử dụng dịch vụ của chúng tôi";
        }

        // Khai bao du lieu tra ve
        $this->_response($output);

    }
}


