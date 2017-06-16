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
/*echo macro('mr::advForm')->row(array(
    'param' => 'brief',
    'type' => 'html',
    'value' => $info['brief']
));*/

echo macro('mr::advForm')->row(array(
    'param' => 'description',
    'type' => 'html',
    'value' => $info['description'],
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'is_feature',
    'name'		=> lang('feature'),
    'type' 		=> 'bool',
    'value'		=> (isset($info['is_feature']) ? $info['is_feature'] : 0)
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'is_default',
    'name'		=> lang('default'),
    'type' 		=> 'bool',
    'value'		=> (isset($info['is_default']) ? $info['is_default'] : 0)
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'status',
    'name'		=> lang('status'),
    'type' 		=> 'bool_status',
    'value'		=> (isset($info['status']) ? $info['status'] : 1)
));

?>