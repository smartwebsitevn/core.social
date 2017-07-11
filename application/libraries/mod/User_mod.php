<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use App\User\UserFactory as UserFactory;

class User_mod extends MY_Mod
{
    protected $setting = array();



    /**
     * Khoi tao doi tuong
     */
    public function __construct()
    {
        t('load')->helper('user');

        if (!$this->setting) {
            $setting = array();

            t('config')->load('mod/user', true, true);
            $setting = array_merge($setting, (array)config('mod/user', ''));
            $this->setting = $setting;
        }

    }

    public function add_info($row)
    {
        $row =  user_add_info($row);
        $row = $this->url($row);
        return  $row;
    }

    /**
     * Kiem tra co the su dung so du
     */
    function can_use_balance($user_id)
    {

        if ($this->setting['balance_block'])
            return false;

        $user = $this->get_info($user_id);
        if (!$user)
            return false;
        if ($this->setting['balance_timeout_from_register']) {
            $timeout = now() - $user->created;
            if ($timeout < ($this->setting['balance_timeout_from_register'] * 60 * 60))
                return false;
        }
        return true;
    }

    /**
     * Lay setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function setting($key = null, $default = null)
    {
        /*$setting = array();

        t('config')->load('mod/user', true, true);
        $setting = array_merge($setting, (array) config('mod/user', ''));*/

        return array_get($this->setting, $key, $default);
    }

    /**
     * Set a config file item
     *
     * @param    string $item Config item key
     * @param    string $value Config item value
     * @return    void
     */
    public function set_setting($item, $value)
    {
        $this->setting[$item] = $value;
    }

    /**
     * Tao url
     *
     * @param object $row
     * @return object
     */
    public function url($row)
    {
        $row = (!is_object($row)) ? new stdClass() : $row;

        $row->_url = site_url('user');

        foreach (array('register', 'login', 'forgot', 'activation', 'logout') as $p) {
            $row->{'_url_' . $p} = site_url($p);
        }

        foreach (array('edit') as $p) {
            $row->{'_url_' . $p} = site_url("user_account/{$p}");
        }

        if (isset($row->id)) {
            $row->_url_view = site_url('user-' . $row->id);
        }

        return $row;
    }

    /**
     * Tao user
     *
     * @param array $input Thong tin
     *    string    'password'    Password chua encode
     *    string    'pin'        Pin chua encode
     * @param array $output
     * @return int
     */
    public function create(array $input, &$output = array())
    {
        $input['password'] = $this->encode_password($input['password'], $input['email']);

        if (array_get($input, 'pin')) {
            $input['pin'] = security_encode($input['pin']);

            if (!array_get($input, 'security_method')) {
                $input['security_method'] = 'pin';
            }
        }

        if (!array_get($input, 'user_group_id')) {
            $input['user_group_id'] = UserFactory::userGroup()->getForUser()->id;
        }

        $input = array_add($input, 'created', now());

        $id = 0;
        $this->_model()->create($input, $id);

        $output = array_merge($input, compact('id'));

        return $id;
    }

    /**
     * Dang ki nhanh
     *
     * @param array $input
     * @param array $output
     * @return int
     */
    public function quick_register(array $input, &$output = array())
    {
        $email = $input['email'];

        $password = $this->random_password();

        $data = array_merge($input, compact('password'));

        $user_id = $this->create($data, $output);

        mod('email')->send('user_account_info', $email, array(
            'user_email' => $email,
            'user_pass' => $password,
        ));

        return $user_id;
    }

    /**
     * Tao password encode
     *
     * @param string $password
     * @param string $email
     * @return string
     */
    public function encode_password($password, $email)
    {
        return security_encode($password, strtolower($email));
    }

    /**
     * Kiem tra co phai la password hien tai cua user hay khong
     *
     * @param string $password
     * @return boolean
     */
    public function is_password_current($password, $user = null)
    {
        if ($user === null) {
            if (!user_is_login()) return false;
            $user = user_get_account_info();
        }

        $password = $this->encode_password($password, $user->email);

        return ($password === $user->password);
    }

    /**
     * Kiem tra co phai la pin hien tai cua user hay khong
     *
     * @param string $pin
     * @return boolean
     */
    public function is_pin_current($pin, $user = null)
    {
        if ($user === null) {
            if (!user_is_login()) return false;
            $user = user_get_account_info();
        }

        $pin = security_encode($pin);

        return ($pin === $user->pin);
    }

    /**
     * Kiem tra username
     *
     * @param string $val
     * @param string $error
     * @return boolean
     */
    public function valid_username($val, &$error = null)
    {
        if (!preg_match("/^([-a-z0-9_-])+$/i", $val)) {
            $error = 'alpha_dash';

            return false;
        }

        if (strlen($val) < $this->_model()->_password_lenght) {
            $error = 'min_length';

            return false;
        }

        if ($this->_model()->has_user($val)) {
            $error = 'exists';

            return false;
        }

        return true;
    }

    /**
     * Tao password random
     *
     * @return string
     */
    public function random_password()
    {
        return random_string('numeric', 6);
    }


    /**
     * Lay vi tien
     * @param UserModel     $user
     * @param CurrencyModel $currency
     */
    public function get_purse($user = '', $currency_id = '')
    {
        if(!$user)
        {
            $user = App\User\UserFactory::auth()->user();
        }
        if(!$currency_id)
        {
            //$currency_id = config('currency_btc_id', 'main');
            $currency_id = currency_get_default()->id;
            $currency = App\Currency\Model\CurrencyModel::findWhere(['id' => $currency_id]);

        }else{
            $currency = App\Currency\Model\CurrencyModel::findWhere(['id' => $currency_id]);

        }

        if (!$currency) {
            throw new Exception("Can't get currency");
        }

        $purse = App\Purse\PurseFactory::purse()->get($user, $currency);

        if (!$purse) {
            throw new Exception("Can't get purse of user");
        }
        //$purse->_balance_decode = currency_format_amount($purse->balance_decode, $currency_id);

        return $purse;
    }

    /**
     * thay doi so du vi
     *string: $status (+, -)
     *string: $status (+, -)
     */
    public function change_purse($status, $amount, $user, $purse)
    {
        $currency_id = $purse->currency_id;
        $data = array(
            'status'         => $status,
            'purse_id'       => $purse->id,
            'purse_amount'   => $amount,
            'reason_key'     => $purse->id,
            'amount'         => currency_convert_amount_default($amount, $currency_id),
            'desc'           => $status.$amount,
            'user_id'        => $user->id,
            'currency_id'    => $currency_id,
            'created'        => now(),
            'ip'             => t('input')->ip_address(),
            'user_agent'     => t('input')->user_agent(),
            'referer       ' => site_url(),
        );
        model('log_balance')->create($data);

        //tr? ti?n t? ví BTC c?a thành viên
        return (new App\Purse\Job\ChangePurseBalance(
            $purse, $amount, $status
        ))->handle();
    }


    public function check_vip($user_id = null)
    {
        if (!$user_id) {
            $user = user_get_account_info();
            if ($user) return false;
            $user_id = $user->id;
        }
        $service_order = model('service_order')->get_info_rule(array('user_id' => $user_id, 'status' => 'active'));
        $is_expired = true;
        if ($service_order)
            $is_expired = $service_order->expire_to < now() ? TRUE : FALSE;

        return $is_expired ? false : true;
    }



}