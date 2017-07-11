<?php
$public_url_js=public_url('js');
?>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/plupload.full.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="<?php echo $public_url_js ?>/jquery/plupload/script.js"></script>

<?php


$rows[] =    macro('mr::form')->row_title(lang('block_change_pass'));
$rows[] =    [
	'param' => 'password','type' 	=> 'password',
	'name' 	=> lang('password_new'),
	'desc' 	=> lang('note_password_change'),
];

$rows[] =    [
	'param' => 'password_repeat',
	'type' 	=> 'password',
];


$rows[] =    macro('mr::form')->row_title(lang('block_change_info'));

$rows[] =    [
	'param' => 'name',
	'name' 	=> lang('full_name'),
	'value' => $user->name,
	'req' 	=> true,
];


if($user->can_edit_email)
{
	$rows[] = array(
		'param' => 'email_edit',
		'name' 	=> lang('email'),
		'value' => $user->email,
		'req' 	=> true,
	);
}
if($user->can_edit_username)
{
	$rows[] = array(
		'param' => 'username_edit',
		'name' 	=> lang('username'),
		'req' 	=> true,
		'value' => $user->username,
	);
}

if($user->can_edit_phone)
{
	$rows[] = array(
		'param' => 'phone_edit',
		'name' 	=> lang('phone'),
		'req' 	=> true,
		'value' => $user->phone,
	);
}

$rows[] =    [
	'param' => 'avatar','name'=>lang('Avatar'),
	'type' 	=> 'image',
	'_upload' 	=> $upload_avatar,
];
$rows[] =    [
	'param' => 'gender',
	'type' => 'bool',
	//'req' 	=> true,
	'value' => $user->gender?$user->gender:1,
	'values' => ['1'=>lang('gender_1'),'2'=>lang('gender_2'),'3'=>lang('gender_3')],
];
$rows[] =    [
	'param' => 'birthday',
	'type' => 'date',
	//'req' 	=> true,
	'value' => $user->birthday,
	'attr'=>['placeholder'=>lang("birthday_hint")]

];
//pr($countrys);
$rows[] =    [
	'param' => 'country',
	'type' => 'select',
	//'req' 	=> true,

	'value' => $user->country,
	'values_row' => [ $countrys, 'id', 'name'],
	'attr'=>['_dropdownchild'=>"city","_url"=>site_url('user/get_citys')]
];
$rows[] =    [
	'param' => 'city',
	'type' => 'select',
	'value' => $user->city,
	//'req' 	=> true,

	'values_row' => [ $citys, 'id', 'name'],
	//'attr'=>['_dropdownchild'=>"distric_id","_url"=>site_url('user/get_districs')]
];

/*$rows[] =    [
	'param' => 'distric',
	'type' => 'select',
	'value' => $user->distric,
	'values_row' => [ $districs, 'distric_id', 'distric_name'],
];*/
/*$rows[] = array(
	'param' => 'subject_id','name'=>lang('subject'),'type'=>'select',
	'value'=>$user->subject_id,'values_row'=>array($cat_type_subject,'id','name'),
	'req' 	=> true,
);*/
foreach([/*'profession',*/'facebook','twitter','address',] as $f){
	$rows[] =    [
		'param' => $f,
		'value' => $user->$f,
	];
}


$rows[] =    [
	'param' => 'desc',
	'value' => $user->desc,
	'type'=>"textarea"
];


/*$rows[] =    [
    'param' => 'password_old',
    'type' 	=> 'password',
    'req' 	=> true,
];*/


echo macro('mr::form')->form([

	'action'     => $user->_url_edit,
	'title'      => lang('title_user_edit'),
	'btn_submit' => lang('button_update'),

	'rows' => $rows,

]);


