<?php

/**
 * Tai file thanh phan
 */
t('lang')->load('site/user');


/**
 * Login
 */
$this->register('login', function (array $args = []) {
    $title = array_get($args, 'title', lang('title_login'));
    $action = array_get($args, 'action', site_url('user/login'));
    $btn_submit = array_get($args, 'btn_submit', lang('button_login'));
    $url = mod_url('user', user_get_account_info());
    $user = new stdClass();
    $user = site_create_url('account', $user);
    $user->_url_login_facebook = site_url('oauth/facebook');
    $user->_url_login_google = site_url('oauth/google');
    // pr($url);
    $form = ['action' => $action,
        'title' => $title,
        'btn_submit' => $btn_submit];

    // $form["rows"][]=t('html')->hidden('email');
    if (setting_get('config-facebook_oauth_id')) {
        $form["rows"][] = '
                <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <a href="' . $user->_url_login_facebook . '" class="btn  btn-social btn-facebook"> <i
                                    class="fa fa-facebook"></i> ' . lang('login_by_facebook') . '</a>
                            <?php endif; ?>
                            <a href="' . $user->_url_login_google . '" class="btn  btn-social btn-google-plus">
                                <i class="fa fa-google-plus"></i>' . lang('login_by_google') . '</a>
                        </div>
                    </div>
                ';
    }
    $form["rows"][] = '<div name="login_error" class="alert alert-danger" style="display: none;"></div>';
    $form["rows"][] = [
        'param' => 'email',
        'name' => lang('account'),
        /* 'attr' => [
             'placeholder' => lang('note_login_account'),
             'title' => lang('note_login_account'),
         ],*/
    ];
    $form["rows"][] = [
        'param' => 'password',
        'type' => 'password',
    ];
    $form["rows"][] = macro('mr::form')->captcha();
    $form["rows"][] =
        '
                <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                        <label class="tcb-inline">
                            <input class="tc" checked="checked" name="remember" value="1" type="checkbox">
                            <span class="labels">'.lang('remember_login') . ' - <a href="' . $url->_url_forgot . '">' . lang('button_forgot_password') . '</a>'.'</span>
						</label>
            			<div class="clearfix"></div>
            			<div name="remember_error" class="form-error"></div>
            			</div>
                    </div>

            ';

    /*$form["rows"][] = ['param' => 'remember',
        'type' => 'bool',
        'name' => '',
        'value' => true,
        'values' => lang('remember_login') . ' - <a href="' . $url->_url_forgot . '">' . lang('button_forgot_password') . '</a>',
    ];*/
    return macro('mr::form')->form($form);
});


/**
 * Register
 */
$this->register('register', function (array $args = []) {
    $title = array_get($args, 'title', lang('title_register'));
    $action = array_get($args, 'action', site_url('user/register'));
    $btn_submit = array_get($args, 'btn_submit', lang('button_register'));
    $user = new stdClass();
    $user = site_create_url('account', $user);
    $user->_url_login_facebook = site_url('oauth/facebook');
    $user->_url_login_google = site_url('oauth/google');

    $form = ['action' => $action,
        'title' => $title,
        'btn_submit' => $btn_submit];

    // $form["rows"][]=t('html')->hidden('email');
    if (setting_get('config-facebook_oauth_id')) {
        $form["rows"][] = '
                <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <a href="' . $user->_url_login_facebook . '" class="btn  btn-social btn-facebook"> <i
                                    class="fa fa-facebook"></i> ' . lang('register_by_facebook') . '  </a>
                            <?php endif; ?>
                            <a href="' . $user->_url_login_google . '" class="btn  btn-social btn-google-plus">
                                <i class="fa fa-google-plus"></i> ' . lang('register_by_google') . ' </a>
                        </div>
                    </div>
                ';
    }
    $form["rows"][] = ['param' => 'email',
        'req' => true,
    ];
    $form["rows"][] = [
        'param' => 'username',
        'req' => true,
        /*'attr'	=> [
            'onkeyup' => 'jQuery(this).closest("form").find("[name=email]").val(jQuery(this).val()+"@'.$_SERVER['HTTP_HOST'].'");'
        ],*/
    ];
    $form["rows"][] = [
        'param' => 'password',
        'type' => 'password',
        'req' => true,
    ];
    $form["rows"][] = [
        'param' => 'password_repeat',
        'type' => 'password',
        'req' => true,
    ];
    /* $form["rows"][]=[
         'param' => 'pin',
         'type' => 'password',
         'req' => true,
     ];
     $form["rows"][]=[
         'param' => 'pin',
         'type' => 'password',
         'req' => true,
     ];
     $form["rows"][]=[
         'param' => 'pin_confirm',
         'type' => 'password',
         'req' => true,
     ];
     */
    $form["rows"][] = [
        'param' => 'name',
        'name' => lang('full_name'),
        'req' => true,
    ];
    $form["rows"][] = [
        'param' => 'phone',
        'req' => true,
    ];
    $form["rows"][] = macro('mr::form')->captcha();
    $form["rows"][] = [
        'param' => 'rule',
        'type' => 'bool',
        'name' => '',
        'value' => true,
        'values' => lang('agree_rule'),
    ];


    return macro('mr::form')->form($form);
});
