<?php
$_data_country =function($param,$code, $countries)
{
	$path = public_url().'/img/world/';
	ob_start();?>
	<select name="<?php echo $param; ?>[]" multiple="multiple" class="form-control select_multi"
		<option value="">-=<?php echo lang('layout'); ?>=-</option>
		<?php foreach ($countries as $group): ?>
					<optgroup label="<?php echo $group->name; ?>">

					<?php foreach ($group->countries as $v): ?>
					<option data-image="<?php echo $path.strtolower($v->id).'.gif'?>" value="<?php echo $v->id; ?>" <?php echo form_set_select($v->id, $code); ?>>
			    	<?php echo $v->name; ?>
			        </option>
					<?php endforeach; ?>

					</optgroup>
				<?php endforeach; ?>


	</select>
	<?php return ob_get_clean();
};

$_user_security_types = function($param, $value, $user_security_types)
{
	ob_start();?>
	<select name="<?php echo $param; ?>" class="form-control">
		<option value="">-=<?php echo lang('user_security_types'); ?>=-</option>
		<?php foreach ($user_security_types as $user_security_type): ?>
			<option value="<?php echo $user_security_type; ?>" <?php echo form_set_select($user_security_type, $value); ?>>
			    <?php echo lang('user_security_type_'.$user_security_type); ?>
			 </option>
		<?php endforeach; ?>
	</select>
	<?php return ob_get_clean();
};


echo '
	<h3>'.lang('title_security_admin').'</h3>
	<div class="hr hr-12 mb30"></div>';
echo macro('mr::form')->row(  array(
    'param' 	=> "admin_matrix",'type' 		=> 'bool',
    'value' 	=> $setting['admin_matrix'],
));

echo '
	<h3>'.lang('title_security_user').'</h3>
	<div class="hr hr-12 mb30"></div>';

echo '<h4>'.lang('title_security_user_confirm').'</h4>';
foreach ($user_security_mods as $user_security)
{
    $value = isset($setting["user_security_".$user_security]) ? $setting["user_security_".$user_security] : '';
    echo macro('mr::form')->row(  array(
        'param' 	=> "user_security_".$user_security, 'type' 		=> 'ob',
        'value' => $_user_security_types("user_security_".$user_security, $value, $user_security_types),
    ));
}
echo macro('mr::form')->row(  array(
    'param' 	=> "user_security_sms_otp_message", 'type' => 'text',
    'value' 	=> $setting['user_security_sms_otp_message'],
));
echo macro('mr::form')->row(  array(
    'param' 	=> "user_security_sms_odp_message", 'type' => 'text',
    'value' 	=> $setting['user_security_sms_odp_message'],
));

echo macro('mr::form')->row(  array(
    'param' 	=> "sms_otp_max_send", 'type' => 'text',
    'value' 	=> $setting['sms_otp_max_send'],
));
echo macro('mr::form')->row(  array(
    'param' 	=> "sms_otp_max_re_send", 'type' => 'text',
    'value' 	=> $setting['sms_otp_max_re_send'],
));
echo macro('mr::form')->row(  array(
    'param' 	=> "sms_odp_max_re_send", 'type' => 'text',
    'value' 	=> $setting['sms_odp_max_re_send'],
));

echo '<h4>'.lang('title_security_user_register').'</h4>';
echo macro('mr::form')->row(  array(
    'param' 	=> "user_register_allow",'type' 		=> 'bool',
    'value' 	=> $setting['user_register_allow'],
));

echo macro('mr::form')->row(  array(
    'param' 	=> "user_register_require_activation",'type' 		=> 'bool',
    'value' 	=> $setting['user_register_require_activation'],
));


/*echo macro('mr::form')->row( array(
    'param' 	=> 'user_register_banned_countries','type' 		=> 'select_multi',
    'value' 	=>$setting['user_register_banned_countries'],'values_row'=>array($countries,'code','name')
));*/
echo macro('mr::form')->row(array(
    'param' => 'user_register_banned_countries', 'type' => 'ob',
    'value' => $_data_country('user_register_banned_countries', $setting['user_register_banned_countries'], $countries),
));

echo '<h4>'.lang('title_security_user_login').'</h4>';
echo macro('mr::form')->row(  array(
    'param' 	=> "user_login_allow",'type' 		=> 'bool',
    'value' 	=> $setting['user_login_allow'],
));
echo macro('mr::form')->row(  array(
    'param' 	=> "user_login_check_ip",'type' 		=> 'bool',
    'value' 	=> $setting['user_login_check_ip'],
));


echo macro('mr::form')->row(  array(
    'param' 	=> "user_login_fail_count_max",'type' 		=> 'spinner',
    'value' 	=> $setting['user_login_fail_count_max'],
    'desc'=>lang('user_login_fail_count_max_note'),
));

echo macro('mr::form')->row(  array(
    'param' 	=> "user_login_fail_block_timeout",'type' 		=> 'spinner',
    'value' 	=> $setting['user_login_fail_block_timeout'],
    'desc'=>lang('user_login_fail_block_timeout_note'),
));

echo '<h4>'.lang('title_security_user_balance').'</h4>';

echo macro('mr::form')->row(  array(
    'param' 	=> "user_balance_block",'type' 		=> 'bool',
    'value' 	=> $setting['user_balance_block'], 'desc'=>lang('user_balance_block_note'),
));
echo macro('mr::form')->row(  array(
    'param' 	=> "user_balance_timeout_from_register",'type' 		=> 'spinner',
    'value' 	=> $setting['user_balance_timeout_from_register'],
    'desc'=>lang('user_balance_timeout_from_register_note'),
));
?>