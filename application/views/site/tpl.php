<?php
/*$_regions =[
	'header'  => 'Đầu trang',
	'small'   => 'Bên phải',
	'content' => 'Nội dung chính',
	'footer'  => 'Cuối trang',
	'mobile_header'  => 'Mobile Đầu trang',
	'mobile_top'  => 'Mobile Bên trên',
	'mobile_bottom'  => 'Mobile Bên dưới',
	'mobile_footer'  => 'Mobile Đuôi trang',
];*/

$_regions = [
    'header' => 'Đầu trang',
    //'mainmenu' => 'Menu chính',
    'slide_home' => 'Slide trang chủ',
    'content_top' => 'Trên nội dung chính',
    'content' => 'Nội dung chính',
    'content_bottom' => 'Dưới nội dung chính',
    //'left' => 'Cột Trái',
    'right' => 'Cột Phải',
    //'tab' => 'Tab khóa học',
    //'product' => 'Khóa học',
    'footer' => 'Chân trang',
    /*'banner_run_left'  => 'Quảng cáo chạy trái',
    'banner_run_right'  => 'Quảng cáo chạy phải',
    'banner_popup'  => 'Popup',*/
];
$_layouts = [
    'home' => 'Home',
    'main' => 'Main',
    'page' => 'Page',
    'user' => 'User',

    'author' => 'Author',
    'checkout' => 'Checkout',

    // Content
    'news' => 'News',
    'project' => 'Project',
    'service' => 'Service',
    'blog' => 'Blog',
    'blog_list' => 'Blog list',
    'contact' => 'Contact',
    'question_answer' => 'Question answer',

    // Product
    'product_all' => 'Product All',
    'product_list' => 'Product List',
    'product_info' => 'Product Info',
    'product_user' => 'Product User',
    'product_demo' => 'Product demo',

];
$tpl['regions'] = $_regions;
foreach ($_layouts as $l => $n) {
    $tpl['layouts'][$l]['name'] = $n;
    $tpl['layouts'][$l]['regions'] = array_keys($_regions);
}

// Layout mac dinh
$tpl['layout'] = 'main';
$tpl['layout_mod'] = [
    'home' => 'home',
    'page' => 'page',
    'tran' => 'user',
    'deposit' => 'user',
    'deposit_bank' => 'user',
    'deposit_card' => 'user',
    'deposit_card_log' => 'user',
    'deposit_sms' => 'user',
    'withdraw' => 'user',
    //'transfer' => 'user',
    'renew_plan' => 'user',
    'renew_voucher' => 'user',
    'invoice' => 'user',
    'invoice_order' => 'user',
    'log_balance' => 'user',
    'news' => 'news',

];

// User
$tpl['layout_mod']['user']["*"] = 'user';
$tpl['layout_mod']['user']["login"] = 'main';
$tpl['layout_mod']['user']["register"] = 'main';
$tpl['layout_mod']['user']["forgot"] = 'main';
$tpl['layout_mod']['user_security']= 'user';
$tpl['layout_mod']['user_bank']= 'user';


//affiliate
$tpl['layout_mod']['affiliate']["index"]= 'user';

$tpl['layout_mod']['product']["*"] = 'product_info';
$tpl['layout_mod']['product']['demo'] = 'product_demo';

$tpl['layout_mod']['product_list']['*'] = 'product_list';
/*$tpl['layout_mod']['product_list']['index'] = 'product_list';
$tpl['layout_mod']['product_list']['category'] = 'product_list';
$tpl['layout_mod']['product_list']['tag'] = 'product_list';*/
$tpl['layout_mod']['product_list']['favorited'] = 'product_user';




//== author
$tpl['layout_mod']['author']['*'] = 'author';

//== question_answer
$tpl['layout_mod']['question_answer']['*'] = 'question_answer';

//== checkout
$tpl['layout_mod']['checkout']['*'] = 'checkout';

//== project
$tpl['layout_mod']['project_list']['*'] = 'project';

//== service
$tpl['layout_mod']['service_list']['*'] = 'service';
$tpl['layout_mod']['service']['*'] = 'service';

//== blog
$tpl['layout_mod']['blog_list']['*'] = 'blog_list';
$tpl['layout_mod']['blog']['*'] = 'blog';

//== blog
$tpl['layout_mod']['contact']['*'] = 'contact';
//== treo phim o trang web khac
//$tpl['layout_mod']['movie_list']['widget'] = 'widget';

return $tpl;



