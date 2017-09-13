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
    //== Che do duyet tin
    'mode_verify_product' => array(
        'type' 	=> 'select',
        'name' 	=> 'Chế độ duyệt tin bài',
        'values'=>array(
            'auto'=>'Tự động xác thực [Tin được duyệt ngay khi đăng]',
            'handle'=>'Thủ công [Tin phải chờ duyệt]')

    ),
    //== Che do xac thuc user
    'mode_verify_user' => array(
        'type' 	=> 'select',
        'name' 	=> 'Chế độ xác thực thành viên',
        'values'=>array('auto'=>'Tự động xác thực',
                        'handle'=>'Thủ công')
                         ),
    
    //== dang tin tuyen dung
    'post_product_require_verify_account' => array(
        'type' 	=> 'bool',
        'name' 	=> 'Yêu cầu tài khoản phải được xác thực mơi cho đăng tin',
    ),

    'product_delta_point' => array(
        'type' 	=> 'text',
        'name' 	=> 'Delta point (C)',
        'desc' 	=> 'D = Cx(tổng số thành viên)/100 , nếu bài viết có số poin < D thì sẽ bị ẩn khỏi hệ thống',
    ),
   /* 'user_delta_point' => array(
        'type' 	=> 'text',
        'name' 	=> 'C (point)',
        'desc' 	=> 'D = Cx(tổng số thành viên)/100 , nếu bài viết có số poin < D thì sẽ bị ẩn khỏi hệ thống',
    ),*/
);

