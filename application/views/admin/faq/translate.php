
<style>
.form .param_html .formRight {
	width: 100%;
	margin: 0px;
}
</style>

<?php
	$_macro = array();
	$_macro['mod'] = 'faq';
	
	foreach ($langs as $l)
	{
		$lang = array(
			'key' 	=> $l->code,
			'name' 	=> $l->name,
		);
		
		$lang['rows'][] = array(
			'param' 	=> "question[{$l->id}]",
			'type' 		=> 'text',
			'name' 		=> lang('question'),
			'value' 	=> $info['question'][$l->id],
		);
		
		$lang['rows'][] = array(
			'param' 	=> "answer[{$l->id}]",
			'type' 		=> 'html',
			'name' 		=> lang('answer'),
			'value' 	=> $info['answer'][$l->id],
			'attr' 		=> array('height' => 400),
		);
		
		$_macro['form_translate']['langs'][] = $lang;
	}

	echo macro()->page($_macro);
