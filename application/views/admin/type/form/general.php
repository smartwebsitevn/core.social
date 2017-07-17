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
    /*
echo macro('mr::advForm')->row(array(
    'param' => 'video',
    'value' => $info['video'],
));
   */
echo macro('mr::advForm')->row(array(
    'param' => 'brief',
    'type' => 'html',
    'value' => $info['brief']
));

echo macro('mr::advForm')->row(array(
    'param' => 'description',
    'type' => 'html',
    'value' => $info['description'],
));
/*
echo macro('mr::advForm')->row(array(
    'param' => 'tags', 'attr' => array('class' => 'tags form-control', '_url' => $url_tag),
    'value' => isset($info['tags']) ? implode(', ', $info['tags']) : '',
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'is_soon',
    'name'		=> lang('soon'),
    'type' 		=> 'bool',
    'value'		=> (isset($info['sois_soonon']) ? $info['is_soon'] : 0)
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'is_new',
    'name'		=> lang('new'),
    'type' 		=> 'bool',
    'value'		=> (isset($info['is_new']) ? $info['is_new'] : 0)
));*/
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