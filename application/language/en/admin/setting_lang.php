<?php
$lang['setting_info']						= 'Thiết lập hệ thống';

$lang['tab_general']						= 'Thông tin chung';
$lang['tab_image']							= 'Hình ảnh';
$lang['tab_local']							= 'Bản địa';
$lang['tab_server']							= 'Máy chủ';
$lang['tab_security']                       = 'Bảo mật';
$lang['tab_connect']                        = 'Kết nối';
$lang['tab_license']                        = 'Giấy phép';

// General
$lang['site_logo']								= 'Logo web';
$lang['site_icon']								= 'Icon';
$lang['site_name']								= 'Tên web';
$lang['site_email']								= 'Email';
$lang['site_phone']								= 'Điện thoại';
$lang['site_fax']								= 'Số Fax';
$lang['site_address']							= 'Địa chỉ';
$lang['admin_logo']								= 'Logo quản trị';
$lang['meta_desc']								= 'Meta description';
$lang['meta_key']								= 'Meta keywords';
$lang['meta_other']								= 'Meta khác';
$lang['embed_js']								= 'Tích hợp Javascript';
$lang['embed_js_note']			                = 'Ví dụ: Google analytic, zopim...';
$lang['no_index']								= 'No index, no follow';
$lang['maintenance']							= 'Bảo trì hệ thống';
$lang['maintenance_notice']						= 'Thông báo tới khách hàng khi bảo trì';

// Local
$lang['date_format']							= 'Định dạng ngày tháng';
$lang['timezone']							    = 'Múi giờ';
$lang['admin_language']						    = 'Ngôn ngữ trang quản trị';
$lang['site_language']							= 'Ngôn ngữ trang người dùng';
$lang['length_unit']							= 'Đơn vị chiều dài';
$lang['weight_unit']							= 'Đơn vị trọng lượng';
$lang['file_unit']						    	= 'Đơn vị dung lượng tệp tin';
$lang['invoice_pre_key']						= 'Tiền tố hóa đơn';
$lang['invoice_pre_number']						= 'Số lượng số "0" trước mã hóa đơn';

// image
$lang['upload_img_max_demision']					= 'Kích thước ảnh tối đa';
$lang['upload_img_resize_demision']					= 'Kích thước ảnh sẽ được thiết lập lại nếu vượt quá kích thước tối đa';
$lang['upload_img_thumb_primary_demision']			= 'Kích thước ảnh thumb chính';
$lang['upload_img_thumb_demision']				    = 'Kích thước ảnh thumb khác';
$lang['upload_img_width']							= 'Độ rộng';
$lang['upload_img_height']							= 'Độ cao';

$lang['upload_server_status']						= 'Tải ảnh lên máy chủ khác';
$lang['upload_server_url']					    	= 'Đường dẫn http://';
$lang['upload_server_hostname']						= 'Hostname|IP';
$lang['upload_server_username']						= 'Username';
$lang['upload_server_password']						= 'Password';
$lang['upload_server_save_on_local']				= 'Giữ bản sao trên máy chủ này';

// Security
$lang['title_security_admin']						= 'Trang quản trị';
$lang['admin_matrix']								= 'Sử dụng thẻ xác thực';
$lang['title_security_user']						= 'Người dùng';
$lang['title_security_user_register']				= 'Đăng ký';
$lang['title_security_user_login']					= 'Đăng nhập';
$lang['title_security_user_balance']				= 'Số dư';
$lang['user_register_allow']						= 'Cho phép đăng ký tài khoản';
$lang['user_register_require_activation']			= 'Xác thực email khi đăng ký tài khoản';
$lang['user_register_banned_countries']				= 'Không cho phép các quốc gia đăng ký tài khoản';
$lang['user_login_fail_count_max']					= 'Số lần đăng nhập sai sẽ bị khóa IP';
$lang['user_login_fail_count_max_note']				= 'Nhập 0 là không giới hạn';
$lang['user_login_fail_block_timeout']				= 'Thời gian đợi (phút)';
$lang['user_login_fail_block_timeout_note']			= 'Thời gian cấm IP khi đăng nhập sai quá số lần cho phép (Nhập 0 là cấm vĩnh viễn)';
$lang['user_login_check_ip']						= 'Kiểm tra IP đăng nhập';
$lang['user_balance_block']						    = 'Khóa số dư tài khoản';
$lang['user_balance_block_note']					= 'Khách hàng sẽ không thể sử dụng số dư để thanh toán.';
$lang['user_balance_timeout_from_register']			= 'Thời gian khóa số dư với tài khoản vừa đăng ký (giờ)';
$lang['user_balance_timeout_from_register_note']	= 'Không thiết lập thì được phép sử dụng số dư ngay khi đăng ký xong';

//xac thuc
$lang['title_security_user_confirm'] = 'Xác thực';
$lang['user_security_payment']   = 'Xác thực mua hàng';
$lang['user_security_transfer']   = 'Xác thực chuyển tiền';
$lang['user_security_withdraw']  = 'Xác thực rút tiền';
$lang['user_security_change_password']  = 'Xác thực đổi mật khẩu';
$lang['user_security_change_pin']  = 'Xác thực đổi mật khẩu cấp 2';
$lang['user_security_sms_otp_message'] = 'Nội dung tin nhắn OTP,<br/> mã OTP sẽ đặt trong biến {code}';
$lang['user_security_sms_odp_message'] = 'Nội dung tin nhắn ODP,<br/> mã ODP sẽ đặt trong biến {code}';

