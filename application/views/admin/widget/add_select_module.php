<?php
$_data_module =function($modules)
{
	ob_start();?>
	<select name="module" class="form-control"  onchange="jQuery(this).parents('form').submit();">
		<option value=""></option>
		<?php foreach ($modules as $m): ?>
			<optgroup label="<?php echo $m->name; ?>">
				<?php foreach ($m->widget as $w => $w_o): ?>
					<option value="<?php echo $m->key.':'.$w; ?>">
						<?php echo $w_o['name']; ?>
					</option>
				<?php endforeach; ?>

			</optgroup>
		<?php endforeach; ?>
	</select>
	<?php return ob_get_clean();
};
$_macro = $this->data;
$_macro['form']['data'] = isset($info) ? (array) $info : array();

$_macro['form']['rows'][] = array(
	'name'=>lang('module'),'type' => 'ob',
	'value' 	=>  $_data_module($modules)
);

echo macro()->page($_macro);
?>
