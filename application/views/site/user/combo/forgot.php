        <!-- Form forgot-->
        <div id="cd-reset-password">
            <p class="cd-form-message"><?php echo lang('title_forgot').'? '.lang('type_email_to_send_new_pass') ?>.</p>

            <form class="cd-form form_action" action="<?php echo $user->_url_forgot; ?>">
                <p class="fieldset">
                    <i class="image-replace fa fa-envelope-o"></i>
                    <input class="full-width has-padding has-border" name="email_valid" type="email" placeholder="E-mail">
                <div class="clear"></div>
                <span name="email_valid_error" class="error"></span>
                </p>

                <?php
                if (!isset($captcha))       $captcha = site_url('captcha');
                $_id_forgot = random_string('unique'); ?>

                <p class="fieldset">

                <div class="col-md-8 p0">
                    <i class="image-replace fa fa-eye"></i>
                    <input class="full-width has-padding has-border" placeholder="<?php echo lang('security_value') ?>" name="security_code"
                           type="text">
                </div>
                <div class="col-md-4 p0">
                    <div class="ml5 mb5">
                        <img id="<?php echo $_id_forgot; ?>" src="<?php echo $captcha; ?>" _captcha="<?php echo $captcha; ?>"
                             class="dInline captcha">
                        <a href="#reset" onclick="change_captcha('<?php echo $_id_forgot; ?>'); return false;" class="dInline"
                           title="Reset captcha">
                            <i class="glyphicon glyphicon-repeat"></i>
                        </a>
                    </div>
                </div>
                <div class="clear"></div>&nbsp;
                <span name="security_code_error" class="error"></span>
                </p>
                <div class="clear"></div>
                <p class="fieldset mt10">
                    <input class="full-width has-padding" type="submit" value="Reset mật khẩu">
                </p>
            </form>
            <p class="cd-form-bottom-message"><a href="#0"><?php echo lang('back_to_login') ?></a></p>
        </div>
