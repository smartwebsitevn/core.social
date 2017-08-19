<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Lay thong tin cua thanh vien
 */
function user_get_info($user_id, $field = '')
{
    $CI =& get_instance();
    $CI->load->model('user_model');

    $info = $CI->user_model->get_info($user_id, $field);
    if (!$info) {
        return FALSE;
    }

    $info = user_add_info($info);

    return $info;
}

/**
 * Them thong tin ngoai vao thong tin cua user
 */
function user_add_info($user)
{
    if (!$user) {
        return FALSE;
    }

    $CI =& get_instance();

    if (isset($user->created)) {

        $p = 'created';
        $user->{'_' . $p} = ($user->$p) ? format_date($user->$p) : '';
        $user->{'_' . $p . '_time'} = ($user->$p) ? format_date($user->$p, 'time') : '';
        $user->{'_' . $p . '_full'} = ($user->$p) ? format_date($user->$p, 'full') : '';

    }
    if (isset($user->last_login)) {
        $user->_last_login = 'Never';
        if ($user->last_login)
            $user->_last_login = get_date($user->last_login, 'full');
    }
    if (isset($user->verify)) {
        $vs = config('user_verifies', 'main');
        $user->_verify = (isset($vs[$user->verify])) ? $vs[$user->verify] : '';
    }
    if (isset($user->blocked)) {
        $vs = config('verify', 'main');
        $user->_blocked = (isset($vs[$user->blocked])) ? $vs[$user->blocked] : '';
    }
   // if (isset($user->avatar))
    //{
    //$CI->load->helper('file');
    $avatar_name = $user->avatar;// (isset($user->avatar_name)) ? $user->avatar_name : '';
    $avatar_default= public_url('img/user_no_image.png');
    if(!$avatar_name &&  $user->avatar_api)
        $avatar_default=$user->avatar_api;
    $user->avatar = file_get_image_from_name($avatar_name,$avatar_default);
    //	<img src="https://graph.facebook.com/<?php echo $member['fb_id']/picture?width=21&height=21&type=normal"

    //}
    if (isset($user->user_group_id)) {
        $group = model('user_group')->get_info($user->user_group_id, 'name,type');
        $user->user_group_name =$user->user_group_type = '';
        if ($group){
            $user->user_group_name = $group->name;
            $user->user_group_type = $group->type;
        }

    }
    if (isset($user->desc) && !empty($user->desc)) {
        $user->desc = handle_content($user->desc, 'output');
    }


    return $user;
}

function user_add_info_other($user)
{
    // thong tin khac
    if (isset($user->country) && $user->country) {
        $country = model('country')->get($user->country);
        if ($country)
            $user->_country = $country;
    }
    if (isset($user->city) && $user->city) {
        $city = model('city')->get_info($user->city);
        if ($city)
            $user->_city = $city;
    }

    $user->_gender = $user->gender ? lang("gender_" . $user->gender) : "-";

    //= Lay hinh anh
    /*if (!isset($user->avatar) || !is_object($user->avatar)) {
        $user->avatar = file_get_image_from_name($user->avatar, public_url('img/user_no_image.png'));
    }*/
    return $user;
}

/**
 * Lay avatar cua thanh vien
 */
function user_get_avatar($avatar_id)
{
    $CI =& get_instance();
    $CI->load->helper('file');

    $file = file_get_info($avatar_id, 'file_name');
    $file_name = (!isset($file->file_name)) ? '' : $file->file_name;

    return file_get_image_from_name($file->file_name, public_url('img/user_no_image.png'));
}

/**
 * Kiem tra co the thuc hien 1 hanh dong voi user
 */
