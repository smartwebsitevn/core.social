<!-- Form Login-->
<div id="cd-login">
    <?php if (mod("user")->setting('login_allow')): ?>
    <form method="POST" action="<?php echo $user->_url_login ?>" accept-charset="UTF-8"
          class="cd-form form_action">
        <input
            name="return" type="hidden" value="<?php echo current_url(1) ?>">
        <p class="fieldset">
            <?php   if(setting_get('config-facebook_oauth_id')):  ?>
            <a href="<?php echo $user->_url_login_facebook ?>" class="btn  btn-social btn-facebook"> <i
                    class="fa fa-facebook"></i> Đăng nhập bằng Facebook </a>
            <?php endif; ?>
            <?php   if(setting_get('config-google_oauth_id')):  ?>
            <a href="<?php echo $user->_url_login_google ?>" class="btn  btn-social btn-google-plus">
                <i class="fa fa-google-plus"></i> Đăng nhập bằng Google </a>
            <?php endif; ?>
        </p>

        <div name="login_error" class="alert alert-danger" role="alert" style=" display:none;"></div>
        <p class="fieldset">
            <i class="image-replace fa fa-envelope-o"></i>
            <input id="signin-email" class="full-width has-padding has-border" placeholder="Email|<?php echo lang('account') ?>"
                   name="email" type="text">

        <div class="clear"></div>
        <span name="email_error" class="error"></span>
        </p>

        <p class="fieldset">
            <i class="image-replace fa fa-key"></i>
            <input id="signin-password" class="full-width has-padding has-border" placeholder="<?php echo lang('your_password') ?>"
                   name="password" type="password" value="">
            <a href="#0" class="hide-password">Hide</a>

        <div class="clear"></div>
        <span name="password_error" class="error"></span>
        </p>
        <?php
        if (!isset($captcha))       $captcha = site_url('captcha');

        $_id_login = random_string('unique'); ?>

        <p class="fieldset">

        <div class="col-md-8 p0">
            <i class="image-replace fa fa-eye"></i>
            <input class="full-width has-padding has-border" placeholder="<?php echo lang('security_value') ?>" name="security_code"
                   type="text">
        </div>
        <div class="col-md-4 p0">
            <div class="ml5 mb5">
                <img id="<?php echo $_id_login; ?>" src="<?php echo $captcha; ?>" _captcha="<?php echo $captcha; ?>"
                     class="dInline captcha">
                <a href="#reset" onclick="change_captcha('<?php echo $_id_login; ?>'); return false;" class="dInline"
                   title="Reset captcha">
                    <i class="glyphicon glyphicon-repeat"></i>
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
        <span name="security_code_error" class="mt3 error"></span>
        </p>
        <div class="clearfix"></div>
        <p class="fieldset"><input class="full-width" type="submit" value="<?php echo lang('login') ?>"></p>
    </form>

    <p class="cd-form-bottom-message"><a href="#0"><?php echo lang('forgot_password_press_here') ?></a></p>
    <?php else: ?>
        <div class="alert alert-danger well p40">Not available</div>
    <?php endif; ?>
</div>
     