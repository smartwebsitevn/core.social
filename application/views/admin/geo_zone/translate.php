<style>
.form .param_html .formRight {
	width: 100%;
	margin: 0px;
}
</style>


<?php
$_macro = array();
$_macro['mod'] = 'geo_zone';
$this->lang->load('admin/geo_zone');


foreach ($langs as $l)
{
	$lang = array(
		'key' 	=> $l->code,
		'name' 	=> $l->name,
	);
	$lang['rows'][] = array(
		'param' 	=> "name[{$l->id}]",
		'type' 		=> 'text',
		'name' 		=> lang('geo_zone_name'),
		'value' 	=> $info['name'][$l->id],
	);
	$lang['rows'][] = array(
		'param' 	=> "description[{$l->id}]",
		'type' 		=> 'text',
		'name' 		=> lang('geo_zone_description'),
		'value' 	=> $info['description'][$l->id],
	);

	$_macro['form_translate']['langs'][] = $lang;
}
echo macro('mr::advForm')->page($_macro);