function user_can_do($user, $action)
{
    if (!$user) return false;
    $CI =& get_instance();

    switch ($action) {
        case 'edit':
        case 'admin_login': {
            return TRUE;
        }
        case 'block': {
            return ($user->blocked == config('verify_no', 'main'));
        }
        case 'unblock': {
            return ($user->blocked == config('verify_yes', 'main'));
        }
        case 'verify': {
            return ($user->verify == config('user_verify_no', 'main'));
        }
        case 'verify_view':
        case 'verify_cancel': {
            return ($user->verify != config('user_verify_no', 'main'));
        }
        case 'verify_edit':
        case 'verify_accept': {
            return ($user->verify == config('user_verify_wait', 'main'));
        }
        case 'del': {
            return ($user->blocked == config('verify_yes', 'main'));
        }
    }

    return FALSE;
}


/*
 * ------------------------------------------------------
 *  User login handle
 * ------------------------------------------------------
 */
/**
 * Kiem tra thong tin dang nhap
 * @param string $email Email
 * @param string $password Mat khau (Da duoc ma hoa)
 * @return
 *    Error:
 *        $r = array();
 *        $r['status'] = FALSE;
 *        $r['result']['error'] = 'error';
 *            Cac gia tri cua error:
 *            'input'                = Du lieu dau vao khong hop le
 *            'ip_blocked'            = IP bi block
 *            'email'                = Sai email
 *            'password'                = Sai password
 *            'ip_blocked_login_fail' = IP bi block do dang nhap sai qua so lan quy dinh
 *            'blocked'                = Tai khoan bi block
 *    Completed:
 *        $r = array();
 *        $r['status'] = TRUE;
 *        $r['result']['user'] = (object)$user (Thong tin user tuong ung);
 */
function user_login($email, $password)
{
    // Tai cac file thanh phan
    $CI =& get_instance();
    $CI->load->helper('email');
    $CI->load->model('user_model');
    $CI->load->model('ip_model');
    $CI->load->model('ip_block_model');

    // Xu ly input
    $email = (string)$email;
    $password = (string)$password;

    // Neu input khong hop le
    if (!$email || !$password || !valid_email($email)) {
        $r = array();
        $r['status'] = FALSE;
        $r['result']['error'] = 'input';

        return $r;
    }

    // Neu IP bi block
    $ip = $CI->input->ip_address();
    if ($CI->ip_block_model->check($ip)) {
        $r = array();
        $r['status'] = FALSE;
        $r['result']['error'] = 'ip_blocked';

        return $r;
    }

    // Neu khong ton tai user tuong ung
    $where = array();
    $where['email'] = $email;
    $user = $CI->user_model->get_info_rule($where);
    if (!$user) {
        $r = array();
        $r['status'] = FALSE;
        $r['result']['error'] = 'email';

        return $r;
    }

    // Neu sai mat khau
    if ($user->password != $password) {
        $r = array();
        $r['status'] = FALSE;
        $r['result']['error'] = 'password';
        // Kiem tra so lan dang nhap sai
        if (!user_login_check_fail_count()) {
            $r['result']['error'] = 'ip_blocked_login_fail';
        }

        return $r;
    }

    // Neu user bi khoa
    if ($user->blocked == config('verify_yes', 'main')) {
        $r = array();
        $r['status'] = FALSE;
        $r['result']['error'] = 'blocked';

        return $r;
    }

    // Reset so lan dang nhap sai cua IP
    $login_fail_count = $CI->ip_model->action_count_get('user_login_fail');
    if ($login_fail_count) {
        $CI->ip_model->action_count_set('user_login_fail', 0);
    }

    // Dang nhap thanh cong
    $r = array();
    $r['status'] = TRUE;
    $r['result']['user'] = (object)(array)$user;

    return $r;
}

/**
 * Kiem tra so lan dang nhap sai
 */
