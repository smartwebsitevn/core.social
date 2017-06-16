<?php
// Noi dung mau dang don gian
$_tmp = array(
	'letter',// thu mau
	'reply',//  phan hoi
	'notice',//  noi dung thong bao cho nguoi dung
);
foreach ($_tmp as $k => $v)
{
	$config['form_content_types'][$k+1] = $v;
	$config['form_content_type_'.$v] = $k+1;
}
