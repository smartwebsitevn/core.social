<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Oauth extends MY_Controller
{
    /**
     * Ham khoi dong
     */
    public function __construct()
    {
        parent::__construct();
        if ( !mod("user")->setting('login_auth_allow') ) {
            $this->_redirect();
        }
        // Neu da dang nhap roi
        if (user_is_login()) {
            $this->_redirect('user');
        }

        // Tai file thanh phan
        $this->load->library('oauth_library', null, 'oauth');
    }

    function glink()
    {
        lib('glink')->get_code();
    }

    /**
     * Xu ly nha cung cap
     */
    public function _remap($action, $params = array())
    {
        $actions = ['facebook',
            'google'];
        if (in_array($action, $actions)) {
            $this->_common($action);
            return;;
        } elseif (method_exists($this, $action)) {
            return call_user_func_array(array($this, $action), $params);
        } else {
            set_message(lang('notice_page_not_found'));
            redirect();
        }

    }

    function _common($provider)
    {
        $status = false;
        $app = $this->_app_key($provider);
        if (!$app)
            show_404();
        $this->load->library('oauth/oauth2');

        $api = $this->oauth2->provider($provider, array(
            'id' => $app['id'],
            'secret' => $app['secret'],
        ));

        if (!$this->input->get('code')) {
            // By sending no options it'll come back here
            $api->authorize();
        } else {
            try {
                $token = $api->access($this->input->get('code'));
                $member_api = $api->get_user_info($token); //uid, name, email,
                $email = isset($member_api['email']) ? $member_api['email'] : $member_api['uid'];
                $name = $member_api['name'];
                $nickname = $member_api['nickname'];
                $image = isset($member_api['image']) ? $member_api['image'] : '';
                //th?c hi?n login
                $this->_login($email, $name, $nickname, $image);
            } catch (OAuth2_Exception $e) {
                show_error('That didnt work: ' . $e);
            }

            //ket thuc
        }
        redirect();
    }

    /**
     * Xu ly dang nhap vao he thong sau khi login tai nha cung cap thanh cong
     */
    private function _login($email, $name, $nickname, $avatar_api)
    {
        // Xu ly input
        $email = strval($email);

        // Tai cac file thanh phan
        $this->load->model('user_model');

        // Lay user tuong ung voi email
        $where = array();
        $where['email'] = $email;
        $user = model('user')->get_info_rule($where, 'id,,username,avatar,avatar_api');

        // Neu khong ton tai user thi them moi
        if (!$user) {
            // Tao password random
            $password = mod('user')->random_password();
            $pin = mod('user')->random_password();

            if (!$nickname || model('user')->check_exits(['username' => $nickname]))
                $username = mod('user')->random_password();
            else
                $username = $nickname;

            $data = array(
                'email' => $email,
                'password' => $password,
                'pin' => $pin,
                'name' => $name,
                'username' => $username,
                'avatar_api' => $avatar_api,
                'register_api' => '1',
                'activation' => '1',

            );
            $user_id = mod('user')->create($data);

            // Gan trang thai dang nhap
            user_login_set($user_id);

            // Gui email thong bao
            mod('email')->send('user_register_api_completed', $email, array(
                'name' => $name,
                'username' => $username,
                'password' => $password,
                'pin' => $pin,
            ));

            // Gui thong bao
            $this->lang->load('site/user');
            $msg = lang('notice_register_openid');
            $msg = strtr($msg, array('{email}' => $email, '{username}' => $username, '{password}' => $password, '{pin}' => $pin));
            set_message($msg);

            // Chuyen den trang edit thong tin
            redirect('user/edit');
        } // Neu da ton tai
        else {
            // Tu dong dang nhap
            user_login_set($user->id);

            if (!$user->avatar && $avatar_api) {
                model('user')->update($user->id, ['avatar_api' => $avatar_api]);

            }

            // Gui email thong bao
            /*$email_params = array();
            $email_params['ip']   = $this->input->ip_address();
            $email_params['time'] = get_date(now(), 'full');
            mod('email')->send('user_login', $email, $email_params);*/


            // Tao url
            $url = $this->session->userdata('url_return');
            if ($url) {
                $this->session->unset_userdata('url_return');
            } else {
                $url = site_url();
            }
            redirect($url);
        }
    }

    /**
     * L?y thông tin k?t n?i c?a nhà cung c?p
     */
    private function _app_key($provider = '')
    {
        $app = array(
            'facebook' => array(
                'id' => setting_get('config-facebook_oauth_id'),
                'secret' => setting_get('config-facebook_oauth_key'),
            ),
            'google' => array(
                'id' => setting_get('config-google_oauth_id'),
                'secret' => setting_get('config-google_oauth_key'),
            ),
        );

        if (isset($app[$provider])) {
            return $app[$provider];
        }
        return false;
    }

}
