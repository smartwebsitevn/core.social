<?php

	$_value = function ($data)
	{
		$info = $data['info'];
		ob_start();
		?>
		<?php if ($info->_is_default): ?>
		<font class="fontB f14"><?php echo $info->value; ?></font>
		<font class="f11 red ml5">(<?php echo lang('currency_default'); ?>)</font>
		<input name="value" value="<?php echo (float) $info->value; ?>" class="hide" type="text"/>
	<?php else: ?>
		<input name="value" value="<?php echo (float) $info->value; ?>" id="param_value" type="text"
			   class="form-control input_number"/>
		<div name="value_error" class="clear error"></div>
		<div class=""><?php echo lang('note_value'); ?></div>
	<?php endif; ?>
		<?php return ob_get_clean();
	};
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['form']['data'] = isset($info) ? (array) $info : array();


	$_macro['form']['rows'][] = array(
		'param' => 'name', 'req' => true,
	);

	if (isset($info))
	{
		$_macro['form']['rows'][] = t('html')->hidden('code', $info->code);

		$_macro['form']['rows'][] = [
			'param' => 'value',
			'type'  => 'ob',
			'value' => $_value($this->data),
		];
	}
	else
	{
		$_macro['form']['rows'][] = [
			'param' => 'code',
			'req'   => true,
		];

		$_macro['form']['rows'][] = [
			'param' => 'value',
			'type'  => 'number',
			'name'  => lang('rate'),
			'desc'  => lang('note_value'),
			'req'   => true,
		];
	}

	$_macro['form']['rows'][] = array(
		'param' => 'decimal', 'req' => true,
	);
	$_macro['form']['rows'][] = array(
		'param' => 'symbol_left',
	);
	$_macro['form']['rows'][] = array(
		'param' => 'symbol_right',
	);

	$_macro['form']['rows'][] = [
		'param' => 'purse_prefix',
		'attr' => ['maxlength' => 3],
		'req'   => true,
	];

	$_macro['form']['rows'][] = [
		'param' => 'status',
		'type' => "bool_status",
	];

	echo macro()->page($_macro);