function user_login_check_fail_count()
{
    // Tai cac file thanh phan
    $CI =& get_instance();
    $CI->load->model('ip_model');
    $CI->load->model('ip_block_model');

    // Neu khong can kiem tra
    $login_fail_count_max = mod('user')->setting('login_fail_count_max');

    if ($login_fail_count_max == 0) {
        return TRUE;
    }

    // Cap nhat so lan dang nhap sai cua IP
    $login_fail_count = $CI->ip_model->action_count_get('user_login_fail');
    $login_fail_count += 1;
    $CI->ip_model->action_count_set('user_login_fail', $login_fail_count);

    // Neu so lan dang nhap sai lon hon quy dinh
    if ($login_fail_count >= $login_fail_count_max) {
        // Block ip
        $ip = $CI->input->ip_address();
        $CI->ip_block_model->set($ip, mod('user')->setting('login_fail_block_timeout'));

        return FALSE;
    }

    return TRUE;
}

/**
 * Gan trang thai dang nhap
 */
function user_login_set($user_id)
{
    // Tai cac file thanh phan
    $CI =& get_instance();
    $CI->load->model('user_model');

    // Luu IP
    $ip = $CI->input->ip_address();
    $data = array();
    $data['ip'] = $ip;
    $data['last_ip'] = $ip;
    $data['last_login'] = now();
    $CI->user_model->update($user_id, $data);


    // Tao token bao mat phien lam viec
    $user_token = md5($user_id . $ip . config('encryption_key'));
    // Set session
    $CI->session->set_userdata('__user_id', $user_id);
    $CI->session->set_userdata('__user_token', $user_token);


    // Luu log
    $log_info = array();
    //$log_info ['detail']=t('html')->a(admin_url('user').'?id='.$user->id,$user->name) .' '.lang('login');
    $log_info ['detail'] = lang('login');
    mod('log')->log('user', $user_id, 'login', $log_info, true);

}

/**
 * Luu cookie ghi nho dang nhap
 * @param string $email Email
 * @param string $password Mat khau (Da duoc ma hoa)
 */
function user_login_set_cookie($email, $password, $expire = null)
{
    $CI =& get_instance();

    $cookie = array();
    $cookie['email'] = $email;
    $cookie['password'] = $password;
    //$cookie['ip'] 		= $CI->input->ip_address();

    $cookie = json_encode($cookie);
    $cookie = security_encrypt($cookie, 'encode');

    $expire = is_null($expire) ? config('cookie_expire_login', 'main') : $expire;

    set_cookie('user', $cookie, $expire);
}

/**
 * Kiem tra thong tin dang nhap trong cookie
 */
function user_login_check_cookie()
{
    $CI =& get_instance();

    // Lay cookie
    $cookie = get_cookie('user', TRUE);
    $cookie = security_encrypt($cookie, 'decode');
    $cookie = @json_decode($cookie);
    if (
        !isset($cookie->email)
        || !isset($cookie->password)
        //|| ! isset($cookie->ip)
    ) {
        return FALSE;
    }

    // Kiem tra IP
    /* if ($cookie->ip != $CI->input->ip_address())
    {
        // Reset cookie
        user_logout();

        return FALSE;
    } */

    // Kiem tra email, password
    $login = user_login($cookie->email, $cookie->password);
    if (!$login['status']) {
        // Reset cookie
        user_logout();

        return FALSE;
    }

    // Gan trang thai dang nhap
    user_login_set($login['result']['user']->id);

    return TRUE;
}

/**
 * Kiem tra user da dang nhap hay chua
 */
function user_is_login(&$user_id_return = 0)
{
    $CI =& get_instance();
    $ip = $CI->input->ip_address();

    // kiem tra id
    $user_id = $CI->session->userdata('__user_id');
    $user_id = (!is_numeric($user_id)) ? 0 : $user_id;
    if ($user_id <= 0)
        return FALSE;
    // kiem tra token
    $user_token = $CI->session->userdata('__user_token');
    $user_token_check = md5($user_id . $ip . config('encryption_key'));
    if ($user_token != $user_token_check)
        return false;
    // tra ve ket qua
    $user_id_return = $user_id;
    return TRUE;


    $CI =& get_instance();

    $user_id = $CI->session->userdata('user_id');
    $user_id = (!is_numeric($user_id)) ? 0 : $user_id;

    return ($user_id > 0) ? TRUE : FALSE;
}

