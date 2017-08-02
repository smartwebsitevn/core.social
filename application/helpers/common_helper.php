<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Lay doi tuong cua cac lop xu ly trong he thong
 */
function t($p = NULL)
{
    static $CI;

    if (is_null($CI)) {
        $CI = get_instance();
    }

    return (is_null($p)) ? $CI : $CI->$p;
}

/**
 * Goi mod
 *
 * @param string $name
 * @return MY_Mod
 */
function mod($name)
{
    return t('mod')->$name;
}

/**
 * Goi model
 *
 * @param string $name
 * @return MY_Model
 */
function model($name)
{
    return t('model')->$name;
}

/**
 * Goi library
 *
 * @param string $name
 */
function lib($name)
{
    return t('lib')->$name;
}

/**
 * Goi widget
 *
 * @param string $name
 * @return MY_Widget
 */
function widget($name)
{
    return t('widget')->$name;
}

/**
 * Load view
 *
 * @param string $view
 * @param array $data
 * @param bool $return
 */
function view($view, array $data = array(), $return = false)
{
    return t('view')->load($view, $data, $return);
}

/**
 * Goi macro voi namespace
 *
 * @param string $namespace
 * @return MY_Macro
 */
function macro($namespace = null)
{
    return t('macro')->callMacroNamespace($namespace);
}

/**
 * Lay khu vuc hien tai cua he thong
 */
function get_area()
{
    static $area;
    if (is_null($area)) {
        $c = t('uri')->segment(1);
        $c = strtolower($c);

        $area = ($c == config('admin_folder', 'main')) ? 'admin' : 'site';
    }

    return $area;
}

/**
 * Lay gia tri cua config
 */
function config($key, $file = 'main', $default = FALSE)
{
    return t('config')->item($key, $file, $default);
}

/**
 * Lay setting theo key
 */
function setting_get($key)
{
    $CI =& get_instance();

    return $CI->setting_model->get($key);
}

/**
 * Lay setting theo group
 */
function setting_get_group($group)
{
    static $_result = array();
    if (!isset($_result[$group])) {
        $CI =& get_instance();
        $_result[$group] = $CI->setting_model->get_group($group);
    }

    return $_result[$group];

    //$CI =& get_instance();
    //return $CI->setting_model->get_group($group);
}

/**
 * Lay setting theo group cua post
 */
function setting_get_group_post($group, $post)
{
    static $_result = array();
    $key = $group . '-' . $post;
    if (!isset($_result[$key])) {
        $CI =& get_instance();
        $_result[$key] = $CI->setting_model->get_group_post($group, $post);
    }

    return $_result[$key];
    //$CI =& get_instance();
    //return $CI->setting_model->get_group_post($group, $post);
}

/**
 * Xu ly du lieu
 */
function handle_content($content, $action)
{
    $base_url = config('base_url', '');
    $upload_url = config('upload', 'main');
    $upload_url = (isset($upload_url['server'])) ? $upload_url['server']['url'] : '';
    $params = array();
    switch ($action) {
        case 'input': {
            $params[url_https($base_url, FALSE)] = '{base_url}';
            $params[url_https($base_url, TRUE)] = '{base_url_https}';

            if ($upload_url) {
                $params[url_https($upload_url, FALSE)] = '{upload_url}';
                $params[url_https($upload_url, TRUE)] = '{upload_url_https}';
            }

            break;
        }

        case 'output': {
            //$params['{base_url}'] 		= url_https($base_url, FALSE);
            //$params['{base_url_https}'] = url_https($base_url, TRUE);
            $params['{base_url}'] = $base_url;
            $params['{base_url_https}'] = $base_url;
            if ($upload_url) {
                $params['{upload_url}'] = url_https($upload_url, FALSE);
                $params['{upload_url_https}'] = url_https($upload_url, TRUE);
            }

            break;
        }

        case 'output_link': {
            $lang_url = t('uri')->langcur;
            if ($lang_url) {
                // neu co tien to lang trong url
                //kiem tra url la file hay link
                $base_url = $base_url . '/' . $lang_url;
                $upload_url = $upload_url . '/' . $lang_url;
            }

            //$params['{base_url}'] 		= url_https($base_url, FALSE);
            //$params['{base_url_https}'] = url_https($base_url, TRUE);
            $params['{base_url}'] = $base_url;
            $params['{base_url_https}'] = $base_url;
            if ($upload_url) {
                $params['{upload_url}'] = url_https($upload_url, FALSE);
                $params['{upload_url_https}'] = url_https($upload_url, TRUE);
            }

            break;
        }
    }

    if (count($params)) {
        $content = strtr($content, $params);
    }

    return $content;
}

