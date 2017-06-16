
<?php
	$_macro = array();
	$_macro['mod'] = 'cat';
	
	foreach ($langs as $l)
	{
		$lang = array(
			'key' 	=> strtolower($l->code),
			'name' 	=> $l->name,
		);
		$lang['rows'][] = array(
			'param' 	=> "name[{$l->id}]",
			'type' 		=> 'text',
			'name' 		=> lang('name'),
			'value' 	=> $info['name'][$l->id],
		);

		$_macro['form_translate']['langs'][] = $lang;
	}

//echo macro()->page($_macro);
echo macro('mr::form')->translate($_macro['form_translate']);
