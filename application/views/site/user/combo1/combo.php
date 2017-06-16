<?php
$user = new stdClass();
$user = site_create_url('account', $user);
$user->_url_login_facebook = site_url('oauth/facebook');
$user->_url_login_google = site_url('oauth/google');
$data['user'] = $user;
$data['captcha'] = site_url('captcha/four');
$_id = '_'.random_string('unique');

?>
<link rel="stylesheet" href="<?php echo public_url('site/css') ?>/sign.css">
<div id="<?php echo $_id ?>" class="cd-user-combo">
    <div class="cd-user-combo-container">
        <ul class="list-unstyled cd-switcher">
            <li><a href="#"><?php echo lang('login') ?></a></li>
            <li><a href="#"><?php echo lang('register') ?></a></li>
        </ul>

        <!-- Form Login-->
        <?php view('tpl::user/combo/login', $data) ?>
        <!-- Form register-->
        <?php view('tpl::user/combo/register', array($data)) ?>
        <!-- Form forgot-->
        <?php view('tpl::user/combo/forgot', array($data)) ?>
        <a href="#0" class="cd-close-form"><?php echo lang('close') ?></a>
    </div>
</div>

<?php view('tpl::user/combo1/js', array('_id'=>$_id)) ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#account_panel').remove();
        var $form_combo = $('#<?php echo $_id ?>'),

            $form_login = $form_combo.find('#cd-login')

             $form_combo_tab = $form_combo.find('.cd-switcher'),
            $tab_login = $form_combo_tab.children('li').eq(0).children('a'),
            $tab_signup = $form_combo_tab.children('li').eq(1).children('a'),
                 $form_login.addClass('is-selected');
             $tab_login.addClass('selected');
    });
</script/>
