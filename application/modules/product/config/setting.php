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

$setting = array(
    //== Che do duyet tin
    'mode_verify_recruit' => array(
        'type' 	=> 'select',
        'name' 	=> 'Chế độ duyệt tin bài',
        'values'=>array('auto'=>'Tự động xác thực [Tin được duyệt ngay khi đăng]',
            'special'=>'Chỉ dữ liệu dạng thẻ [Nếu tin có dữ liệu text thì phải chờ duyệt]',
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
    'post_recruit_require_verify_account' => array(
        'type' 	=> 'bool',
        'name' 	=> 'Yêu cầu tài khoản phải được xác thực mơi cho đăng tin',
    ),
    'post_recruit_help' => array(
        'type' 	=> 'html',
        'name' 	=> 'Hướng dẫn đăng tuyển',
    ),
    'post_recruit_url_rule' => array(
        'type' 	=> 'text',
        'name' 	=> 'Link điều khoản đăng tuyển',
    ),
    // id user duoc set cho tim mau phuc vu xem truoc
    'user_of_preview_recruit' => array(
        'type' 	=> 'text',
        'name' 	=> 'ID của user sẽ set cho tin mẫu, nếu không set thì không xem trước được ',
    ),
    // dang bai viet
    'post_blog_url_help' => array(
        'type' 	=> 'text',
        'name' 	=> 'Hướng dẫn đăng bài viết mới',
    ),
    
    //== quang cao tin tuyen dung
    'ads_recruit_help' => array(
        'type' 	=> 'html',
        'name' 	=> 'Hướng dẫn quảng cáo tin bài',
    ),
    'ads_recruit_url_rule' => array(
        'type' 	=> 'text',
        'name' 	=> 'Link điều khoản quảng cáo tin bài',
    ),
    'ads_recruit_url_more' => array(
        'type' 	=> 'text',
        'name' 	=> 'Link tìm hiển thêm về quảng cáo tin',
    ),
    // quang cao thành viên
    'ads_company_help' => array(
        'type' 	=> 'html',
        'name' 	=> 'Hướng dẫn quảng cáo thành viên',
    ),
    'ads_company_url_rule' => array(
        'type' 	=> 'text',
        'name' 	=> 'Link điều khoản quảng cáo thành viên',
    ),

    'ads_company_url_more' => array(
        'type' 	=> 'text',
        'name' 	=> 'Link tìm hiển thêm về quảng cáo nhà tuyển dụng',
    ),
    'ads_company_max_number_job' => array(
        'type' 	=> 'text',
        'name' 	=> 'Số thẻ tin bài tối đa mà thành viên có thể thêm để quảng cáo',
    ),

    //==
    'cronjob_compare_notice_recruit' => array(
        'type'  => 'text',
        'name'  => 'Lấy số thành viên đăng nhập trong số tháng để gửi email tin bài',
    ),

    // Blog
    'blog_intro' => array(
        'type' 	=> 'html',
        'name' 	=> 'Nội dung giới thiệu Blog',
    ),
);

