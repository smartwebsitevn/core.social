<?php
$info = isset($info) ? (array)$info : null;
/* Images */
echo macro('mr::advForm')->row(array(
    'param' => 'banner',
    'type' => 'image',
    '_upload' => $widget_upload_banner
));
echo macro('mr::advForm')->row(array(
    'param' => 'icon',
    'type' => 'image',
    '_upload' => $widget_upload_icon
));
echo macro('mr::advForm')->row(array(
    'param' => 'icon_fa',
    'attr'=>['placeholder'=>"example: arrows"],
    'desc'=>t('html')->a('http://fontawesome.io/icons/' ,'ICONS HERE' ,array('target'=>'_blank')) ,
));

echo macro('mr::advForm')->row(array(
    'param' => 'images',
    'type' => 'image',
    '_upload' => $widget_upload_images
));

echo '<hr>';
/*
echo macro('mr::advForm')->row(array(
    'param' => 'count_view', 'value' => $info['count_view'],
));*/
echo macro('mr::advForm')->row(array(
    'param' => 'sort_order', 'value' => $info['sort_order'],
));
echo macro('mr::advForm')->row(array(
    'type' => 'custom',
    'html' => '<hr>'
));
/*echo macro('mr::advForm')->row(array(
    'param' => 'comment_allow', 'type' => 'bool',
    'value' => $info ? $info['comment_allow'] : 0,
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));

echo macro('mr::advForm')->row(array(
    'param' => 'comment_fb_allow', 'type' => 'bool',
    'value' => $info ? $info['comment_fb_allow'] : 0,
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));

echo macro('mr::advForm')->row(array(
    'param' => 'rate_allow', 'type' => 'bool',
    'value' => $info ? $info['rate_allow'] : 0,
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
*/
echo macro('mr::advForm')->row(array(
    'param' => 'is_in_menu',
    'name' => lang('show_in_menu'),
    'type' => 'bool_status',
    'value' => (isset($info['is_in_menu']) ? $info['is_in_menu'] : 0)
));

echo '<hr>';

echo macro('mr::advForm')->row(array(
    'param' => 'seo_url',
    'value' => $info['seo_url']
));
echo macro('mr::advForm')->row(array(
    'param' => 'seo_title',
    'value' => $info['seo_title']
));

echo macro('mr::advForm')->row(array(
    'param' => 'seo_description',
    'value' => $info['seo_description']
));
echo macro('mr::advForm')->row(array(
    'param' => 'seo_keywords',
    'value' => $info['seo_keywords']
));
/*
echo macro('mr::advForm')->row(array(
    'param' => 'created',
    'type' => 'datetime',
    'value' => isset($info['created']) ? $info['created'] : array(
        0 => date('H:i'),
        1 => date('d-m-Y')
    )
));
*/
/*echo macro('mr::advForm')->row(array(
    'name' => lang('created'),
    'param' => 'created_day',
    'type' => 'date',
    'value' => $info['created_day'] ? $info['created_day'] : date('d-m-Y'),
));*/
  if($info) {
echo macro('mr::advForm')->row(array(
    'type' => 'custom',
    'html' => '
		<label class="col-sm-3  control-label ">
            ' . lang('created') . '
        </label>
        <span class="col-sm-9 control-label" style="text-align: left;">
        	' . date('H:i', $info['created']) . ', ' . lang('date') . ' ' . date('d-m-Y', $info['created']) . '
        </span>
		'
));
if ($info['updated'])
    echo macro('mr::advForm')->row(array(
        'type' => 'custom',
        'html' => '
		<label class="col-sm-3  control-label ">
            ' . lang('last_updated') . '
        </label>
        <span class="col-sm-9 control-label" style="text-align: left;">
        	' . date('H:i', $info['updated']) . ', ' . lang('date') . ' ' . date('d-m-Y', $info['updated']) . '
        </span>
		'
    ));
  }
?>