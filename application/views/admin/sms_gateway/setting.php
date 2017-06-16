
<?php
	$args = $this->data;

	$args['form'] = [

		'title' => lang('sms_gateway_'.$code),
	];
	foreach ($params as $p):
    	$args['form']['rows'][] = array(
    	    'param' => $p,
    	    'name'  => lang("sms_gateway_{$code}_{$p}"),
    	    'type'  => 'text',
    	    'value' => $setting[$p],
    	    'req' 	=> true,
    	);
	endforeach;
	echo macro()->page($args);
$_id = '_' . random_string('unique');
?>
