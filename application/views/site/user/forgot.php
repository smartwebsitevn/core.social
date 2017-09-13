<?php
//echo macro('tpl::user/macros')->register();
?>

<?php
$user = new stdClass();
$user = site_create_url('account', $user);
$user->_url_login_facebook = site_url('oauth/facebook');
$user->_url_login_google = site_url('oauth/google');
$data['user'] = $user;
$data['captcha'] = site_url('captcha/four');
?>
<div class=" user_authentication">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1 class="panel-title">L?y l?i m?t kh?u</h1>
			</div>
			<div class="panel-body">
				<?php view('tpl::user/_common/forgot', array("user" => $user)) ?>

			</div>
		</div>
</div>