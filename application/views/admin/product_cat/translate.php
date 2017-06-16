<style>
.form .param_html .formRight {
	width: 100%;
	margin: 0px;
}
</style>
<?php
$_macro = array();
$_macro['mod'] = 'product';
$this->lang->load('admin/product');


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

	$lang['rows'][] = array(
		'param' 	=> "SEOtitle[{$l->id}]",
		'type' 		=> 'text',
		'name' 		=> lang('SEOtitle'),
		'value' 	=> $info['SEOtitle'][$l->id]
	);

	$lang['rows'][] = array(
		'param' 	=> "SEOdescription[{$l->id}]",
		'type' 		=> 'text',
		'name' 		=> lang('SEOdescription'),
		'value' 	=> $info['SEOdescription'][$l->id]
	);

	$_macro['form_translate']['langs'][] = $lang;
}

echo macro('mr::advForm')->page($_macro);

