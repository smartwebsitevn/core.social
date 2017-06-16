<?php

echo macro('mr::advForm')->row(array(
    'param' 	=> 'is_alway_in_stock',
    'name'		=> lang('alway_in_stock'),
    'type' 		=> 'bool_status',
    'value'		=> isset($info['is_alway_in_stock']) ? $info['is_alway_in_stock'] : 1,
    'values'	=> array( lang('number_in_stock') ,lang('alway_in_stock')),
    'desc' => lang('number_in_stock_hint')

));

echo macro('mr::advForm')->row(array(
    'param' => 'quantity', 'value' => $info['quantity'],
    'type' => 'spinner',
    'refer' => 'status',
    'value' => $info['quantity'],
    'desc' => lang('quantity_hint')
));
/*
echo macro('mr::advForm')->row(array(
    'param' => 'point',
    'value' => $info['point'],
    'type' => 'spinner'
));*/
echo macro('mr::advForm')->row(array(
    'name' => lang('warranty'),
    'param' => 'warranty_id',
    'type' => 'select',
    'value' => $info['warranty_id'],
    'values_row' => array( $cat_type_warranty, 'id', 'name' )
));
echo macro('mr::advForm')->row(array(
    'name' => lang('stock'),
    'param' => 'stock_id',
    'type' => 'select',
    'value' => $info['stock_id'],
    'values_row' => array( $cat_type_stock, 'id', 'name' )
));

echo macro('mr::advForm')->row(array(
    'param' => 'taxclass',
    'type' => 'select',
    'value' => isset($info['taxclass']) ? $info['taxclass'] : 0,
    'values_row' => array( $taxclasses, 'id', 'name' )
));

echo macro('mr::advForm')->row(array(
    'param' 	=> 'shipping',
    'type' 		=> 'bool',
    'value'		=> (isset($info['shipping']) ? $info['shipping'] : 1),
    'desc' => lang('shipping_hint')

));
echo '<hr/>';

echo macro('mr::advForm')->row(array(
    'param' => 'weight',
    'type' => 'number',
    'value' => $info['weight']
));
echo macro('mr::advForm')->row(array(
    'param' => 'dimension',
    'value' => $info['dimension']
));
echo '<hr/>';

/* Images */
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
/*
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
    'param' => 'images',
    'type' => 'image',
    '_upload' => $widget_upload_images
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'files',
    'type' 		=> 'file',
    '_upload' 	=> $widget_upload_files
));
echo '<hr>';

echo macro('mr::advForm')->row(array(
    'param' => 'count_view', 'value' => $info['count_view'],
));

echo macro('mr::advForm')->row(array(
    'type' => 'custom',
    'html' => '<hr>'
));
echo macro('mr::advForm')->row(array(
    'param' => 'has_voucher',
    'name' => lang('has_voucher'),
    'type' => 'bool_status',
    'value' => (isset($info['has_voucher']) ? $info['has_voucher'] : 0)
));
/*
echo macro('mr::advForm')->row(array(
    'param' => 'has_combo',
    'name' => lang('has_combo'),
    'type' => 'bool_status',
    'value' => (isset($info['has_combo']) ? $info['has_combo'] : 0)
));

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
    'param' => 'is_in_menu',
    'name' => lang('show_in_menu'),
    'type' => 'bool_status',
    'value' => (isset($info['is_in_menu']) ? $info['is_in_menu'] : 0)
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