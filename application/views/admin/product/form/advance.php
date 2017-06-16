<?php
$user_options = $affiliate_options = null;
if ($info && isset($info['user_options'])) {
    $user_options = json_decode($info['user_options']);
}
if ($info && isset($info['affiliate_options'])) {
    $affiliate_options = json_decode($info['affiliate_options']);
}

echo '<h4>Cấu hình giới hạn xem khóa học</h4>';

echo macro('mr::advForm')->row(array(
    'param' => 'watch_config',
    'values' => array('0' => lang('default'), '1' => lang('setting')),
    'type' => 'bool',
    'attr' => ["class" => "toggle_status tc"],
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
echo '<div id="watch_config_value_1" class="watch_config_value" >';

echo macro('mr::advForm')->row(array(
    'param' => 'watch_expired', 'name' => lang('watch_expired'), 'type' => 'number',
    'value' => $info['watch_expired'],
    'unit' => 'ngày',
    //'desc'=>'Nếu để mặc định thì sẽ lấy theo cấu hình chung, '

));
echo macro('mr::advForm')->row(array(
    'param' => 'watch_times', 'name' => lang('watch_times'), 'type' => 'number',
    'value' => $info['watch_times'],
    'unit' => 'lần',

    //'desc'=>'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
echo '</div>';


echo '<hr/><h4>Cấu hình giáo viên cho khóa học</h4>';
/*echo macro('mr::advForm')->row(array(
	'param' => 'author_id',
	'type'	=> 'select2',
	'length_search' => 1,
	'ajax' => (object)array(
		'loader' => 'Simple',
		'source' => 'lesson_author'
	),
	'value' => $info['author_id'],
	'values_row' => array( $lesson_author, 'id', 'name' )
));*/

echo macro('mr::advForm')->row(array(
    'param' => 'author_id',
    'type' => 'search_multi',
    'length_search' => 1,
    'ajax' => (object)array(
        'loader' => 'Customer'
    ),
    'value' => isset($info['_author'])?$info['_author']:null,
    'values_row' => array(null, 'id', 'name')
));

echo '<hr/><h4>Cấp quyền truy cập khóa học cho thành viên</h4>';

//pr($info['_owners']));
echo macro('mr::advForm')->row(array(
    'param' => 'permission',
    'type' => 'search_multi',
    'length_search' => 1,
    'ajax' => (object)array(
        'loader' => 'Customer'
    ),
    'value' => isset($info['_owners'])?$info['_owners']:null,

    'values_row' => array(null, 'id', 'name')
));

echo '<hr/><h4>Cấu hình cho cộng tác viên</h4>';

echo macro('mr::advForm')->row(array(
    'param' => 'user_id', 'name' => lang('apply_for_partner'),
    'value' => isset($info['_user']) ? $info['_user']->email : '',
    'attr' => array('class' => 'autocomplete form-control', '_url' => $url_search_user),

));
echo macro('mr::advForm')->row(array(
    'param' => 'user_options[status]',
    'name' => lang('user_status'),
    'value' => $user_options ? $user_options->status : 0,
    'values' => array(/*'2'=>lang('default'),*/
        '0' => lang('no'), '1' => lang('yes')),
    'type' => 'bool',
    'attr' => ["class" => "toggle_status tc", '_field' => "user_status"],
    //'desc'=>'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));

echo '<div id="user_status_value_1" class="user_status_value" >';

echo macro('mr::advForm')->row(array(
    'param' => 'user_options[amount]',
    'name' => lang('user_amount'),
    'value' => $user_options ? $user_options->amount : '',
    'type' => 'number',

));
echo macro('mr::advForm')->row(array(
    'param' => 'user_options[amount_type]',
    'name' => lang('user_amount_type'),
    'value' => $user_options ? $user_options->amount_type : 1,
    'values' => array('1' => lang('user_amount_type_money'), '2' => lang('user_amount_type_percent')),
    'type' => 'select',

));
echo '</div>';
/*
echo macro('mr::advForm')->row(array(
	'type' 	=> 'custom',
	'html' 	=> '<hr><h4>Cấu hình chia sẻ</h4>'
));
echo macro('mr::advForm')->row(array(
	'param' 	=> 'affiliate_options[status]',
	'name' 	=> lang('user_status'),
	'value'=>$affiliate_options?$affiliate_options->status:0,
	'values'=>array('0'=>lang('no'),'1'=>lang('yes')),
	'type' 		=> 'bool',
	//'desc'=>'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
echo macro('mr::advForm')->row(array(
	'param' 	=> 'affiliate_options[amount]',
	'name' 	=> lang('user_amount'),
	'value'=>$affiliate_options?$affiliate_options->amount:'',
	'type' 		=> 'number',

));

echo macro('mr::advForm')->row(array(
	'param' 	=> 'affiliate_options[amount_type]',
	'name' 	=> lang('user_amount_type'),
	'value'=>$affiliate_options?$affiliate_options->amount_type:1,
	'values'=>array('1'=>lang('fix'),'2'=>lang('%')),
	'type' 		=> 'select',

));
*/
/*
echo macro('mr::advForm')->row( array(
	'param' => 'expire_time','name'=>lang('expire_time'),'type'=>'number',
	'unit'=>'Giờ',
	'value'=>$info['expire_time'],
	'desc'=>'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));


echo macro('mr::advForm')->row( array(
	'param' => 'max_watch_times','name'=>lang('max_watch_times'),'type'=>'number',
	'value'=>$info['max_watch_times'],
	'desc'=>'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));*/

