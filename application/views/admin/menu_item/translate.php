
<?php
	$_macro = array();
	$_macro['mod'] = 'menu';
	
	foreach ($langs as $l)
	{
		$lang = array(
			'key' 	=> $l->code,
			'name' 	=> $l->name,
		);
		
		$lang['rows'][] = array(
			'param' 	=> "title[{$l->id}]",
			'type' 		=> 'text',
			'name' 		=> lang('title'),
			'value' 	=> $info['title'][$l->id],
		);
		
		$_macro['form_translate']['langs'][] = $lang;
	}

//echo macro()->page($_macro);
echo macro('mr::form')->translate($_macro['form_translate']);
