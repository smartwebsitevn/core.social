<?php
$info = isset($info) ? (array)$info : null;

/* Name is required */
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
/*
echo macro('mr::advForm')->row(array(
    'param' => 'parent_id','name'=>lang('parent'),
    'type'=>'select2',
    'values_row'=> array($parents,'id','_name'),
        'value' => $info['parent_id']

));

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
)); */
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