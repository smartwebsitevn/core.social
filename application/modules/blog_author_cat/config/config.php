<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ------------------------------------------------------
 *  Module Config
 * ------------------------------------------------------
 */

// Ten module
$config['name'] = 'Danh mục blog_author';

// Module co ho tro widget hay khong
$config['widget'] = TRUE;

// Widget co su dung cache hay khong
$config['widget_cache'] = FALSE;

// Thoi gian luu cache cua widget (0: Theo mac dinh)
$config['widget_cache_expire'] = 0;

// Danh sach cac method cua controller site
$config['site_methods'] = array();
// Danh sach loai ho tro hien thi menu
$config['menu_methods'] = array(
    array('title'=>lang('list'),
        'callback'=>admin_url('blog_author_cat/menu_callback')),
);
// Phan quyen
$config['permissions'] = array();
