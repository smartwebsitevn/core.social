
<style>
.form .param_html .formRight {
	width: 100%;
	margin: 0px;
}
</style>

<?php
	$_macro = array();
	$_macro['mod'] = 'page';
	
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
			'param' 	=> "description[{$l->id}]",
			'type' 		=> 'html',
			'name' 		=> lang('description'),
			'value' 	=> $info['description'][$l->id],
			'attr' 		=> array('height' => 400),
		);
		
		$_macro['form_translate']['langs'][] = $lang;
	}

	echo macro()->page($_macro);
