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
    'sp1' => array(
        'type' => 'separate',
        'name' => 'Cấu hình thu phí',
    ),

    'premium_turn_on' => array(
        'type' => 'bool',
        'name' => 'Bật chức năng thu phí',
    ),
    'premium_course_exprie_time' => array(
        'type' => 'text',
        'name' => 'Hạn xem của 1 khóa học',
        'unit' => 'ngày',
        'value' => 30,
        'desc' => 'Tính từ lúc thành viên mua khóa học',
    ),

    'premium_lesson_exprie_time' => array(
        'type' => 'text',
        'name' => 'Hạn xem của 1 bài học',
        'unit' => 'ngày',
        'value' => 30,
        'desc' => 'Tính từ lúc thành viên mua bài học (áp dụng trường hợp mua lẻ bài học)',
    ),
    'premium_lesson_max_watch_times' => array(
        'type' => 'text',
        'name' => 'Số lần xem tối đa của 1 bài học đã mua',
        'unit' => 'lần',
        'value' => 10,
    ),

    'premium_turn_off_function_renew_plan' => array(
        'type' => 'bool',
        'name' => 'Tắt chức năng gia hạn VIP',
    ),
    'premium_turn_off_function_renew_voucher' => array(
        'type' => 'bool',
        'name' => 'Tắt chức năng gia hạn VOUCHER',
    ),
    'premium_turn_off_function_course_owner' => array(
        'type' => 'bool',
        'name' => 'Tắt chức năng quản lý khóa học đã mua',
    ),
    'premium_turn_off_function_lesson_owner' => array(
        'type' => 'bool',
        'name' => 'Tắt chức năng quản lý bài học đã mua',
    ),

    'sp1_1' => array(
        'type' => 'separate',
        'name' => 'Cấu hình Affiliate',
    ),

    'affiliate_turn_on' => array(
        'type' => 'bool',
        'name' => 'Bật chức năng Affiliate',
        'desc' => 'Hệ thống chỉ tính hoa hồng cho người giới thiệu khi thành viên mua Khóa học, Bài học, Combo.',

    ),
    'affiliate_commission_constant' => array(
        'type' => 'text',
        'name' => 'Hoa hồng cố định',
        'unit' => 'VNĐ',
        'value' => '',
        'desc' => 'Hệ thống sẽ cộng số tiền cố định cho người giới thiệu',
    ),
    'affiliate_commission_percent' => array(
        'type' => 'text',
        'name' => 'Hoa hồng phần trăm',
        'unit' => '%',
        'value' => '',
        'desc' => 'Hệ thống sẽ cộng số % đơn hàng tiền cố định cho người giới thiệu',
    ),
    'affiliate_commission_number' => array(
        'type' => 'text',
        'name' => 'Thiết lập số lần người mà giới thiệu có thể nhận Hoa hồng từ người mình giới thiệu',
        'value' => '1',
        'desc' => 'Nếu không thiết lập thì không giới hạn',
    ),
    /*'sp2' => array(
        'type' => 'separate',
        'name' => 'Cấu hình quảng cáo trong bài học',
    ),
    'ads_logo_status' => array(
        'type' => 'bool',
        'name' => 'Hiện logo trong bài học',
    ),
    'ads_video_status' => array(
        'type' => 'bool',
        'name' => 'Bật chức năng quảng cáo trong bài học',
    ),
    'ads_video_url' => array(
        'type' => 'text',
        'name' => 'Url video quảng cáo',
        'desc' => 'https://www.youtube.com/watch?v=HWh2cmKUsb0 hoặc HWh2cmKUsb0',
    ),
    'ads_video_time_total' => array(
        'type' => 'text',
        'name' => 'Thời gian quảng cáo (s)',
    ),
    'ads_video_time_skip' => array(
        'type' => 'text',
        'name' => 'Bỏ qua quảng cáo khi chạy được (s)',
    ),
    'ads_video_popup_url' => array(
        'type' => 'text',
        'name' => 'Url popup khi click vao quảng cáo',
    ),
*/


    'sp3' => array(
        'type' => 'separate',
        'name' => 'Cấu hình khác',
    ),
    'premium_checkout_quick' => array(
        'type' => 'select',
        'name' => 'Cho phép thanh toán đơn hàng nhanh',
        'values' => [
            "0" => "Không",
            "quick" => "Mua không cần điền thông tin",
            "quick_fast" => "Mua nhanh bằng số dư (Không cần Điền thông tin và xác nhận đơn hàng)",
        ],
        //'desc' => 'Mỗi giá trị 1 dòng',
    ),
    'login_to_watch' => array(
        'type' => 'bool',
        'name' => 'Yêu cầu đăng nhập mới xem được bài học',
        //'desc' => 'Mỗi giá trị 1 dòng',
    ),
    'rate_allow' => array(
        'type' => 'bool',
        'name' => 'Cho phép đánh giá bài học',
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
    'author_auto_default' => array(
        'type' => 'bool',
        'name' => 'Tự động gán tác giả mặc định ',
        'desc' => 'Nếu set là có, trường hợp khóa học|bài học không được gán tác giả thì hệ thống sẽ gán tác giả mặc định',
    ),
    'lesson_do_examination_max' => array(
        'type' => 'text',
        'name' => 'Số lần tối đa được phép làm lại bài trắc nghiệm',
        'desc' => 'Không thiết lập thì không giới hạn',
    ),
    /*
  'sp5' => array('type' => 'separate'),

  'path_sub' => array(
    'type' => 'text',
    'name' => 'Đường dẫn thư mục chưa phụ đề Sub',
    //'desc' => 'Mỗi giá trị 1 dòng',
  ),

  'sp6' => array(
  'type' => 'separate',
  'name' => 'Cấu hình xem bài học Private',
  ),
  'movie_private_status' => array(
  'type' => 'bool',
  'name' => 'bài học có link private',
  ),
  'movie_private_url' => array(
  'type' => 'text',
  'name' => 'Địa chỉ nhận link private (Server IP V6)',
  // 'desc' => 'Nếu không khai báo thì Server hiện thòi là Server IP V6 và bạn phải điền đầy đủ thông tin phía dưới',

  ),

  'google_oauth_id' => array(
  'type' => 'text',
  'name' => 'Google Oauth ID',
  ),
  'google_oauth_key' => array(
  'type' => 'text',
  'name' => 'Google Oauth Key',
  ),
  'google_oauth_redirect' => array(
  'type' => 'text',
  'name' => 'Url Redirect',
  'desc' => 'Địa chỉ nhận token từ google trả về',
  ),
  'google_oauth_refresh_token' => array(
  'type' => 'text',
  'name' => 'Refresh token',
  ),
  'google_oauth_login' => array(
  'type' => 'ob',
  'name' => 'Kết nối',
  'value' => $_data_oauth(),
  ),
  */
);
