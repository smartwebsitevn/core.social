
<?php
	$_macro = array();
	$_macro['mod'] = 'cat_news';
	
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
		$lang['rows'][] = array(
				'param' 	=> "url[{$l->id}]",
				'type' 		=> 'text',
				'name' 		=> lang('url'),
				'value' 	=> $info['url'][$l->id],
		);
		$lang['rows'][] = array(
				'param' 	=> "titleweb[{$l->id}]",
				'type' 		=> 'text',
				'name' 		=> lang('titleweb'),
				'value' 	=> $info['titleweb'][$l->id],
		);

		$lang['rows'][] = array(
				'param' 	=> "description[{$l->id}]",
				'type' 		=> 'text',
				'name' 		=> lang('description'),
				'value' 	=> $info['description'][$l->id],
		);
		$lang['rows'][] = array(
				'param' 	=> "keywords[{$l->id}]",
				'type' 		=> 'text',
				'name' 		=> lang('keywords'),
				'value' 	=> $info['keywords'][$l->id],
		);
		$_macro['form_translate']['langs'][] = $lang;
	}

//echo macro()->page($_macro);
echo macro('mr::form')->translate($_macro['form_translate']);
;
