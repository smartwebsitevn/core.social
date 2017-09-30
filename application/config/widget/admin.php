<?php

$config = [

    'menu' => [
        'home' => ['home'],
        'product' => array(
            'product', 'product_cat',
            //   'manufacture',
            //'product_to_subscribe','product_to_report',  'product_request',

            'product_setting',
        ),
        'attribute' => array(
            'cat',
            'range',
            //'form_content',
            //'-',
            //'addon',
            //'option',
            //'attribute',//    'attribute_group',
            'type_item', //'type', 'type_cat',
            '-',
            'country',
            'city',
            'geo_zone',
            '-',
            /*
            'tax_class',
            'tax_rate',
           '-',

           'shipping_rate',
           'payment_method',*/

        ),
        'sales' => [
            /*'service_order',
            'plan',
            'combo',*/
            'invoice_order',
            //'tran',
            'voucher',

            'deposit_admin', 'withdraw_admin',/* 'order_report'*/
        ],
        'content' => [
            'page',
            'aboutus',//'aboutus_cat',
            'news',// 'news_cat',
            'blog',//'blog_cat','blog_author',
            'service',//'service_cat',
            'project',//'project_cat',
            'faq', //   'faq_cat',
            '-',
            'slider_item',//'slider',
            'ads_banner',// 'ads_location',
            '-',
            //'album',  'download',  'video',  'tracking',
            'message',
            'contact',
            'email_register',
            'comment',
            //'question_answer',
            'sayus',

            //'seo_url',/* 'seo_word',*/
            'tag',
        ],
        'theme' => ['menu_item', 'widget'],

        //'payment_card' => ['deposit_card', 'deposit_card_log', 'card_type', 'payment_card'],
        //'sms_otp'      => ['sms_otp', 'sms_otp_log'],
        //'sms_gateway'  => ['sms_gateway', 'sms_gateway_log'],
        'account' => ['admin', 'admin_group', 'user', 'user_group', 'user_notice',/* 'user_bank'*/],
        /* 'lang' => ['lang',
             'lang_phrase',
             'lang_file',],*/
        'module' => [],
        'system' => [
            //'server',
            //'payment',
            //'currency',
            //'lang_phrase',
            //'bank',
            'cronjob',
            'notice',
            'email', 'emailsmtp', 'emailsend'/* 'send_email','queue'*/, 'media', /*'module',*/
            'setting',
        ],
        //'log' => ['log_balance', 'log_activity', 'log_login', 'log_access', /*'log_system',*/ /*'log_api','log_api_sms_receive.php' */],
        /*'card_api'		=> array('card', 'card_api'),
        'payment_card'	=> array('card_type', 'payment_card', 'deposit_card_log'),*/
        //'sms'			=> array('log_api_sms_receive'),
    ],
    'menu_tab' => [
        'attribute' => [
            'type', 'type_cat',
        ],
        'content' => [
            'aboutus_cat',
            'news_cat',
            'blog_cat', 'blog_author',
            'service_cat',
            'project_cat',
            'faq_cat',
            'slider',
            'ads_location',
        ],
    ],
    'menu_url' => [
        'product' => [
            'product_setting' => admin_url('module/setting/product'),
        ],
//        'tran' => [
//            'tran' => admin_url('transaction'),
//        ],
    ],

    'menu_icon_group' => [
        'card_api' => 'ticket',
        'payment_card' => 'money',
        'sms' => 'shopping-cart',
        'tran' => 'retweet',
        'account' => 'user',
        'content' => 'book',
        'module' => 'cogs',
        'theme' => 'building',
        'lang' => 'table',
        'system' => 'cogs',
        'log' => 'file-text-o',
    ],

    'menu_lang' => [],

];