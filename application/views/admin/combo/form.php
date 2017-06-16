<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;
$_macro['form']['rows'][] = array(
    'type' => 'ob', 'value' => '<div class="row">
                    <label class="col-sm-3 "> </label>
                    <div class="col-sm-9">
                        <div class="error alert alert-danger" name="services_error" style="display: none"></div>
                    </div>
                </div>'
);

$_macro['form']['rows'][] =array(
    'param' => 'name', 'name' => lang('name'),
    'value' => $info['name'], 'req' => 1,
);
$_macro['form']['rows'][] =array(
    'param' => 'expire_from', 'type' => 'date',
    'value' => $info['_expire_from'],
    'req' => 1
);
$_macro['form']['rows'][] =array(
    'param' => 'expire_to', 'type' => 'date',
    'value' => $info['_expire_to'],
    'req' => 1
);

$_macro['form']['rows'][] =array(
    'param' => 'price',
    'name' => lang('price'),
    'type' => 'number',
    'unit' => $currency->code,
    'value' => $info['price'],
    'req' => 1,
);
$_macro['form']['rows'][] = '<hr/>';
// thiet lap cau hinh han xem cho khoa hoc
$_macro['form']['rows'][] =  array(
    'param' => 'product_id','name'=>lang('apply_for_product'),'type'=>'select_multi',
    'value'=>isset($info["services"]->products)?$info["services"]->products:'',
    'values_row'=>array($products,'id','name'),
   // 'req' => 1,
);

$_macro['form']['rows'][] = array(
    'name' 	=> lang('product_watch_expired_type'),
    'param' 	=> 'product_watch_expired_type',
    'value'=>isset($info["services"]->product_watch_expired_type)?$info["services"]->product_watch_expired_type:0,

    'values'=>array('0'=>lang('product_watch_expired_type_default'),
        '1'=>lang('product_watch_expired_type_total'),
        '2'=>lang('product_watch_expired_type_custom')),
    'type' 		=> 'bool',
    'attr'=>["class"=>"toggle_status tc",'_field'=>"product_watch_expired_type"],
    //'desc'=>'N?u ?? m?c ??nh thì s? l?y theo c?u hình chung'

);
$_macro['form']['rows'][] = '<div id="product_watch_expired_type_value_2" class="product_watch_expired_type_value" >';

$_macro['form']['rows'][] = array(
    'param' 	=> 'product_watch_expired_value',
    'name' 	=> lang('product_watch_expired_value'),
    'value'=>isset($info["services"]->product_watch_expired_value)?$info["services"]->product_watch_expired_value:'',
    'type' 		=> 'number',

);
$_macro['form']['rows'][] = '</div>';
$_macro['form']['rows'][] = '<hr/>';
$_macro['form']['rows'][] =  array(
    'param' => 'lesson_id','name'=>lang('apply_for_lesson'),'type'=>'select_multi',
    'value'=>isset($info["services"]->lessons)?$info["services"]->lessons:'',
    'values_row'=>array($lessons,'id','name'),
   // 'req' => 1,
);
$_macro['form']['rows'][] = array(
    'name' 	=> lang('lesson_watch_expired_type'),
    'param' 	=> 'lesson_watch_expired_type',
    'value'=>isset($info["services"]->lesson_watch_expired_type)?$info["services"]->lesson_watch_expired_type:0,

    'values'=>array('0'=>lang('lesson_watch_expired_type_default'),
        '1'=>lang('lesson_watch_expired_type_total'),
        '2'=>lang('lesson_watch_expired_type_custom')),
    'type' 		=> 'bool',
    'attr'=>["class"=>"toggle_status tc",'_field'=>"lesson_watch_expired_type"],
    //'desc'=>'N?u ?? m?c ??nh thì s? l?y theo c?u hình chung'

);
$_macro['form']['rows'][] = '<div id="lesson_watch_expired_type_value_2" class="lesson_watch_expired_type_value" >';

$_macro['form']['rows'][] = array(
    'param' 	=> 'lesson_watch_expired_value',
    'name' 	=> lang('lesson_watch_expired_value'),
    'value'=>isset($info["services"]->lesson_watch_expired_value)?$info["services"]->lesson_watch_expired_value:'',

    'type' 		=> 'number',

);
$_macro['form']['rows'][] = '</div>';

$_macro['form']['rows'][] = '<hr/>';

$_macro['form']['rows'][] =array(
    'param' => 'image',
    'type' => 'image',
    '_upload' => $widget_upload,
);


$_macro['form']['rows'][] =array(
    'param' => 'desc', 'type' => 'textarea',
    'value' => $info['desc'],
);

$_macro['form']['rows'][] =array(
    'param' => 'description', 'name' => lang('description'),
    'type' => 'html',
    'value' => $info['description']
);
$_macro['form']['rows'][] =array(
    'param' => 'file',
    'type' => 'file',
    '_upload' => $widget_upload_files,
);

foreach (array('meta_title', 'meta_desc', 'meta_key') as $meta) {
    $_macro['form']['rows'][] =array(
        'param' => $meta, 'type' => 'textarea',
        'value' => $info[$meta],
    );
}
$_macro['form']['rows'][] =array(
    'param' => 'sort_order', 'name' => lang('sort_order'),
    'value' => $info['sort_order'],
);

$_macro['form']['rows'][] =array(
    'param' => 'feature', 'type' => 'bool_status',
    'value' =>$info['feature']?1:0,
);
$_macro['form']['rows'][] =array(
    'param' => 'status', 'type' => 'bool_status',
    'value' =>$info?$info['status']:1,
);
echo macro()->page($_macro);


?>