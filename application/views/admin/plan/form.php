<?php
$info =isset($info) ? (array)$info : null;
$_data_discount = function ()use($info,$discount_types) {
    ob_start(); ?>
    <input name="discount" id="param_discount"  type="text"  class="form-control input-small pull-left format_number" style="width:100px;" value="<?php echo $info['discount']?>"/>
    <select name="discount_type"  class="form-control  pull-left" style="width:150px;">
        <?php foreach ($discount_types as $k => $v): ?>
            <option value="<?php echo $k; ?>" <?php echo form_set_select($k, $info['discount_type'])?>>
                <?php echo lang('discount_type_'.$v); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php return ob_get_clean();
};
$_macro = $this->data;
$_macro['form']['data'] = $info;
$_macro['form']['rows'][] = array(
    'param' => 'name', 'req' => 1,
);

$_macro['form']['rows'][] = array(
    'param' => 'cost', 'req' => 1, 'type' => 'number'
);

$_macro['form']['rows'][] = array(
    'name' => 'discount', 'req' => 1, 'type' => 'ob',
    'value'=>$_data_discount(),
);

$_macro['form']['rows'][] = array(
    'param' => 'day', 'req' => 1, 'type' => 'number',
    'desc' => lang('day_note'),
);

$_macro['form']['rows'][] = array(
    'param' => 'status',
    'type' => 'bool_status',
);

/*$_macro['form']['rows'][] = array(
    'param' => 'sort_order',
);*/

echo macro()->page($_macro);