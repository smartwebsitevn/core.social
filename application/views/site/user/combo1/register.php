
<!-- Form register-->
<div id="cd-signup">
    <?php if (mod("user")->setting('register_allow')): ?>
    <form method="POST" action="<?php echo $user->_url_register ?>" accept-charset="UTF-8"
          class="cd-form form_action">
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


        <p class="fieldset">
            <i class="image-replace fa fa-file-text-o"></i>
            <input class="full-width has-padding has-border" placeholder="Họ tên đầy đủ của bạn" name="name"
                   type="text">

        <div class="clear"></div>
        <span name="name_error" class="error"></span>
        </p>
        <?php /* ?>
        <p class="fieldset">
            <i class="image-replace fa fa-user"></i>
            <input class="full-width has-padding has-border" placeholder="Tên tài khoản của bạn" name="username"
                   type="text">

        <div class="clear"></div>
        <span name="username_error" class="error"></span>
        </p>
 <?php */ ?>
        <p class="fieldset">
            <i class="image-replace fa fa-envelope-o"></i>
            <input id="signin-email" class="full-width has-padding has-border" placeholder="<?php echo lang('your_email') ?>"
                   name="email" type="email">

        <div class="clear"></div>
        <span name="email_error" class="error"></span>
        </p>


        <p class="fieldset">
            <i class="image-replace fa fa-key"></i>
            <input id="signin-password" class="full-width has-padding has-border" placeholder="<?php echo lang('password') ?>"
                   name="password" type="password" value="">
            <a href="#0" class="hide-password">Hide</a>

        <div class="clear"></div>
        <span name="password_error" class="error"></span>
        </p>


        <p class="fieldset">
            <i class="image-replace fa fa-key"></i>
            <input id="confirm-password" class="full-width has-padding has-border"
                   placeholder="<?php echo lang('confirm_password') ?>" name="password_repeat" type="password" value="">

        <div class="clear"></div>
        <span name="password_repeat_error" class="error"></span>
        </p>
        <?php /* ?>
        <p class="fieldset">
            <i class="image-replace fa fa-shield"></i>
            <input id="signin-pin" class="full-width has-padding has-border" placeholder="Mật khẩu cấp 2"
                   name="pin" type="password" value="">
            <a href="#0" class="hide-password">Hide</a>

        <div class="clear"></div>
        <span name="pin_error" class="error"></span>
        </p>


        <p class="fieldset">
            <i class="image-replace fa fa-shield"></i>
            <input id="confirm-pin_repeat" class="full-width has-padding has-border"
                   placeholder="Xác nhận mật khẩu cấp 2" name="pin_repeat" type="password" value="">

        <div class="clear"></div>
        <span name="pin_repeat_error" class="error"></span>
        </p>
        <p class="fieldset">
            <i class="image-replace fa fa-user"></i>
            <input class="full-width has-padding has-border" placeholder="Tên của bạn" name="name"
                   type="text">

        <div class="clear"></div>
        <span name="name_error" class="error"></span>
        </p>
        <p class="fieldset">
            <i class="image-replace fa fa-phone"></i>
            <input class="full-width has-padding has-border" placeholder="Điện thoại của bạn" name="phone"
                   type="text">

        <div class="clear"></div>
        <span name="phone_error" class="error"></span>
        </p>
        <?php */ ?>
        <?php
        if (!isset($captcha))       $captcha = site_url('captcha');
        $_id_register = random_string('unique'); ?>

        <p class="fieldset">

        <div class="col-md-8 p0">
            <i class="image-replace fa fa-eye"></i>
            <input class="full-width has-padding has-border" placeholder="<?php echo lang('security_value') ?>" name="security_code"
                   type="text">
        </div>
        <div class="col-md-4 p0">
            <div class="ml5 mb5">
                <img id="<?php echo $_id_register; ?>" src="<?php echo $captcha; ?>" _captcha="<?php echo $captcha; ?>"
                     class="dInline captcha">
                <a href="#reset" onclick="change_captcha('<?php echo $_id_register; ?>'); return false;" class="dInline"
                   title="Reset captcha">
                    <i class="glyphicon glyphicon-repeat"></i>
                </a>
            </div>
        </div>

        <div class="clearfix"></div>
        <span name="security_code_error" class="mt3 error"></span>
        </p>

        <p class="fieldset"><br>
            <input type="checkbox" name="rule" value="1"> <b><?php echo lang('accept_term_of_use') ?></b>
        <div class="clearfix"></div>
        <span name="rule_error" class="error"></span>
        </p>


        <div class="clearfix"></div>
        <p class="fieldset mt10">
            <input class="full-width has-padding" type="submit" value="Đăng ký">
        </p>
    </form>
    <?php else: ?>
        <div class="alert alert-danger well p40">Not available</div>

    <?php endif; ?>
</div>

