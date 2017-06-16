
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		// Form login action
		$('#cart_user_login').nstUI({
			method:	'formAction',
			formAction:	{
				field_load: '',
				event_complete: function()
				{
					window.location.reload();
				}
			}
		});
		
	});
})(jQuery);
</script>


<style>
#main_content .form-label {
	width: 100%;
	font-weight: bold;
	display: block;
	padding: 0 0 2px 0;
}
#main_content .form-item {
	width: 195px;
}
#main_content .form-item .t-input, #main_content .form-item select {
	width: 200px;
}
</style>


<div class="t-box" id="main_content">
	<div class="box-title">
		<h1><?php echo lang('title_cart_checkout'); ?></h1>
	</div>
	
	<div class="box-content">
	
		<!-- Login form -->
		<div class="left" style="width:48%;">
			<div class="h_title"><?php echo lang('title_checkout_login'); ?></div>
			<div class="clear"></div>
			
			<form id="cart_user_login" class="t-form" action="<?php echo $url->_url_login; ?>" method="post">
				<?php $rid = random_string('unique'); ?>
				
				<div class="message-box error f13 hideit hide" style="color:#444; margin:0 0 10px;" name="login_error"></div>
				<div class="clear"></div>
				
				<div class="form-row">
					<label class="form-label" for="param_email_<?php echo $rid; ?>"><?php echo lang('email'); ?>:</label>
					<div class="form-item">
						<span class="one"><input name="email" id="param_email_<?php echo $rid; ?>" type="text" class="t-input" /></span>
						<span name="email_autocheck" class="autocheck"></span>
						<div class="clear"></div>
						<div name="email_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="form-row">
					<label class="form-label" for="param_password_<?php echo $rid; ?>"><?php echo lang('password'); ?>:</label>
					<div class="form-item">
						<span class="one"><input name="password" id="param_password_<?php echo $rid; ?>" type="password" class="t-input" /></span>
						<div name="password_autocheck" class="autocheck"></div>
						<div class="clear"></div>
						<div name="password_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="form-row">
					<div class="form-item">
						<label>
							<input type="checkbox" name="remember" />
							<?php echo lang('remember_login');?>
						</label>
						<div class="clear pb5"></div>
						
						<div class="left mb5">
							<input type="submit" value="<?php echo lang('button_login'); ?>" class="button button-border medium red f" />
						</div>
						<div class="clear"></div>
						
						<div class="link fontB">
							<a href="<?php echo $url->_url_forgot; ?>"><?php echo lang('button_forgot_password');?></a>
						</div>
						<div class="clear"></div>
						
						<div class="link fontB">
							<a href="<?php echo $url->_url_register; ?>"><?php echo lang('button_register');?></a>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				
			</form>
			<div class="clear"></div>
		</div>
		<!-- end Login form -->
		
		
		<!-- Mua hang khong can dang ki -->
		<div class="right" style="width:48%;">
			<div class="h_title"><?php echo lang('title_checkout_fast'); ?></div>
			<div class="clear"></div>
			
			<form class="t-form form_action" action="<?php echo $url->_url_checkout; ?>" method="post">
				
				<div class="form-row">
					<label class="form-label" for="param_contact_name"><?php echo lang('full_name'); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<span class="one"><input name="contact_name" value="<?php echo $contact['name']; ?>" id="param_contact_name" type="text" class="t-input" /></span>
						<span name="contact_name_autocheck" class="autocheck"></span>
						<div class="clear"></div>
						<div name="contact_name_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="form-row">
					<label class="form-label" for="param_contact_email"><?php echo lang('email'); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<span class="one"><input name="contact_email" value="<?php echo $contact['email']; ?>" id="param_contact_email" type="text" class="t-input" /></span>
						<span name="contact_email_autocheck" class="autocheck"></span>
						<div class="clear"></div>
						<div class="f11" style="margin-top:2px;"><?php echo lang('note_checkout_email'); ?></div>
						<div class="clear"></div>
						<div name="contact_email_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="form-row">
					<label class="form-label" for="param_contact_phone"><?php echo lang('phone'); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<span class="one"><input name="contact_phone" value="<?php echo $contact['phone']; ?>" id="param_contact_phone" type="text" class="t-input" /></span>
						<span name="contact_phone_autocheck" class="autocheck"></span>
						<div class="clear"></div>
						<div name="contact_phone_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>

				<div class="form-row">
					<label class="form-label" for="param_security_code"><?php echo lang('security_code'); ?>:<span class="req">*</span></label>
					<div class="form-item">
						<div style="margin-bottom:5px;">
							<img id="captcha_security" src="<?php echo $captcha; ?>" _captcha="<?php echo $captcha; ?>" class="dInline">
							<a href="" onclick="change_captcha('captcha_security'); return false;" class="dInline">
								<img src="<?php echo public_url('site'); ?>/css/img/reset.png" class="dInline" style="margin:5px;">
							</a>
						</div>
						
						<span class="one" style="float:left;"><input name="security_code" id="param_security_code" style="width:90px;" class="t-input" type="text" /></span>
						<div name="security_code_autocheck" class="autocheck"></div>
						<div class="clear"></div>
						<div name="security_code_error" class="error"></div>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="form-row">
					<div class="form-item">
			           	<input type="submit" value="<?php echo lang('button_payment'); ?>" class="button button-border medium blue f" />
					</div>
				</div>
				
			</form>
			<div class="clear"></div>
		</div>
		<!-- end Mua hang khong can dang ki -->
		
		
		<div class="clear"></div>
	</div>
</div>
