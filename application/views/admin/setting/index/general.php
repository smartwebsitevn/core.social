<?php
echo '<div class="row">';
echo '<div class="col-md-4 col-xs-12">';
echo macro('mr::form')->row( array(
	'param' 	=> 'favicon','name' 	=> lang('site_icon'),'type' 		=> 'image',
	'_upload' 	=>$upload_favicon
));
echo '</div>';

echo '<div class="col-md-4 col-xs-12">';
echo macro('mr::form')->row( array(
	'param' 	=> 'logo','name' 	=> lang('site_logo'),'type' 		=> 'image',
	'_upload' 	=>$upload_logo
));
echo '</div>';

/*
echo '<div class="col-md-3 col-xs-12">';
echo macro('mr::form')->row( array(
	'param' 	=> 'logo_slogan','name' 	=> lang('slogan'),'type' 		=> 'image',
	'_upload' 	=>$upload_logo_slogan
));
echo '</div>';*/

echo '<div class="col-md-4 col-xs-12">';
echo macro('mr::form')->row( array(
	'param' 	=> 'logo_admin','name' 	=> lang('admin_logo'),'type' 		=> 'image',
	'_upload' 	=>$upload_logo_admin
));
echo '</div>';

echo '</div>';
echo '<div class="clear"></div><hr/>';
echo macro('mr::form')->row( array(
	'param' 	=> 'name','name' 	=> lang('site_name'),
	'value' 	=>$setting['name'],'req'=>1,
));
echo macro('mr::form')->row( array(
	'param' 	=> 'email','name' 	=> lang('site_email'),
	'value' 	=>$setting['email'],'req'=>1,
));
// thong tin lien he
foreach(array(/*'slogan',*/'hotline','phone','fax','yahoo','skype','address'/*,'support','livechat','video'*/) as $p){
	echo macro('mr::form')->row(  array(
		'param' 	=> $p,
		'value' 	=> $setting[$p],
	));
}
// mang xa hoi
foreach(array('facebook','twitter','youtube','googleplus'/*,'linkedin','instagram'*/) as $p){
	echo macro('mr::form')->row(  array(
		'param' 	=> $p,
		'value' 	=> $setting[$p],
	));
}

echo macro('mr::form')->row( array(
	'param' 	=> 'meta_desc','type' 		=> 'textarea',
	'value' 	=>$setting['meta_desc'],
));

echo macro('mr::form')->row( array(
	'param' 	=> 'meta_key','type' 		=> 'textarea',
	'value' 	=>$setting['meta_key'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'meta_other','type' 		=> 'textarea',
	'value' 	=>$setting['meta_other'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'embed_js','type' 		=> 'textarea',
	'value' 	=>$setting['embed_js'],'desc'=>lang('embed_js_note'),
));
echo macro('mr::form')->row( array(
	'param' 	=> 'no_index','type' 		=> 'bool',
	'value' 	=>$setting['maintenance'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'maintenance','type' 		=> 'bool',
	'value' 	=>$setting['maintenance'],
));

echo '<div id="maintenance_notice" >';
echo macro('mr::form')->row( array(
	'param' 	=> 'maintenance_notice','type' 		=> 'html',
	'value' 	=> $setting['maintenance_notice'],
));
echo '</div>';

echo '<a target="_blank" href="'.admin_url('md-site/setting').'" >Thiết lập khác</a>'

?>
