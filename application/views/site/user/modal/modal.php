<?php
$user = new stdClass();
$user = site_create_url('account', $user);
$user->_url_login_facebook = site_url('oauth/facebook');
$user->_url_login_google = site_url('oauth/google');
$data['user'] = $user;
$data['captcha'] = site_url('captcha/four');
?>
<link rel="stylesheet" href="<?php echo public_url('site/css') ?>/sign.css">
<div class="user_authentication">
<?php if (mod("user")->setting('login_allow')): ?>
<!-- Form Login-->
<?php view('tpl::user/modal/login', $data) ?>
<?php endif; ?>
<?php if (mod("user")->setting('register_allow')): ?>
<!-- Form register-->
<?php view('tpl::user/modal/register', array($data)) ?>
<?php endif; ?>
<!-- Form forgot-->
<?php view('tpl::user/modal/forgot', array($data)) ?>
</div>
