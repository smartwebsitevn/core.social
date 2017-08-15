<?php

/*
echo macro('mr::advForm')->row(array(
    'param' => 'point',
    'value' => $info['point'],
    'type' => 'spinner'
));

echo '<hr/>';
*/
/* Images */
/*
echo macro('mr::advForm')->row(array(
    'param' => 'video',
    //'type' => 'textarea',
    'value'=>$info['video'],
));


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
));*/


echo macro('mr::advForm')->row(array(
    'param' => 'count_view', 'value' => $info['count_view'],
));

/*

echo macro('mr::advForm')->row(array(
    'param' => 'comment_allow', 'type' => 'bool',
    'value' => $info ? $info['comment_allow'] : 2,
    'values' => array('2' => lang('default'), '0' => lang('no'), '1' => lang('yes')),
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
echo macro('mr::advForm')->row(array(
    'param' => 'comment_fb_allow', 'type' => 'bool',
    'value' => $info ? $info['comment_fb_allow'] : 2,
    'values' => array('2' => lang('default'), '0' => lang('no'), '1' => lang('yes')),
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
echo macro('mr::advForm')->row(array(
    'param' => 'rate_allow', 'type' => 'bool',
    'value' => $info ? $info['rate_allow'] : 2,
    'values' => array('2' => lang('default'), '0' => lang('no'), '1' => lang('yes')),
    'desc' => 'Nếu để mặc định thì sẽ lấy theo cấu hình chung'

));
*/

echo '<hr>';
/*echo macro('mr::advForm')->row(array(
    'param' => 'noindex',
    'type' => 'bool',
    'value' => (isset($info['noindex']) ? $info['noindex'] : 0)
));*/

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
?>