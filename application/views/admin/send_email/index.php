<?php
	$_macro = $this->data;
	$_macro['toolbar']	= array();
	$_macro['form']['title'] = lang('title_send_email');

	$_macro['form']['rows'][] = array(
		'param' => 'to',
		'name' 	=> lang('email_to'),
		'desc' 	=> lang('note_to'),
		'req' 	=> true,
		'attr'	=> array(
			'class' 		=> 'tags',
			'_tags_text' 	=> '',
			'_tags_ac' 		=> $url_search,
		),
	);

	$_macro['form']['rows'][] = array(
		'param' => 'subject',
		'req' 	=> true,
	);

	$_macro['form']['rows'][] = array(
		'param' => 'message',
		'type' 	=> 'html',
		'req' 	=> true,
	);
	
	$_macro['form']['data'] = ( ! isset($info)) ? array() : (array) $info;
	
	echo macro()->page($_macro);