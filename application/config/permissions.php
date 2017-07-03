<?php

$_permissions_speacial = function ($permissions) {
    $permissions['home']['view'] = array();
    //================= System
    $permissions['media']['edit'] = array('directory', 'files', 'image', 'create', 'modify', 'copy', 'folders', 'rename', 'resize', 'upload');
    $permissions['module']['edit'] = array('info', 'setting', 'table', 'table_update', 'row_add', 'row_edit', 'row_del');
    $permissions['setting']['edit'] = array( 'backup');
    $permissions['translate']['edit'] = array('table', 'index', 'quick');
    $permissions['stats']['view'] = array('report');
    $permissions['read_captcha']['edit'] = array('get', 'edit');
//================= Log
    $permissions['log_access']['view'] = array('admin', 'user', 'index');
    $permissions['log_activity']['view'] = array('admin', 'user', 'index');
    $permissions['log_user_balance']['view'] = array('index', 'view');
    $permissions['log_login']['edit'] = array('admin', 'user', 'index');

//================= Tran
    $permissions['service_order']['edit'] = array('edit', 'suspend', 'upgrade_config', 'upgrade_service', 'renew');
    $permissions['tran']['edit'] = array('success', 'canceled', 'fraude', 'transaction_user', 'transaction_admin');
    $permissions['invoice_order']['view']	= array('index','view','pending','canceled','draf');

//================= Account
    $permissions['admin']['edit'] = array('edit', 'matrix_reset');
    $permissions['user']['edit'] = array( 'edit', 'block', 'unblock', 'admin_login', 'verify_view', 'verify_accept', 'verify_cancel',);

//================= Lang
    $permissions['lang_file']['edit'] = array('phrase', 'sync');
    $permissions['lang_phrase']['import'] = array('import');
    $permissions['lang_phrase']['export'] = array('export');

//================= Theme
    $permissions['widget']['edit'] = array('add_select_module', 'add', 'edit');

    return $permissions;
};
//================= Tao cac quyen co ban
$_permissions_content = [



    //== Tran
    'service_order', 'combo',
    'plan', 'voucher',
    'invoice', 'invoice_order',
    'tran', 'tran_banking',
    'withdraw', 'withdraw_admin',
    'deposit_admin',
    'deposit_bank',
    'deposit_card', 'deposit_card_log',
    'order_report',

    //== Content
    'page',
    'aboutus','aboutus_cat',

    'news', 'news_cat',
    'blog', 'blog_cat','blog_author',
    'service', 'service_cat',
    'project', 'project_cat',
    'faq', 'faq_cat',
    'contact',
    'email_register',
    'sayus',

    'slider', 'slider_item',
    'ads_banner', 'ads_location',
    'support',
    'comment',
    'seo_url', 'seo_word',
    'tag',
    //== Lang
    'lang', 'lang_file', 'lang_phrase',
    //== Theme
    'menu', 'menu_item', 'widget',
    //== Sms
    'sms_gateway', 'sms_gateway_log',
    'sms_otp', 'sms_otp_log',
    //== Account
    'admin', 'admin_group',
    'user', 'user_group',
    //'user_bank',
    'user_notice',
    //==system
    'server',
    'payment',
    'payment_card', 'payment_method',
    'currency', 'bank',    'card_type',

    'notice',
    'email', 'emailsmtp', 'emailsend',
    'media', 'cronjob', 'module',
    'cronjob', 'module',
    'send_email', 'setting',
    'translate', 'stats', 'queue', 'read_captcha', 'login',

    'log_access', 'log_activity', 'log_user_balance', 'log_login',
    'log_api', 'log_api_sms_receive',


    //== Product
    'product', 'product_cat', 'manufacture', 'product_order',
    //== Module hotro cho cac module khac
    'cat','range','form_content',
    'country','city','disctric',
    'option','attribute',
    'geo_zone',  'tax_class', 'tax_rate',  'shipping_rate',
    'addon','addon_cat',
];
foreach ($_permissions_content as $p) {
    $permissions[$p]['view'] = array('index', 'view');
    $permissions[$p]['add'] = array('add',);
    $permissions[$p]['edit'] = array('edit', 'setting', 'feature', 'feature_del', 'on', 'off', 'show', 'hide', 'set_default', 'test',
        'active', 'complete', 'cancel', 'refund', 'verify', 'unverify','order');
    $permissions[$p]['delete'] = array('del', 'install', 'uninstall',);
}
$permissions =$_permissions_speacial($permissions);