/**
 * Gan du lieu tra ve
 */
function set_output($type, $content)
{
    $CI =& get_instance();
    $CI->load->helper('file');

    $mime = get_mime_by_extension('.' . $type);
    header('Content-type: ' . $mime);
    echo $content;
    exit;
}

// --------------------------------------------------------------------
/**
 * Gan message
 * @param mixed $form Dang hien thi text|modal|toast
 * @return null
 */
function set_message_display($form = 'text')
{
    // Kiem tra input
    if (
    !in_array($form, array('text', 'modal', 'toast'))
    ) {
        return FALSE;
    }
    t()->session->set_userdata('message_display', $form);

}

function get_message_display($default = 'text')
{
    $v = t()->session->userdata('message_display');
    t()->session->unset_userdata('message_display');
    if ($v == '')
        $v = $default;
    return $v;

}
function get_message_options()
{
    $v= t()->session->userdata('message_options');
    if($v){
        t()->session->unset_userdata('message_options');
        return $v;
    }
    return null;
}
/**
 * Gan message
 * @param string $message Noi dung message
 * @param string $type Loai message ('success', 'warning', 'error', 'info')
 * @param mixed $repeat Cho phep lap lai message hay khong (Neu khai bao la NULL thi se xoa gia tri cu sau do them vao cuoi danh sach)
 * @return bool
 */
function set_message($message, $type = 'info', $repeat = NULL)
{
    // Kiem tra input
    if (!$message || !in_array($type, array('success', 'warning', 'error', 'info'))) {
        return FALSE;
    }

    // Lay data
    $data = t()->session->userdata('message');
    $data[$type] = (!isset($data[$type])) ? array() : $data[$type];

    // Cap nhat data
    $exists = in_array($message, $data[$type]);
    if (!$exists || $repeat === NULL || $repeat === TRUE) {
        // Neu $repeat = NULL thi xoa gia tri cu
        if ($repeat === NULL && $exists) {
            $i = array_search($message, $data[$type]);
            unset($data[$type][$i]);
            $data[$type] = array_values($data[$type]);
        }

        // Them message va cap nhat session
        $data[$type][] = $message;
        t()->session->set_userdata('message', $data);

        return TRUE;
    }

    return FALSE;
}

// --------------------------------------------------------------------

/**
 * Lay message
 * @param string $type Loai message ('success', 'warning', 'error', 'info'). Neu khong khai bao thi lay message cua tat ca cac loai
 * @param bool $clear_queue Xoa message lay duoc khoi danh sach
 * @return array
 */
function get_message($type = '', $clear_queue = TRUE)
{
    // Lay data
    $data = t()->session->userdata('message');

    // Lay message cua 1 type
    $result = '';
    if ($type != '') {
        if (isset($data[$type])) {
            $result = $data[$type];

            if ($clear_queue) {
                unset($data[$type]);
                t()->session->set_userdata('message', $data);
            }
        }
    } // Lay message cua tat ca type
    else {
        $result = $data;

        if ($clear_queue) {
            t()->session->unset_userdata('message');
        }
    }

    return (!is_array($result)) ? array() : $result;
}

// --------------------------------------------------------------------

/**
 * Lay error
 */
