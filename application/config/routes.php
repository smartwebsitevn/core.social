<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Lay config tu file config chinh
$_config =& load_class('Config', 'core');
$_config->load('main', TRUE);
$_af = $_config->item('admin_folder', 'main');

// Module
$route[$_af.'/md-([^/]+)/setting'] 				= $_af.'/module/setting/$1';
$route[$_af.'/md-([^/]+)/([^/]+)/list'] 		= $_af.'/module/table/$1:$2';
$route[$_af.'/md-([^/]+)/([^/]+)/update'] 		= $_af.'/module/table_update/$1:$2';
$route[$_af.'/md-([^/]+)/([^/]+)/add'] 			= $_af.'/module/row_add/$1:$2';
$route[$_af.'/md-([^/]+)/([^/]+)/edit/([^/]+)'] = $_af.'/module/row_edit/$1:$2:$3';
$route[$_af.'/md-([^/]+)/([^/]+)/del/([^/]+)'] 	= $_af.'/module/row_del/$1:$2:$3';
$route[$_af.'/md-([^/]+)/([^/]+)/del'] 			= $_af.'/module/row_del';

$route[$_af.'/md-([^/]+)'] 						= $_af.'/md/$1';
$route[$_af.'/md-([^/]+)/(:any)'] 				= $_af.'/md/$1/$2';
$route[$_af.'/md-([^/]+)/(:any)/(:any)'] 		= $_af.'/md/$1/$2/$3';
/*$route['md-([^/]+)'] 							= 'md/$1';
$route['md-([^/]+)/(:any)'] 					= 'md/$1/$2';
$route['md-([^/]+)/(:any)/(:any)'] 				= '/md/$1/$2/$3';*/
foreach (array('logout') as $_p)
{
	$route[$_af.'/'.$_p] = $_af.'/home/'.$_p;
}

$route[$_af.'/transaction'] = $_af.'/tran';
$route[$_af.'/transaction/(.+)$'] = $_af.'/tran/$1';

// User
foreach (array('login', 'logout', 'register', 'forgot', 'activation') as $_p)
{
	$route[$_p] = 'user/'.$_p;
}
$route['my-account'] 					= 'user_account/index';
$route['my-balance'] 					= 'user_account/balance';
$route['my-page'] 						= 'user_page/index';
$route['user-(:num)'] 					= 'user_page/view/$1';
//$route['(:any)-u(:num)'] 					= 'user_page/view/$1';


//===========================================================================================
// Tran
$route['tran-(:num)'] 					= 'tran/view/$1';
$route['tran-(:num)/(:any)'] 			= 'tran/view/$1/$2';

//Deposit_card_api
$route['nap/(:any)'] 			        = 'deposit_card_api/index/$1';

// Page
$route['page/(:any)-i(:num)'] 			= 'page/index/$2';
$route['(:any)-page(:num)'] 			= 'page/index/$2';
$route['tro-giup'] 			= 'page/index/5';
$route['lien-he'] 			= 'page/index/6';

// News
$route['tin-tuc'] 						= 'news';
$route['tin-tuc/(:any)/i(:num)'] 		= 'news/view/$2';
$route['tin-tuc/tim-kiem'] 				= 'news/search';
$route['tin-tuc/(:any)'] 				= 'news/$1';
$route['(:any)-news(:num)'] 			= 'news/view/$2';

// Faq
$route['faq/(:any)-i(:num)'] 			= 'faq/cat/$2';

// Other
foreach(array('album','download','video') as $key => $row) {
	$route[$row.'/(:any)'] = $row.'/view/$1';
}
$route['(:any)/tag-(:any)-(:num)'] 		= '$1/tag/$3';


// services
$route['danh-sach-dich-vu'] 				= 'service_list';
$route['loai-dich-vu/(:any)-c(:num)']   = 'service_list/category/$2';
$route['dich-vu/(:any)-i(:num)'] 	= 'service/view/$2';

// project
$route['danh-sach-du-an/(:any)-c(:num)']   = 'project_list/category/$2';
$route['danh-sach-du-an'] 				= 'project_list';
$route['du-an'] 	= 'project';
$route['du-an/(:any)-i(:num)'] 	= 'project/view/$2';


// blogs
$route['danh-sach-blog/(:any)-c(:num)']   = 'blog_list/category/$2';
$route['danh-sach-blog/(:any)-a(:num)']   = 'blog_list/author/$2';

$route['danh-sach-blog'] 				= 'blog_list';
$route['blog'] 	= 'blog';
$route['blog/(:any)-i(:num)'] 	= 'blog/view/$2';

// aboutuss
$route['danh-sach-aboutus/(:any)-c(:num)']   = 'aboutus_list/category/$2';
$route['danh-sach-aboutus'] 				= 'aboutus_list';
$route['aboutus'] 	= 'aboutus';
$route['aboutus/(:any)-i(:num)'] 	= 'aboutus/view/$2';

// -=Modified=-
$route['comment'] 						= 'comment';
$route['invoice'] 						= 'invoice';

$route['checkout'] 						= 'checkout';
$route['checkout-confirm'] 				= 'checkout/confirm';
$route['checkout-success'] 				= 'checkout/success';


$route['author/(:any)-(:num)'] = 'author/view/$2';

//combo
$route['(:any)-cb(:num)'] 		         = 'combo/view/$2';
$route['(:any)-cbo(:num)'] 		         = 'combo/order/$2';

//== products
$route['ban-tin/(:any)-(:num)']   = 'product_list/category/$2';
$route['ban-tin'] 				= 'product_list';
$route['xem-ban-tin/(:any)-i(:num)'] 	= 'product/view/$2';
$route['xem-ban-tin/demo/(:any)-d(:num)'] 	= 'product/demo/$2';

$route['my-favorited'] 					    = 'product_list/favorited';
$route['my-posts'] 					    = 'product_list/owner';



// gia han vip
$route['buy-vip'] = 'renew_plan';
$route['voucher'] = 'renew_voucher';

//Affiliate
$route['ref-(:any)'] 		         	= 'affiliate/link/$1';


//============================================
// sitemap
$route['sitemap.xml'] = 'sitemap/index';

// Rountes upload
$_upload = $_config->item('upload', 'main');
$route[$_upload['folder'].'/(:any)'] 	= 'run_file_upload';


$route['default_controller'] = 'home';//'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
