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


class Renew_plan extends MY_Controller
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

        if (mod("product")->setting('premium_turn_off_function_renew_plan')) {
            redirect();
        }


        t('lang')->load('modules/deposit/common');
        t('lang')->load('modules/deposit/deposit_card');
        t('lang')->load('site/renew_plan');


        $user = user_get_account_info();
        // kiem tra dich vu cua thanh vien
        $service_order = model('service_order')->get_info_rule(array('user_id' => $user->id));
        if ($service_order && $service_order->status == 'suspended'){
            set_message_display('modal');
            set_message(lang('notice_renew_deny_service_blocked'));
            redirect();
        }

        $this->data['user'] = $user;


    }

    /**
     * Gan dieu kien cho cac bien
     */
    protected function _set_rules($params)
    {
        $rules = array();
        $rules['plan'] = array('plan', 'required|trim|xss_clean|callback__check_plan_id');

        $rules['payment_method'] = array('payment_method', 'required|trim|callback__check_payment_method');
        $rules['amount_balance'] = array('amount_balance', 'trim|callback__check_amount_balance');

        // card info
        $rules['type'] = array('card_type', 'required|trim|filter_html|callback__check_type');
        $rules['code'] = array('card_code', 'required|trim|min_length[5]|max_length[30]|filter_html|xss_clean');
        $rules['serial'] = array('card_serial', 'required|trim|min_length[5]|max_length[30]|filter_html|xss_clean');
        $rules['card'] = array('card', 'callback__check_card');

        $rules['security_code'] = array('security_code', 'required|trim|callback__check_security_code');

        $this->form_validation->set_rules_params($params, $rules);
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

    /**
     * Kiem tra id comment cha
     */
    function _check_plan_id($value)
    {
        $this->load->model('plan_model');
        $row = $this->plan_model->get_info($value);
        if (!$row) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_invalid'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra user_security
     */
    public function _check_user_security()
    {
        if (!mod('user_security')->valid('payment')) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_incorrect'));
            return false;
        }

        return true;
    }

    /**
     * Kiem tra payment_method
     */
    public function _check_payment_method($value)
    {
        if (!in_array($value, array('balance', 'card'))) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Kiem tra balance amount
     */
    public function _check_amount_balance($value)
    {

        $plan_id = $this->input->post('plan');
        $plan = mod('plan')->get_info($plan_id);
        if (!$plan) {
            return true;
        }
        $amount = $plan->cost_new;
        $purse = $this->_getUserPurseVnd();

        // echo 'balanc:'.$purse->balance .' - amount='.$amount;
        if ($purse->balance < $amount) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_balance_not_enought'));
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Kiem tra type
     */
    public function _check_type($value)
    {
        if (!$this->_get_card_type($value)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_value_not_exist'));
            return false;
        }

        return TRUE;
    }

    /**
     * Kiem tra thong tin card
     */
    public function _check_card()
    {
        $ip = t('input')->ip_address();

        // Ip hien tai da bi block
        if (model('ip_block')->check($ip)) {
            $this->form_validation->set_message(__FUNCTION__, lang('notice_ip_blocked', $ip));
            return false;
        }

        // Gui request den cac providers
        $input = $this->_get_input();

        $providers = array_filter([$input['type']->provider, $input['type']->provider_sub]);

        list($status, $result, $provider) = $this->_request_check_card($providers, $input);



        // Xu ly so lan check card
        $this->_handle_count_check($status);
        // Neu card khong hop le
        if (!$status) {
            $message = array($result);

            if ($m = $this->_make_warning_block_ip()) {
                $message[] = $m;
            }
            $this->form_validation->set_message(__FUNCTION__, implode('<br>', $message));
            return false;
        }

        $this->data['card'] = array_merge($input, $result, compact('provider'));

        return true;
    }

    /**
     * Gui yeu cau check card
     *
     * @param array $providers
     * @param array $input
     * @return array [$status, $result, $provider]
     */
    protected function _request_check_card(array $providers, array $input)
    {
        $status = false;
        $result = null;
        $provider = null;

        foreach ($providers as $provider) {
            list($status, $result) = $this->_request_provider_check_card($provider, $input);

            if ($status) break;
        }

        return [$status, $result, $provider];
    }

    /**
     * Gui yeu cau den provider check card
     *
     * @param string $provider
     * @param array $input
     * @return array
     */
    protected function _request_provider_check_card($provider, array $input)
    {
        $log_id = $this->_log($provider, $input);

        list($status, $result) = $this->_perform_check_card(
            $provider,
            $input['type']->key,
            $input['code'],
            $input['serial']
        );

        $this->_log_result($log_id, $status, $result);

        return [$status, $result];
    }

    /**
     * Luu log
     *
     * @param string $provider
     * @param array $input
     * @return int
     */
    protected function _log($provider, array $input)
    {
        $log_id = 0;

        model('deposit_card_log')->create([
            'provider' => $provider,
            'type' => $input['type']->key,
            'code' => $input['code'],
            'serial' => $input['serial'],
            'status' => mod('deposit_card_log')->status('pending'),
            'user_id' => user_get_account_info()->id,
            'ip' => t('input')->ip_address(),
        ], $log_id);

        return $log_id;
    }

    /**
     * Luu log result
     *
     * @param int $log_id
     * @param bool $api_status
     * @param string|array $api_result
     */
    protected function _log_result($log_id, $api_status, $api_result)
    {
        if ($api_status) {
            model('deposit_card_log')->update($log_id, [
                'amount' => $api_result['amount'],
                'status' => mod('deposit_card_log')->status('completed'),
                'message' => 'success',
                'result' => json_encode($api_result['data']),
            ]);
        } else {
            model('deposit_card_log')->update($log_id, [
                'status' => mod('deposit_card_log')->status('failed'),
                'message' => (string)$api_result,
            ]);
        }
    }

    /**
     * Tao noi dung canh bao block ip
     *
     * @return string
     */
    protected function _make_warning_block_ip()
    {
        $count_max = mod('deposit_card')->setting('fail_count_max');

        if (!$count_max) return;

        $max = mod('deposit_card')->setting('fail_count_max');
        $minute = mod('deposit_card')->setting('fail_block_timeout');

        return lang('notice_warning_block_ip', compact('max', 'minute'));
    }

    /**
     * Lay input
     *
     * @return array
     */
    protected function _get_input()
    {
        $type_id = $this->input->post('type');
        $code = $this->input->post('code');
        $serial = $this->input->post('serial');

        $type = $this->_get_card_type($type_id);

        return compact('type_id', 'type', 'code', 'serial');
    }

    /**
     * Lay thong tin card type
     *
     * @param int $id
     * @return false|object
     */
    protected function _get_card_type($id)
    {
        return model('card_type')->get_info_rule(array(
            'id' => $id,
            'status' => 1,
        ));
    }

    /**
     * Lay danh sach card type
     */
    protected function _get_card_types()
    {
        return mod('card_type')->get_list(array('status' => 1));
    }


    /**
     * Lay purse VND cua user hien tai
     *
     * @return UserModel
     * @throws Exception
     */
    protected function _getUserPurseVnd()
    {
        $currency = CurrencyModel::findWhere(['code' => 'VND']);

        if (!$currency) {
            throw new Exception('Currency VND not found');
        }

        $user = UserFactory::auth()->user();

        $purse = PurseFactory::purse()->get($user, $currency);

        if (!$purse) {
            throw new Exception("Can't get purse of user");
        }

        return $purse;
    }


    /**
     * Goi den api kiem tra card
     *
     * @param string $type
     * @param string $code
     * @param string $serial
     * @return array
     */
    protected function _perform_check_card($provider, $type, $code, $serial)
    {
        $api_result = array();
        $api_status = t('payment_card')->$provider->check($type, $code, $serial, $api_result);

        /*$api_status = true;
        $api_result['amount'] = 110000;
        $api_result['data'] = $this->input->post();*/

        /* $api_status = false;
        $api_result = 'error'; */

        return [$api_status, $api_result];
    }

    /**
     * Xu ly so lan check card
     *
     * @param bool $api_status
     */
    protected function _handle_count_check($api_status)
    {
        if (!$api_status) {
            $this->_handle_count_fail();
        } else {
            if (model('ip')->action_count_get('deposit_card_fail')) {
                model('ip')->action_count_set('deposit_card_fail', 0);
            }
        }
    }

    /**
     * Xu ly so lan nhap sai card
     */
    protected function _handle_count_fail()
    {
        $count_max = mod('deposit_card')->setting('fail_count_max');
        $block_timeout = mod('deposit_card')->setting('fail_block_timeout') * 60;

        if (!$count_max) return;

        $count = model('ip')->action_count_change('deposit_card_fail', 1);

        if ($count >= $count_max) {
            $ip = t('input')->ip_address();

            model('ip_block')->set($ip, $block_timeout);
            model('ip')->action_count_set('deposit_card_fail', 0);
        }
    }

    /**
     * Tao Lich su
     */
    protected function _history()
    {
        $this->_list([
            'input' => [
                'select' => 'deposit_card.*, tran.created',
                'where' => [
                    'tran.user_id' => user_get_account_info()->id,
                    'deposit_card.status' => mod('order')->status('completed'),
                ],
            ],
            'page_size' => 10,
            'display' => false,
        ]);
    }

    /**
     * Danh sach
     */
    function index()
    {

        $user = $this->data['user'];
        // Tai cac file thanh phan
        $this->load->library('form_validation');
        $this->load->helper('form');
        // Cap nhat amount
        if ($this->input->get('act') == 'update') {
            $plan_id = $this->input->get('plan');
            if ($this->_check_plan_id($plan_id)) {
                $plan = mod('plan')->get_info($plan_id);
                $amount = $plan->cost_new;
                $data = array();
                $data['_amount'] = currency_format_amount($amount);
                $output = json_encode($data);
                set_output('json', $output);
            }
            return;

        }

        // Xu ly form
        if ($this->input->post('_submit')) {
            // Gan dieu kien cho cac bien
            $params = array('plan', 'payment_method', 'security_code');
            $payment_method = $this->input->post('payment_method');
            if ($payment_method == 'balance') {
                $user_security = mod('user_security')->param();
                $params = array_merge($params, array('amount_balance', $user_security));
                $this->form_validation->set_rules($user_security, 'lang:security_value', 'required|callback__check_user_security');
            } else {
                $params = array_merge($params, array('type', 'code', 'serial'));
            }

            $this->_set_rules($params);

            // Xu ly du lieu
            $result = array();
            if ($this->form_validation->run()) {
                // Xoa captcha
                //$this->captcha_library->del('four');
                $purse = $this->_getUserPurseVnd();
                $plan_id = $this->input->post('plan');
                $plan = mod('plan')->get_info($plan_id);
                $amount = $plan->cost_new;
                $this->data['cart'] = array(
                    'title' => $plan->name,
                    'day' => $plan->day,
                    'purse' => $purse,
                    'user' => $user,
                    'amount' => $amount,
                    //'coupon' 	=> $coupon,
                );


                if ($payment_method == 'balance') {
                    $result['complete'] = true;
                    $this->_index_submit_balance($result);
                } else {
                    // Reset rules
                    $this->form_validation->reset_rules();
                    // Gan dieu kien cho cac bien
                   //  $params = array('card');
                    $params = array_merge($params, array('card',));
                    $this->_set_rules($params);
                    // Xu ly du lieu
                    if ($this->form_validation->run()) {
                        $result['complete'] = true;
                        $this->_index_submit_card($result);
                    }
                }
                $result['location'] = site_url('user');
            }

            if (empty($result['complete'])) {
                foreach ($params as $param) {
                    $result[$param] = form_error($param);
                }
            }

            // Form output
            $this->_form_submit_output($result);
        }

        $this->_index_view();
    }

    protected function _index_view()
    {
        // Luu bien gui den view
        // Lay danh sach
        $list = model('plan')->get_list();
        foreach ($list as $row) {
            $row = mod('plan')->add_info($row);
        }
        $controller = $this->uri->segment(1);
        if($controller == 'renew_plan')
            $title = lang('title_renew_plan');
        else
            $title = lang('title_order_plan');

        $this->data['title'] = $title;
        $this->data['list'] = $list;
        $this->data['action'] = current_url();
        $this->data['url_update'] = $this->data['action'] . '?act=update';
        $this->data['captcha'] = site_url('captcha/four');
        $this->data['purse'] = $this->_getUserPurseVnd();

        $this->data['types'] = mod('card_type')->get_list(array('status' => 1));
        $this->data['input'] = array_only((array)t('input')->get(), array('type', 'code', 'serial'));

        // Hien thi view
        $this->_display();
    }

    /**
     * Xu ly index submit
     *
     * @return string
     */
    protected function _index_submit_balance(&$result = array())
    {
        $cart = $this->data['cart'];

        $output = array();
       // $this->_mod()->create($cart, $output);
        mod('service_order')->create($cart, $output);
        // tru tien
        $reason = DepositReason::make($output['invoice_order']);
        AccountantFactory::balance()->sub($cart['purse'], $cart['amount'], $reason);

        set_message(lang('notice_renew_success'));

    }

    protected function _index_submit_card(&$result = array())
    {
        try {
            $cart = $this->data['cart'];
            $card = $this->data['card'];

            // su ly menh gia the
            if ($card['amount']) {

                // neu menh gia the nho hon so tien goi, thi nap tien do vao so du
                if ($card['amount'] < $cart['amount']) {
                    // tao don hang nap th?
                    $this->_depositCard($cart['purse'], $card, true);
                    set_message(lang('notice_renew_card_not_enought'));

                } elseif ($card['amount'] >= $cart['amount']) {
                    // tao don hang nap th?
                   // $this->_depositCard($cart['purse'], $card, false);
                    // tao don hang gia han
                    $output = array();
                  //  $this->_mod()->create($cart, $output);
                    mod('service_order')->create($cart, $output);
                    // neu menh gia the lo hon so tien goi, thi nap tien thua do vao so du
                    $amount_balance = $card['amount'] - $cart['amount'];
                    // neu con du tien thi cong vao so du (>1000 thi moi cong)
                    if ($amount_balance > 1000) {
                        $reason = DepositReason::make($output['invoice_order']);
                        AccountantFactory::balance()->add($cart['purse'], $amount_balance, $reason);
                        set_message(lang('notice_renew_card_too_enought',$amount_balance));
                    }
                    else{
                        set_message(lang('notice_renew_success'));

                    }


                }

            }


        } catch (\Exception $e) {
            $result['complete'] = false;
            $result['card'] = $e->getMessage();
        }
    }

    /**
     * Thuc hien nap tien
     *
     * @param PurseModel $purse
     * @param array $card
     * @return array
     */
    protected function _depositCard(PurseModel $purse, array $card, $add_balance = false)
    {
        $fee = mod('card_type')->get_fee($card['type']);

        $amount = $card['amount'] * (100 - $fee) * 0.01;

        $card_discount = $this->_getCardTypeDiscount($card['type']->key, $card['provider']);
        $card_discount = $card_discount ?: $fee;

        $profit = $fee - $card_discount;

        $command = new DepositCardCommand([
            'purse' => $purse,
            'amount' => $amount,
            'fee' => $fee,
            'card' => new CardDeposit([
                'type' => CardTypeModel::find($card['type']->id),
                'code' => $card['code'],
                'serial' => $card['serial'],
                'amount' => $card['amount'],
                'profit' => $profit,
            ]),
            'provider' => $card['provider'],
            'data' => ['data' => $card['data']],
        ]);

        $deposit_card = (new DepositCard($command))->handle($add_balance);

        /*set_message(lang('notice_deposit_success', [
            'card_type' => $card['type']->name,
            'card_amount' => number_format($card['amount']),
            'amount' => $deposit_card->format('amount'),
        ]));*/

        return [
            'type' => $card['type']->name,
            'card_amount' => $card['amount'],
            'amount' => $amount,
            '_card_amount' => number_format($card['amount']),
            '_amount' => $deposit_card->format('amount'),
            'invoice_order_id' => $deposit_card->invoice_order->id,
            'invoice_order_url' => $deposit_card->invoice_order->url('view'),
        ];

        return $deposit_card->invoice_order->url('view');
    }

    /**
     * Lay discount cua card type tung ung voi nha cung cap
     *
     * @param srting $card_type
     * @param string $provider
     * @return float
     */
    protected function _getCardTypeDiscount($card_type, $provider)
    {
        $provider_discounts = lib('payment_card')->{$provider}->get_discount() ?: [];

        return array_get($provider_discounts, $card_type); //l?y chi?t kh?u c?a lo?i th? t? nhà cung c?p
    }

}


