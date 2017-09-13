<?php   if(mod("user")->setting('login_auth_allow') && (setting_get('config-facebook_oauth_id') || setting_get('config-google_oauth_id'))):  ?>
    <div class="forgot-password-link clearfix">
        <p class="sub-title">
            <?php echo $title ?> với tài khoản mạng xã hội
        </p>
        <div class="form-control-wrapper social-area" style="display: block;">
            <div class="row">
                <?php   if(setting_get('config-facebook_oauth_id')):  ?>

                    <div class="col-md-6">
                        <a class="btn-facebook" href="<?php echo  $user->_url_login_facebook ?>">
                            <i class=" fa fa-facebook" aria-hidden="true"></i>
                            <span >Facebook</span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php   if(setting_get('config-google_oauth_id')):  ?>
                    <div class="col-md-6">
                        <a class="btn-google" href="<?php echo  $user->_url_login_google ?>">
                            <i class="fa fa-google-plus" aria-hidden="true"></i>
                            <span >Google+</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

