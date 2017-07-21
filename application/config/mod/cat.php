<?php
	/*//= thanh vien candidate
	'u_level',// trinh do thanh vien
	'u_specialize',// chuyen mon cua thanh vien
	'u_skill', // ky nang cua thanh vien
	'u_meetwork',// kha nang dap ung cong viec cua thanh vien
	'u_education', // trinh do hoc van cua thanh vien
	'u_school', // truong dao tao thanh vien
	'u_rank', // cap bac cua thanh vien
	'u_quality', // pham chat, to chat cua thanh vien
	'u_type', //loai thanh vien
	//= Viec lam job
	'j_type', //loai viec lam
	'j_note', // ghi chu viec lam
	'j_welfare', //phuc loi
	//= cong ty company
	'c_size', // qui mo cong ty
	'c_form', // loai hinh cong ty
	'c_request', // Yeu cau nop ho so cua cong ty
	//= tin bai news
	'n_cat', // danh muc
	'n_author', // tac gia

	//'training', // he dao tao
	//'school', // truong hoc
	//'subject', // Mon hoc/
//	'time_product',// thoi gian khoa hoc
//'age',// do tuoi yeu cau
//	'level',// trinh do thanh vien
*/
$_tmp = array(
	'user_job',
	);


foreach ($_tmp as $k => $v)
{
	$config['cat_types'][$k+1]  = $v;
	$config['cat_type_'.$v] = $k+1;
}


$config['cat_hiarachy_types']  = array();//'cat'
$config['cat_feature_types']  = array();
$config['cat_image_types']  = array();