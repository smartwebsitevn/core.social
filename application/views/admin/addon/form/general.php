<?php
echo macro('mr::advForm')->row(array(
    'param' => 'image',
    'type' => 'image',
    '_upload' => $widget_upload_image
));
echo macro('mr::advForm')->row(array(
    'param' => 'name',
    'value' => $info['name'],
    'req' => true,
));

echo macro('mr::advForm')->row(array(
    'name' => lang('cat'),
    'param' => 'cat_id',
    'type' => 'select2',
    'value' => $info['cat_id'],
    'values_row' => array($categories, 'id', 'name'),
    'req' => true,
));

echo macro('mr::advForm')->row(array(
    'param' => 'description',
    'type' => 'html',
    'value' => $info['description'],
));
echo macro('mr::advForm')->row(array(
    'param' => 'sort_order', 'value' => $info['sort_order'],
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'is_feature',
    'name'		=> lang('feature'),
    'type' 		=> 'bool',
    'value'		=> (isset($info['is_feature']) ? $info['is_feature'] : 0)
));

echo macro('mr::advForm')->row(array(
    'param' 	=> 'status',
    'name'		=> lang('status'),
    'type' 		=> 'bool_status',
    'value'		=> (isset($info['status']) ? $info['status'] : 1)
));

?>