/**
 * Lay thong tin cua user hien tai
 *
 * @return false|object
 */
function user_get_account_info()
{
    // Neu chua login
    $user_id = 0;
    if (!user_is_login($user_id)) {
        return false;
    }

    // Bien tinh luu thong tin user hien tai
    static $user;

    // Neu thong tin chua duoc get hoac user_id khong phu hop
    if (!isset($user->id) || $user->id != $user_id) {
        // Lay thong tin user
        $user = model('user')->get_info($user_id);
        // Neu khong ton tai user
        if (!$user) {
            // Logout tai khoan hien tai
            user_logout();

            return false;
        }

//			$user->balance = model('user')->balance_get($user->id);
//			$user->_balance = currency_format_amount_default($user->balance);
//			$user->permissions = model('user_group')->get_permissions($user->user_group_id);
    }

    return $user ? (object)(array)$user : false;
}

/**
 * User dang xuat
 */
function user_logout()
{
    $CI =& get_instance();

    // Xoa session
    if ($CI->session->userdata('__user_id') !== FALSE) {
        $CI->session->unset_userdata('__user_id');
    }

    // Xoa cookie
    if (get_cookie('user') !== FALSE) {
        delete_cookie('user');
    }
}


/*
 * ------------------------------------------------------
 *  user permission handle
 * ------------------------------------------------------
 */
/**
 * Kiem tra quyen truy cap cua user
 * @param string $c Ten controller
 * @param string $uri Uri truy cap cua controller
 */
function user_permission($c, $uri)
{
    return true;
    //echo '<br>-check per : c='.$c.' - uri='.$uri;
    $user = user_get_account_info();

    $CI =& get_instance();

    // Neu la controller mac dinh
    $c_main = array('home', 'login', 'file', 'md');
    if (in_array($c, $c_main)) {
        return TRUE;
    }

    // Lay config
    $config = user_permission_list();
    // Lay permissions cua user
    $permissions = $user->permissions;
    // Neu duoc phep truy cap controller nay
    if (isset($permissions[$c]) && is_array($permissions[$c])) {
        // Duyet qua cac permission
        foreach ($permissions[$c] as $p) {
            // Lay uri cua permission
            $p_uri = (isset($config[$c][$p]['uri'])) ? $config[$c][$p]['uri'] : FALSE;
            if (!is_array($p_uri)) {
                continue;
            }

            // Kiem tra uri hien tai
            $uri = trim($uri, '/') . '/';
            foreach ($p_uri as $_uri) {
                $_uri = trim($_uri, '/') . '/';
                if (preg_match('#^' . preg_quote($_uri) . '#i', $uri)) {
                    return TRUE;
                }
            }
        }
    }

    return FALSE;
}

/**
 * Kiem tra user co quyen truy cap url hay khong
 * @param string $url URL can kiem tra
 */
function user_permission_url($url)
{

    $CI =& get_instance();

    // Lay uri
    $uri = url_get_uri($url);
    $uri = mod('seo_url')->get_route_base($uri);
    $uri = explode('/', $uri);
    //pr($CI->router->default_controller);
    // Lay controller
    //$c = (isset($uri[1]) && $uri[1] != '') ? $uri[1] : $CI->router->routes['default_controller'];
    $c = (isset($uri[0]) && $uri[0] != '') ? $uri[0] : $CI->router->default_controller;

    // Lay uri cua controller
    unset($uri[0]);


    $u = (!count($uri)) ? array('index') : $uri;
    $u = implode('/', $u);
    return user_permission($c, $u);
}

/**
 * Lay danh sach permission trong config
 */
