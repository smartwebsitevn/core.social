<?php
$setting = isset($setting) ? (array)$setting : null;
//pr($setting);
echo macro('mr::form')->row(array(
    'param' => 'discount', 'type' => 'number',
    'name' => lang('discount'),
    'value' => $setting['discount'],
    'req' => 1,
));
echo macro('mr::form')->row(array(
    'param' => 'discount_type', 'type' => 'select',
    'name' => lang('discount_type'),
    'value' => $setting['discount_type'],
    'values'=>array('1'=>lang('fix'),'2'=>lang('%')),
    'req' => 1,
));

echo macro('mr::form')->row(  array(
    'param' => 'product_id','name'=>lang('apply_for_product'),'type'=>'select_multi',
    'value'=>$setting['product_id'],'values_row'=>array($products,'id','name'),
));

echo macro('mr::form')->row(  array(
    'param' => 'lesson_id','name'=>lang('apply_for_lesson'),'type'=>'select_multi',
    'value'=>$setting['lesson_id'],'values_row'=>array($lessons,'id','name'),
));

echo view('tpl::voucher/js');
?>


