<?php
$setting = isset($setting) ? (array)$setting : null;

echo macro('mr::form')->row(array(
    'param' => 'time', 'type' => 'number',
    'name' => lang('time'),
    'value' => $setting['time'],
    'req' => 1,
));
?>