function user_permission_list()
{
    static $_list = NULL;

    if ($_list === NULL) {
        // Load config
        include APPPATH . 'config/permissions' . EXT;
        $permissions = (isset($permissions)) ? $permissions : array();

        // Lay danh sach permission
        $_list = array();
        foreach ($permissions as $c => $ps) {
            foreach ($ps as $p => $p_i) {
                $_list[$c][$p]['name'] = (isset($p_i['name'])) ? $p_i['name'] : '';
                $_list[$c][$p]['uri'] = (isset($p_i['uri'])) ? $p_i['uri'] : $p_i;
            }
        }

        // Lay permission cua cac module da cai dat
        $CI =& get_instance();
        $CI->load->model('module_model');

        $input = array();
        $input['select'] = 'key';
        $modules = $CI->module_model->get_list($input);
        foreach ($modules as $module) {
            $c = 'md-' . $module->key;
            $ps = $CI->module->{$module->key}->config->item('permissions');
            foreach ($ps as $p => $p_i) {
                $_list[$c][$p]['name'] = (isset($p_i['name'])) ? $p_i['name'] : '';
                $_list[$c][$p]['uri'] = (isset($p_i['uri'])) ? $p_i['uri'] : $p_i;
            }
        }
    }

    return $_list;
}


/**
 * Hi?n th? downline tree dang table
 * @param array $menuData : M?ng d? li?u
 * @param int $parentId : ID cha
 * @param int $depth : T?o ký t? thêm vào class
 */
function downline_tree_level_colors()
{
    return $level_colors =
        [
            1 => "#A801FF",
            2 => "#1A8205",
            3 => "#0052FE",
            4 => "#FEFF1B",
            5 => "#F68207",
            6 => "#FF1500",

        ];
}

function downline_tree_level_texts()
{
    return $level_texts =
        [
            1 => "second",
            2 => "third",
            3 => "forth",

        ];
}

function downline_tree_table($parent, $downline_trees)
{

    $level_texts = downline_tree_level_texts();
    $colors = downline_tree_level_colors();
    ob_start();
    ?>

    <div class="well-sm mt10 mb20">
        <ul class="list-unstyled">
            <?php foreach ($colors as $l => $i): ?>
                <li class="pull-left mr10">
                    <span class="label"
                          style="background:<?php echo $i ?>"><?php echo lang("user_level_" . $l) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <table border="0" style="width:100%; text-align:center;">
        <tbody>

        <tr>
            <td colspan="27" class="net_first">
                <?php /* ?>
                <img src="<?php echo public_url("site/layout") ?>/images/dashboard/iv_2.png" class="verified_icon_id_l1"
                     data-toggle="tooltip" data-placement="right"
                     title="" data-original-title="ID Verified">

                <img src="<?php echo public_url("site/layout") ?>/images/dashboard/pv_1.png" class="verified_icon_ph_l1"
                     data-toggle="tooltip" data-placement="right"
                     title="" data-original-title="Photo Verified">
                 <?php */ ?>

                <a class="seemore" data-id="106064" href="javascript:void(0)">
                    <img class="img-rounded bor_dark" width="120" style="border-width:medium;"
                         src="<?php echo public_url('img/user_no_image.png'); ?>"
                         data-toggle="tooltip" data-placement="top" title=""
                         data-original-title="<?php echo downline_tree_table_info_title($parent->level, $parent) ?>">
                </a>

                <div
                    style="margin-top:0px; margin-bottom:20px; font-weight:bold;">
                    <span class="label"
                          style="background:<?php echo $colors[$parent->level] ?>"> <?php echo $parent->username . ' - ' . lang('user_level_' . $parent->level) ?></span>

                </div>
            </td>
        </tr>
        <?php

        // if (count($downline_trees['items']) > 0) {
        ?>
        <tr>
            <td colspan="27">
                <div class="first_line"></div>
            </td>
        </tr>
        <?php
        $html = [];
        downline_tree_table_process($parent->id, $downline_trees, 1, $parent->level, $level_texts, $html);
        $html = array_reverse($html);
        // pr($html);
        foreach ($html as $vs) {
            // echo "<br>K1=".$k.' count='.count($vs);
            echo '<tr>';
            // foreach($vs as  $v) {
            echo $vs["content"];
            // }
            echo '</tr>';
            echo '<tr class="hidden-xs">';
            for ($i = 1; $i <= $vs["count"]; $i++) {
                echo '<td colspan="' . (27 / $vs["count"]) . '">
                            <div class="first_line"></div>
						  </td>';
            }
            echo '</tr>';
        }
        // }
        ?>

        </tbody>
    </table>
    <?php
    return ob_get_clean();
}

