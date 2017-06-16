
<h4 class="h_title"><?php echo lang('title_customer_info'); ?></h4>

<?php
	$_reqs 	= array('email');
	$_descs = array(
		'email' => lang('note_contact_email'),
	);
	
	foreach ($contact as $p => $v)
	{
		echo macro('mr::form')->row(array(
			'param' => $p,
			'value' => $v,
			'desc' 	=> array_get($_descs, $p),
			'req' 	=> in_array($p, $_reqs),
		));
	}
	
	/* echo macro('mr::form')->row(array(
		'param' 	=> 'register',
		'type'		=> 'bool',
		'value' 	=> true,
		'name' 		=> '',
		'values' 	=> lang('note_contact_register'),
	)); */
	
	echo t('html')->hidden('register', false);
	
	echo macro('mr::form')->captcha($captcha);
	
	echo macro('mr::form')->submit(lang('button_payment'));
?>