function get_error($name)
{
    $CI =& get_instance();
    $CI->config->load('error', TRUE);

    $error = array();
    $error['code'] = config($name, 'error');
    $error['name'] = $name;

    return $error;
}

/**
 * Lay thong tin row cua table
 * @param string $table Ten table
 * @param mixed $id Id cua row
 * @param bool $cache Su dung cache hay khong
 */
function db_get_row($table, $id, $cache = TRUE)
{
    static $_result = array();
    if (!isset($_result[$table][$id]) || !$cache) {
        $CI =& get_instance();
        $CI->load->model($table . '_model');

        $_result[$table][$id] = $CI->{$table . '_model'}->get_info($id);
    }

    return $_result[$table][$id];
}

/**
 * Xu ly thong tin page
 *    Lay thong tin: page_info($key)
 *    Gan thong tin: page_info($key, $value)
 * @param string $key Key cua bien (title, description, keywords, robots, breadcrumbs)
 */
function page_info($key)
{
    // Kiem tra key
    if (!in_array($key, array('title', 'description', 'keywords', 'robots', 'breadcrumbs'))) {
        return FALSE;
    }

    // Bien tinh luu thong tin
    static $_page = array();

    // Lay thong tin
    if (func_num_args() == 1) {
        // Neu key ton tai gia tri
        if (isset($_page[$key])) {
            return $_page[$key];
        }

        // Neu la cac bien co trong setting thi gan gia tri mac dinh
        $key_setting = array();
        $key_setting['title'] = 'name';
        $key_setting['description'] = 'meta_desc';
        $key_setting['keywords'] = 'meta_key';

        if (isset($key_setting[$key])) {
            $new_key = $key_setting[$key];

            $lang_cur = lang_get_cur();

            $translate = null;
            if (config('language_multi', 'main') && $lang_cur->id != lang_get_default()->id
            ) {
                $where = array('table' => 'setting', 'table_id' => 'key', 'table_field' => $new_key, 'lang_id' => $lang_cur->id);
                $translate = model('translate')->get_info_rule($where);
            }
            if ($translate)
                $_page[$key] = $translate->value;
            else
                $_page[$key] = setting_get('config-' . $new_key);
            return $_page[$key];
        }

        return FALSE;
    } // Luu thong tin
    elseif (func_num_args() >= 2) {
        $_page[$key] = func_get_arg(1);

        return TRUE;
    }

    return FALSE;
}

/**
 * Lay data
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function get_data($key, $default = null)
{
    return t('mod')->system->get_data($key, $default);
}

/**
 * Lay data
 *
 * @param  string $key
 * @param  mixed $value
 * @return array
 */
function set_data($key, $value)
{
    return t('mod')->system->set_data($key, $value);
}

/*
 * Ghi du lieu vao file log
 */
function write_file_log($file_name, $data)
{
    //return;
    //$date = date('m/d/Y h:i:s a', time());
    if (is_array($data) || is_object($data))
        $data = json_encode($data);
    $log = t('input')->ip_address() . ': ' . get_date(now(), 'full') . ':' . $data;
    file_put_contents($file_name, $log . PHP_EOL, FILE_APPEND);
}

function filter_injection($data)
{
    $data = trim(htmlentities(strip_tags($data)));
    if (get_magic_quotes_gpc())
        $data = stripslashes($data);

    $data = mysql_real_escape_string($data);
    return $data;
}

// hien thi anh
function thumb_img($image)
{
    if(!isset($image->url_thumb) || !isset($image->url) ) return;
    $ext = strtolower(substr(strrchr($image->url_thumb, '.'), 1));
    if ($ext === 'gif') {
        $path = $image->url;
    } else {
        $path = $image->url_thumb;
    }
    return $path;
}

// lay thuoc tinh
function obj_true_name($obj,$names=[])
{
    foreach($names as $v){
        if(isset($obj->$v))
            return $v;
    }
    return null;
}