function downline_tree_table_process($parentId, $menuData, $level_f = 1, $level, $level_text, &$html)
{
    // echo '<br>level=' . $level .'level-f=' . $level_f ;
    if ($level < 6 && $level < $level_f || $level_f > 3) {
        return '';
    }

    $d = pow(3, $level_f);
    $retValC = '';
    $j = 1;
    $net = isset($level_text[$level_f]) ? $level_text[$level_f] : 'second';
    $col_span = 27 / $d;
    $level_g = 0;
    if (isset($menuData['parents'][$parentId])) {
        foreach ($menuData['parents'][$parentId] as $itemId) {
            $node = $menuData['items'][$itemId];
            $retValC .= '<td  colspan="' . $col_span . '" class="net_' . $net . '">';
            $retValC .= downline_tree_table_info($level_f, $node, $j);
            $retValC .= downline_tree_table_process($itemId, $menuData, $level_f + 1, $level, $level_text, $html);
            $retValC .= '</td>';
            $j++;
            $level_g++;
        }
    }

    // echo '<br>$j=' . $j;
    if ($j <= 3) {
        //echo '<br>k=';pr($k,0);
        for ($k = $j; $k <= 3; $k++) {
            // echo '<br>k=';pr($k,0);
            $retValC .= '<td  colspan="' . $col_span . '" class="net_' . $net . '">';
            $retValC .= downline_tree_table_info_empty($level_f, $k);
            $retValC .= downline_tree_table_process(-1, $menuData, $level_f + 1, $level, $level_text, $html);
            $retValC .= '</td>';
            $level_g++;
        }
    }
    if (isset($html[$level_f]["count"])) {
        $html[$level_f]["count"] += $level_g;

        $html[$level_f]["content"] .= $retValC;

    } else {
        $html[$level_f]["count"] = $level_g;
        $html[$level_f]["content"] = $retValC;
    }
}

function downline_tree_table_info($level_f, $node, $d)
{
    $title = downline_tree_table_info_title($level_f, $node);
    $colors = downline_tree_level_colors();
    ob_start(); ?>

    <div class="verticle_line <?php echo $d == 2 ? "ver_mid" : '' ?>"></div>
    <?php if ($level_f <= 2): ?>
    <a data-width="60%" data-height="90%" class="blocked_<?php echo $node['locked'] ?>"
        >
        <img src="<?php echo public_url("site/layout") ?>/images/dashboard/iv_2.png"
             class="verified_icon_id_l<?php echo $level_f + 1 ?>" data-toggle="tooltip" data-placement="right"
             title="" data-original-title="Move Branch">
    </a>
    <?php /* ?>
    <img src="<?php echo public_url("site/layout") ?>/images/dashboard/pv_1.png"
         class="verified_icon_ph_l<?php echo $level_f + 1 ?>" data-toggle="tooltip" data-placement="right"
         title="" data-original-title="Photo Verified">
         <?php */ ?>

<?php endif; ?>

    <?php
    $url = site_url('user/downline/' . $node['username']);

    ?>

    <a class="seemore" data-id="106064" href="<?php echo $url ?>">
        <img class="img-rounded bor_dark" src="<?php echo public_url("site/layout") ?>/images/dashboard/no_img.jpg"
             data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $title ?>">
    </a>
    <?php if ($level_f == 1): ?>
    <div style="margin-top:5px; margin-bottom:20px; font-weight:bold;">
        <span class="label"
              style="background:<?php echo $colors[$level_f] ?>"> <?php echo $node['username'] //. '-' . $d ?></span>

    </div>
<?php endif; ?>
    <?php if ($level_f == 2): ?>
    <br>
    <br>
<?php endif; ?>
    <?php
    return ob_get_clean();

}

