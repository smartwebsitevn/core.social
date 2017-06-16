<?php
$_data_country =function($countries,$info )
{
    $path = public_url().'/img/world/';
    ob_start();?>
    <select name="code" class="form-control">
        <option value="">-=<?php echo lang('language'); ?>=-</option>
        <?php foreach ($countries as $group): ?>
					<optgroup label="<?php echo $group->name; ?>">

					<?php foreach ($group->countries as $v): ?>
                           <option style="background:url(<?php echo $path.strtolower($v->id).'.gif'?>) no-repeat 5px 8px;text-indent: 1.5em" value="<?php echo $v->id; ?>" <?php echo form_set_select($v->id, isset($info['code']) ? $info['code'] : ''); ?>>
                <?php echo $v->name; ?>
            </option>

        <?php endforeach; ?>
        	</optgroup>
				<?php endforeach; ?>

    </select>



    <?php return ob_get_clean();
};
$info =isset($info) ? (array)$info : array();
$_macro = $this->data;
$_macro['toolbar'] = array();
$_macro['form']['data'] = $info;

/*
$_macro['form']['rows'][] = array(
    'param' 	=> 'code','name'=>lang('language'),'type' 		=> 'select',
     'values_row'=>array($countries,'code','name'),
    'req' => true,

);*/

$_macro['form']['rows'][] = array(
    'name' => lang('language'), 'type'=> 'ob',
    'value' => $_data_country($countries,$info),
    'req' => true,

);

$_macro['form']['rows'][] = array(
    'param' => 'name',
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'directory','name'=>lang('code'),
    'req' => true,
);
/*$_macro['form']['rows'][] = array(
    'param' => 'code',
    'req' => true,
);*/
$_macro['form']['rows'][] = array(
    'param' => 'charset',
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'status','type'=>"bool_status",
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'sort_order',
);

echo macro()->page($_macro);