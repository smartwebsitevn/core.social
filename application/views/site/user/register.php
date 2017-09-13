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
<?php if (mod("user")->setting('register_allow')): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title">Đăng Ký</h1>
        </div>
        <div class="panel-body">
            <?php view('tpl::user/_common/register', array("user" => $user)) ?>

        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger">Chức năng này tạm thời dừng hoạt động, xin quý khách vui lòng quay lại sau</div>
<?php endif; ?>
</div>
