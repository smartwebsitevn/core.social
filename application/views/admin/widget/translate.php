
<?php
	$_macro = array();
	$_macro['mod'] = 'widget';
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
		foreach ($setting_params as $p => $o)
		{
			if(!isset($o['translate']) || !$o['translate'])
				continue;

			$lang['rows'][] = array(
					'param' 	=> $p."[{$l->id}]",
					'type' 		=> $o['type'],
					'name' 		=> $o['name'],
					'value' 	=> $info[$p][$l->id],
			);
		}
		$_macro['form_translate']['langs'][] = $lang;
	}

	echo macro()->page($_macro);
