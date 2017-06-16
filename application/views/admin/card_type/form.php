<?php
	$info = isset($info) ? (array) $info : array('status' => 1);

	$_providers = $providers;
	//array_unshift($_providers, (object)array('id' => '', 'name' => ''));
	$_providers = array_pluck($_providers, 'name', 'id');

	$_keys = $keys;
	//array_unshift($_keys, (object) array('key' => '', 'name' => ''));
	$_keys = array_pluck($_keys, 'name', 'key');
	
	$_make_form_fee_user_group = function() use ($user_groups, $info)
	{
		ob_start();?>
		<?php foreach ($user_groups as $row): ?>
			<div class="left mt5">
				<span class="left" style="width:160px; padding-top:3px;">
					<label><?php echo $row->name; ?></label>
				</span>
				<span class="left">
					<input name="fee_user_group[<?php echo $row->id; ?>]"
						value="<?php echo array_get($info, 'fee_user_group.'.$row->id, ''); ?>"
						style="width:150px;" type="text"
					/>
				</span>
			</div>
			<div class="clear"></div>
		<?php endforeach; ?>
		<?php return ob_get_clean();
	};
	
	$_macro = $this->data;
	$_macro['form']['data'] = $info;
	
	$_macro['form']['rows'][] = array(
		'param' => 'name',
		'req' 	=> true,
	);
	
	$_macro['form']['rows'][] = array(
		'param' => 'key',
		'type' 	=> 'select',
		'values'=> $_keys,
		'req' 	=> true,
	);

	$_macro['form']['rows'][] = array(
	    'param' => 'fee',
	    'unit' 	=> '%',
	    'desc' 	=> lang('note_fee'),
	    'req' 	=> true,
	);
	/*
	$_macro['form']['rows'][] = array(
	    'param' => 'profit',
	    'unit' 	=> '%',
	    'desc' 	=> lang('profit_notice'),
	    'req' 	=> false,
	);
	*/

	$_macro['form']['rows'][] = array(
		'param' => 'provider',
		'type' 	=> 'select',
		'values'=> $_providers,
		'req' 	=> true,
	);
	
	$_macro['form']['rows'][] = array(
	    'param' => 'provider_sub',
	    'type' 	=> 'select',
	    'values'=> $_providers,
	    'req' 	=> false,
	);
	
	$_macro['form']['rows'][] = array(
		'param' => 'fee_user_group',
		'type' 	=> 'custom',
		'html' 	=> $_make_form_fee_user_group(),
		'unit' 	=> '%',
		'desc' 	=> lang('note_fee_user_group'),
	);
	$_macro['form']['rows'][] = array(
		'param' => 'status',
		'type' 	=> 'bool',
	);
	
	$_macro['form']['rows'][] = array(
		'param' 	=> 'image',
		'type' 		=> 'image',
		'_upload' 	=> $widget_upload,
	);
	
	echo macro()->page($_macro);
	