<?php
	/*
	 'salary',// muc luong
	'time_product',// thoi gian khoa hoc
	'time_update',// cap nhap cach day
	'time_post',// post cach day
	'age',// do tuoi
	'experience',// nam kinh nghiem
	'establish' // thoi gian thanh lap
	*/
$_tmp = array(
	'price',// khoang gia
);
foreach ($_tmp as $k => $v)
{
	$config['range_types'][$k+1] = $v;
	$config['range_type_'.$v] = $k+1;
}


// loai chi khong co to
$config['range_no_to_types']  = array('time_update','time_post');