function downline_tree_table_info_empty($level_f, $d)
{
    ob_start(); ?>
    <div class="verticle_line <?php echo $d == 2 ? "ver_mid" : '' ?>"></div>
    <a href="javascript:void(0)">
        <img src="<?php echo public_url("site/layout") ?>/images/dashboard/empty.png" title=""
             class="img-rounded bor_dark">
    </a>
    <?php if ($level_f == 1): ?>
    <div style="margin-top:5px; margin-bottom:20px; font-weight:bold;">...</div>
<?php endif; ?>
    <?php if ($level_f == 2): ?>
    <br>
    <br>
<?php endif; ?>

    <?php
    return ob_get_clean();
}


function downline_tree_table_info_title($level_f, $node)
{
    if (is_object($node)) $node = (array)$node;
    $title = 'T' . $level_f . ' - ' . $node['username'] . ' - ' . lang('user_level_' . $node['level']);
    return $title;
}


/* Downline tree dang UL*/
function downline_tree($parentId, $menuData, $level_f = 1, $level)
{
    if ($level < 6 && $level < $level_f) {
        return '';
    }
    $retVal = '';
    if (isset($menuData['parents'][$parentId])) {
        $class = '';
        $retVal = '<ul>';

        foreach ($menuData['parents'][$parentId] as $itemId) {
            $node = $menuData['items'][$itemId];

            $retVal .= '<li>';
            $title = 'T' . $level_f . ' - ' . $node['name'] . ' - ' . lang('user_level_' . $node['level']);
            $retVal .= '<a data-toggle="tooltip" title="' . $title . '"  data-width="60%" data-height="90%" class="lightbox blocked_' . $node['locked'] . '" href="' . site_url('user/edit_downline/' . $node['id']) . '">' . $node['username'] . '</a>';

            $retVal .= downline_tree($itemId, $menuData, $level_f + 1, $level);

            $retVal .= '</li>';
        }

        $retVal .= '</ul>';
    }

    return $retVal;
}



/**
 * Lay thong tin cua tai khoan cao nhat cua he thong
 */
function user_get_root()
{
    $CI =& get_instance();
    // Bien tinh luu thong tin user hien tai
    static $user = FALSE;

    // Neu thong tin chua duoc get hoac user_id khong phu hop
    if (!$user) {
        // Lay thong tin user
        $CI->load->model('user_model');
        $user = $CI->user_model->get_info(user_get_id_root());
        if (!$user) {
            return FALSE;
        }
    }
    return $user;
}

// kiem tra xem user hien thoi co phai la user root ko
function user_is_root($user)
{
    return (isset($user->id) && $user->id == user_get_id_root());
}

// kiem tra xem user hien thoi co phai la user root ko
function user_get_id_root()
{
    return 1;//config("user_root_id");
}

//== User la manager
function user_is_manager($user)
{
    return (isset($user->user_group_id) && $user->user_group_id == user_get_id_group_manager());
}

// kiem tra xem user hien thoi co phai la user giang vien ko
function user_get_id_group_manager()
{
    return 4;
}



//== User la active
function user_is_active($user)
{
    return (isset($user->user_group_id) && $user->user_group_id == user_get_id_group_active());
}

// kiem tra xem user hien thoi co phai la user giang vien ko
function user_get_id_group_active()
{
    return 3;
}
