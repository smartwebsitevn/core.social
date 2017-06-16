<?php

// oauth
foreach(array('facebook_oauth_id','facebook_oauth_key','google_oauth_id','google_oauth_key') as $p){
	echo macro('mr::form')->row(  array(
		'param' 	=> $p,
		'value' 	=> $setting[$p],
	));
}

?>