$lang['user_security_types']          = 'Kiểu xác thực';
$lang['user_security_type_password']  = 'Bằng mật khẩu đăng nhập';
$lang['user_security_type_pin']       = 'Bằng mật khẩu cấp 2';
$lang['user_security_type_sms_otp']  = 'Bằng SMS OTP';
$lang['user_security_type_sms_odp']  = 'Bằng SMS ODP';
$lang['sms_otp_max_send']            = 'Số lần gửi OTP tối đa/ngày';
$lang['sms_otp_max_re_send']         = 'Số lần gửi lại OTP tối đa/ngày';
$lang['sms_odp_max_re_send']         = 'Số lần gửi lại ODP tối đa/ngày';


// Server
$lang['title_server_general']					= 'Cấu hình';
$lang['title_server_logs']						= 'Logs';
$lang['title_server_upload']					= 'Tải lên';
$lang['title_server_captcha']					= 'Mã bảo vệ';
$lang['title_server_email']						= 'Email';
$lang['base_url']							    = 'Tên miền web';
$lang['server_ip']							    = 'Ip máy chủ';
$lang['use_ssl']							    = 'Sử dụng SSL';
$lang['use_seo_url']							= 'Sử dụng SEO Url';
$lang['xss_protect']						    = 'Lọc Xss';
$lang['upload_max_size']						= 'Giới hạn dung lượng tệp tin người dùng tải lên(Mb)';
$lang['upload_max_size_note']					= 'Nhập 0 là không giới hạn';
$lang['upload_max_size_admin']					= 'Giới hạn dung lượng tệp tin người quản trị được tải lên (Mb)';
$lang['upload_allowed_types']					= 'Loại tệp tin được phép tải lên';
$lang['log_error']						        = 'Lưu log lỗi';
$lang['log_access']						        = 'Lưu log truy cập';
$lang['log_activity']						    = 'Lưu log hoạt động';
$lang['log_login']						        = 'Lưu log đăng nhập';
$lang['log_user_balance']						= 'Lưu log thay đổi số dư';
$lang['proxy_ips']							    = 'Proxy IP';
$lang['proxy_ips_note']							= 'Mỗi Proxy IP trên một dòng';
$lang['banned_countries']						= 'Chặn quốc gia truy cập vào web';
$lang['banned_ips']							    = 'Chặn các IP  truy cập vào web';
$lang['banned_ips_note']						= 'Mỗi IP trên một dòng';
$lang['captcha_type']							= 'Loại';
$lang['captcha_google_api_url']					= 'Google API Url';
$lang['captcha_google_secret_key']				= 'Google secret key';
$lang['captcha_google_site_key']				= 'Google site key';
$lang['email_from_email']						= 'SMTP Server';
$lang['email_from_name']						= 'SMTP Username';
$lang['email_from_pass']						= 'SMTP Password';
$lang['email_protocol']							= 'Máy chủ gửi mail';
$lang['email_smtp_host']						= 'SMTP Server';
$lang['email_smtp_user']						= 'SMTP Username';
$lang['mail_smtp_pass']						   = 'SMTP Password';
$lang['email_smtp_port']						= 'SMTP Port';
$lang['email_smtp_timeout']						= 'SMTP Timeout (giây)';
$lang['nencer_mail_api']						= 'Nencer Mail API';
$lang['amazone_ses']							= 'Amazon SES';
$lang['smtp']									= 'SMTP';
$lang['phpmail']								= 'Mail()';
$lang['sendmail']								= 'SendMail server';
$lang['postfix']								= 'Postfix server';
$lang['sendgrid']								= 'Sendgrid API';


//License:
$lang['license_key']								= 'Key';
$lang['license_expired']							= 'Ngày hết hạn';
$lang['license_domain']								= 'Tên miền';
$lang['license_status']								= 'Trạng thái';

//Connect
$lang['facebook_oauth_id']							= 'Facebook ID';
$lang['facebook_oauth_key']							= 'Facebook Key';
$lang['google_oauth_id']							= 'Google ID';
$lang['google_oauth_key']							= 'Google Key';

// Option

$lang['deposit_status']							= 'Nạp tiền';
$lang['deposit_amount_min']						= 'Số tiền nạp tối thiểu';
$lang['deposit_amount_max']						= 'Số tiền nạp tối đa';
$lang['order_quantity_max']						= 'Số lượng tin bài tối đa trong một lần mua';
$lang['order_auto_active']						= 'Loại đơn hàng được kích hoạt sau khi khách thanh toán thành công';
$lang['payment']								= 'Cổng thanh toán';
$lang['note_tut_tags_value']					= 'Nhập giá trị sau đó nhấn Enter';
$lang['note_option_value']						= 'Nếu không khai báo thì không áp dụng';
