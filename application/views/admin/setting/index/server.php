<?php
echo '
	<h3>'.lang('title_server_general').'</h3>
	<div class="hr hr-12 mb30"></div>';
/*echo macro('mr::form')->row( array(
	'param' 	=> 'base_url',
	'value' 	=>$setting['base_url'],
	'req'=>true,
));*/
echo macro('mr::form')->row( array(
	'param' 	=> 'server_ip',
	'value' 	=>$setting['server_ip'],
	'req'=>true,
));

echo macro('mr::form')->row( array(
	'param' 	=> 'use_ssl','type' 		=> 'bool',
	'value' 	=>$setting['use_ssl'],
));

echo macro('mr::form')->row( array(
	'param' 	=> 'use_seo_url','type' 		=> 'bool',
	'value' 	=>$setting['use_seo_url'],
));

echo macro('mr::form')->row( array(
	'param' 	=> 'xss_protect','type' 		=> 'bool',
	'value' 	=>$setting['xss_protect'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'proxy_ips','type' 		=> 'bool',
	'value' 	=>$setting['proxy_ips'],
));
echo '
	<h3>'.lang('title_server_upload').'</h3>
	<div class="hr hr-12 mb30"></div>';

echo macro('mr::form')->row( array(
	'param' 	=> 'upload_max_size','type' 		=> 'spinner',
	'value' 	=>$setting['upload_max_size'],'desc'=>lang('upload_max_size_note'),
));
echo macro('mr::form')->row( array(
	'param' 	=> 'upload_max_size_admin','type' 		=> 'spinner',
	'value' 	=>$setting['upload_max_size_admin'],'desc'=>lang('upload_max_size_admin_note'),
));
echo macro('mr::form')->row( array(
	'param' 	=> 'upload_allowed_types','type' 		=> 'select_multi',
	'value' 	=>$setting['upload_allowed_types'],'values_single'=>$file_types,
));


echo '
	<h3>'.lang('title_server_logs').'</h3>
	<div class="hr hr-12 mb30"></div>';


echo macro('mr::form')->row( array(
	'param' 	=> 'log_activity','type' 		=> 'bool',
	'value' 	=>$setting['log_activity'],
));

echo macro('mr::form')->row( array(
	'param' 	=> 'log_user_balance','type' 		=> 'bool',
	'value' 	=>$setting['log_user_balance'],
));

echo macro('mr::form')->row( array(
	'param' 	=> 'log_access','type' 		=> 'bool',
	'value' 	=>$setting['log_access'],
));
echo macro('mr::form')->row( array(
	'param' 	=> 'log_error','type' 		=> 'bool',
	'value' 	=>$setting['log_error'],
));


echo '
	<h3>'.lang('title_server_captcha').'</h3>
	<div class="hr hr-12 mb30"></div>';



echo macro('mr::form')->row(  array(
	'name' 		=> lang('captcha_type'),
	'type' 		=> 'select',
	'param' 	=> "captcha_type",
	'value' 	=> $setting['captcha_type'],
	'values_single' 	=>  $captcha_types,
	'req'=>true,
));
$display_captcha=$setting['captcha_type'] ;
?>
<div class="captcha_type captcha_google" style=" <?php echo ($display_captcha !='google')?"display:none":""?>">
	<?php
	echo macro('mr::form')->row(  array(
		'param' 	=> "captcha_google_api_url",
		'value' 	=> $setting['captcha_google_api_url'],
	));

	echo macro('mr::form')->row(  array(
		'param' 	=> "captcha_google_secret_key",
		'value' 	=> $setting['captcha_google_secret_key'],
	));

	echo macro('mr::form')->row(  array(
		'param' 	=> "captcha_google_site_key",
		'value' 	=> $setting['captcha_google_site_key'],
	));

	?>
</div>
<?php
echo '
	<h3>'.lang('title_server_email').'</h3>
	<div class="hr hr-12 mb30"></div>';



echo macro('mr::form')->row(  array(
	'type' 		=> 'select',
	'param' 	=> "email_protocol",
	'value' 	=> $setting['email_protocol'],
	'values_single' 	=>  $mail_protocols,
	'req'=>true,
));


$display_smtp=$setting['email_protocol'] ;


?>
<div class="email_mail email_protocol">
	<?php
	echo macro('mr::form')->row(  array(
			'param' 	=> "email_from_name",
			'value' 	=> $setting['email_from_name'],
	));

	echo macro('mr::form')->row(  array(
			'param' 	=> "email_from_email",
			'value' 	=> $setting['email_from_email'],
	));

	echo macro('mr::form')->row(  array(
			'param' 	=> "email_reply_name",
			'value' 	=> isset($setting['email_reply_name']) ? $setting['email_reply_name'] : '',
	));
	echo macro('mr::form')->row(  array(
			'param' 	=> "email_reply_email",
			'value' 	=> isset($setting['email_reply_email']) ? $setting['email_reply_email'] : '',
	));
	?>
</div>
<div class="nencer_mail_api" style=" <?php echo ($display_smtp !='nencer_mail_api')?"display:none":""?>">
	<?php
	echo macro('mr::form')->row(  array(
			'param' 	=> "nencer_mail_api_user",
			'value' 	=> $setting['nencer_mail_api_user'],
	));
	echo macro('mr::form')->row(  array(
			'param' 	=> "nencer_mail_api_pass",
			'value' 	=> $setting['nencer_mail_api_pass'],
	));
	?>
</div>
<script type="text/javascript">
		$(document).ready(function() {
			$('select[name="email_protocol"]').bind('change', function() {
				if(this.value== 'nencer_mail_api')
				{					//$('.captcha_type').hide();
					$('.nencer_mail_api').show();

				}else{
					$('.nencer_mail_api').hide();
				}
			});

			$('select[name="captcha_type"]').bind('change', function() {
				if(this.value== 'google')
				{
					//$('.captcha_type').hide();
					$('.captcha_google').show();

				}else{
					$('.captcha_google').hide();
				}
			});
		});
</script>

