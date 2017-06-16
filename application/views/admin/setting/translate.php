
<?php
	$_macro = array();
	$_macro['mod'] = 'setting';
$_macro['toolbar'] = array();
	foreach ($langs as $l)
	{
		$lang = array(
			'key' 	=> $l->code,
			'name' 	=> $l->name,
		);
		$lang['rows'][] = array(
			'param' 	=> "name[{$l->id}]",
			'type' 		=> 'text',
			'name' 		=> lang('name'),
			'value' 	=> $info['name'][$l->id],
		);
		$lang['rows'][] = array(
			'param' 	=> "meta_key[{$l->id}]",
			'type' 		=> 'textarea',
			'name' 		=> lang('meta_key'),
			'value' 	=> $info['meta_key'][$l->id],
		);
		$lang['rows'][] = array(
			'param' 	=> "meta_desc[{$l->id}]",
			'type' 		=> 'textarea',
			'name' 		=> lang('meta_desc'),
			'value' 	=> $info['meta_desc'][$l->id],
		);


		
		$_macro['form_translate']['langs'][] = $lang;
	}

	echo macro()->page($_macro);
