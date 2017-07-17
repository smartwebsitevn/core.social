<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ------------------------------------------------------
 *  Module Setting Params
 * ------------------------------------------------------
 * 
 * $setting[param] = (array)options;
 * Danh sach cac tuy chon:
 * 	'type'			= Loai bien. VD: text. Cac loai bien duoc ho tro:
 * 						text, textarea, html, bool, select, select_multi, radio, checkbox, date, color, file, file_multi, image, image_multi
 * 	'name'			= Tieu de cua bien
 * 	'value'			= Gia tri mac dinh
 * 	'values'		= Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
 * 						VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
 * 	'file_allowed'	= Cac loai file cho phep, khong khai bao thi lay theo mac dinh. VD: 'jpg|png'
 * 	'file_private'	= TRUE: File private khong co link down || FALSE: File public co link down
 * 	'file_server'	= TRUE: File luu tren server luu tru (Mac dinh) || FALSE: File luu len server hien tai
 * 	'file_thumb'	= Tao thumb cho anh hay khong, TRUE || FALSE
 */

$setting = array(

    'rate_allow' => array(
        'type' => 'bool',
        'name' => 'Cho phép đánh giá tin bài',
    ),
    'comment_fb_allow' => array(
        'type' => 'bool',
        'name' => 'Cho phép bình luận bằng Facebook',
    ),
    'comment_allow' => array(
        'type' => 'bool',
        'name' => 'Cho phép thành viên bình luận bằng tài khoản',
    ),
    'comment_auto_verify' => array(
        'type' => 'bool',
        'name' => 'Tự động xác thực bình luận ',
        'desc' => 'Nếu set là có, bình luận sẽ được hiển thị ngày mà không cần xét duyệt',
    ),
    'turn_off_function_order' => array(
        'type' => 'bool',
        'name' => 'Tắt chức năng mua hàng',
        'desc' => 'Nếu set là có, hệ thống sẽ tắt tất cả chức năng bán hàng phía người dùng',

    ),
    'product_order_quick' => array(
        'type' => 'bool',
        'name' => 'Chế độ mua hàng nhanh',
        //'desc' => 'Mỗi giá trị 1 dòng',
    ),
    'product_checkout_quick' => array(
        'type' => 'bool',
        'name' => 'Chế độ thanh toán nhanh',
        //'desc' => 'Mỗi giá trị 1 dòng',
    ),
);
