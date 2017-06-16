<?php
$user = new stdClass();
$user = site_create_url('account', $user);
$user->_url_login_facebook = site_url('oauth/facebook');
$user->_url_login_google = site_url('oauth/google');
$data['user'] = $user;
$data['captcha'] = site_url('captcha/four');
//$_id = '_'.random_string('unique');
$_id = "modal-user-login";
?>
    <link rel="stylesheet" href="<?php echo public_url('site/css') ?>/sign.css">
    <div id="<?php echo $_id ?>" class="cd-user-combo cd-user-modal modal fade  " style="display: none" tabindex="-1"
         role="dialog">
        <div class="cd-user-combo-container">
            <ul class="list-unstyled cd-switcher">
                <li><a href="#"><?php echo lang('login') ?></a></li>
                <?php //endif; ?>

                <li><a href="#"><?php echo lang('register') ?></a></li>
            </ul>
            <!-- Form Login-->

                <?php view('tpl::user/combo1/login', $data) ?>


            <!-- Form register-->

                <?php view('tpl::user/combo1/register', array($data)) ?>


            <!-- Form forgot-->
            <?php view('tpl::user/combo1/forgot', array($data)) ?>
            <a href="#0" class="cd-close-form"><?php echo lang('close') ?></a>
        </div>
    </div>
<?php view('tpl::user/combo1/js', array('_id' => $_id)) ?>