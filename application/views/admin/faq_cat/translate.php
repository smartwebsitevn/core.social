
<?php
	$_macro = array();
	$_macro['mod'] = 'faq_cat';
	
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
		
		$_macro['form_translate']['langs'][] = $lang;
	}

	echo macro()->page($_macro);
