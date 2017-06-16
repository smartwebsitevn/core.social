<?php

if($info){
    echo macro('mr::advForm')->row(array(
        'type'=>'static',
        'param' => 'key',
        'value' => $info['key'],
    ));
    echo macro('mr::advForm')->row(array(
        'type'=>'static',
        'param' => 'params',
        'value' => $info['params'],
    ));
}

else
{
    echo macro('mr::advForm')->row(array(
        'param' => 'key',
        'value' => $info['key'],
        'req' => true,
    ));
    echo macro('mr::advForm')->row(array(
        'param' => 'params',
        'value' => $info['params'],
        'req' => true,
    ));
}

echo macro('mr::advForm')->row(array(
    'param' => 'name',
    'value' => $info['name'],
    'req' => true,
));

echo macro('mr::advForm')->row(array(
    'param' => 'content',
    'type' => 'html',
    'value' => $info['content'],
));

echo macro('mr::advForm')->row(array(
    'param' => 'sort_order', 'value' => $info['sort_order'],
));
echo macro('mr::advForm')->row(array(
    'param' 	=> 'status',
    'name'		=> lang('status'),
    'type' 		=> 'bool_status',
    'value'		=> (isset($info['status']) ? $info['status'] : 1)
));

?>