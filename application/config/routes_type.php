<?php

/*$route['checkout'] 						= 'checkout';
$route['checkout-confirm'] 				= 'checkout/confirm';
$route['checkout-success'] 				= 'checkout/success';*/

// types
$route['my-types'] 					    = 'user_type';
$route['danh-sach-type/(:any)-c(:num)']   = 'type_list/category/$2';
$route['danh-sach-type'] 				= 'type_list';
$route['type'] 	= 'type';    
$route['type/(:any)-i(:num)'] 	= 'type/view/$2';

