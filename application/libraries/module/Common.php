<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Module Common Functions
 *
 * Cac function chinh cua module
 *
 * @author        ***
 * @version        2013-12-31
 */
/**
 * Lay doi tuong cua module
 *
 * @param string $name
 */
function module($name)
{
    return t('module')->$name;
}

/**
 * Kiem tra module co ton tai hay khong
 * @param string $module Ten module
 * @param bool $cache Su dung cache hay khong
 */
function module_exists($module, $cache = TRUE)
{
    if (!$module) return FALSE;

    static $_result = array();
    if (!isset($_result[$module]) || !$cache) {
        $file = APPPATH . 'modules/' . $module . '/module' . EXT;
        $_result[$module] = (file_exists($file)) ? TRUE : FALSE;
    }

    return $_result[$module];
}

/**
 * Kiem tra module da duoc cai dat hay chua
 * @param string $module Ten module
 * @param bool $cache Su dung cache hay khong
 */
function module_install($module, $cache = TRUE)
{
    if (!$module) return FALSE;

    static $_result = array();
    if (!isset($_result[$module]) || !$cache) {
        $CI =& get_instance();
        $CI->load->model('module_model');

        $info = $CI->module_model->get_info($module, 'key');
        $_result[$module] = ($info) ? TRUE : FALSE;
    }

    return $_result[$module];
}

/**
 * Kiem tra module co dang hoat dong hay khong
 * @param string $module Ten module
 * @param bool $cache Su dung cache hay khong
 */
function module_active($module, $cache = TRUE)
{
    if (!$module) return FALSE;

    static $_result = array();
    if (!isset($_result[$module]) || !$cache) {
        $CI =& get_instance();
        $CI->load->model('module_model');

        $info = $CI->module_model->get_info($module, 'status');
        $_result[$module] = ($info && $info->status == config('status_on', 'main')) ? TRUE : FALSE;
    }

    return $_result[$module];
}

    /**
     * Kiem tra module co the truy cap hay khong
     * @param string $module Ten module
     */
    function module_can_access($module)
    {

        static $can_access = array();
        // Neu khong get duoc thong tin admin hien tai
        if (isset($can_access[$module])) {
            return $can_access[$module];
        }

        // neu module da cai dat thi moi kiem tra (vi ko check voi module ko dc cai dat)
        if (module_install($module)) {
            // neu module da cai ma chua kick hoat
            if (!module_active($module)) {
                $can_access[$module]= false;
                return false;
            }
        }
        $can_access[$module]= true;
        return true;
    }

/**
 * Lay setting cua module
 * @param string $module Ten module
 * @param string $param Bien muon lay gia tri, mac dinh la lay toan bo cac bien
 * @param bool $cache Su dung cache hay khong
 */
function module_get_setting($module, $param = '', $cache = TRUE)
{
    if (!$module) return FALSE;

    // Lay setting cua module
    static $_setting = array();
    if (!isset($_setting[$module]) || !$cache) {
        $CI =& get_instance();
        $CI->load->model('module_model');

        $_setting[$module] = $CI->module_model->get_setting($module);
    }

    // Neu chi lay gia tri cua 1 bien
    if ($param != '') {
        return (isset($_setting[$module][$param])) ? $_setting[$module][$param] : FALSE;
    }

    return $_setting[$module];
}

/**
 * Tao url cho module
 * @param string $module Ten module
 * @param string $controller Ten controller (site || admin)
 * @param string $uri URI
 * @param array $opt Cac thuoc tinh (Xem trong site_url())
 */
function module_url($area, $module, $controller, $uri = '', array $opt = array())
{
    $area = (!in_array($area, array('site', 'admin'))) ? 'site' : $area;
    $uri = 'md-' . $module . '/' . $controller . '/' . $uri;

    return call_user_func_array($area . '_url', array($uri, $opt));
}

/*
      function module_url($module, $controller = 'site', $uri = '', array $opt = array())
{
    $controller = ( ! in_array($controller, array('site', 'admin'))) ? 'site' : $controller;
    $uri = 'md-'.$module.'/'.$uri;

    return call_user_func_array($controller.'_url', array($uri, $opt));
}*/