<?php
echo macro('mr::box')->box([
    'title' => lang('title_unlock'),
    'content' => macro()->info([
        lang('account') => $user->username,
        lang('email') => $user->email,
        lang('full_name') => $user->name,
        lang('level_current') => lang("user_level_" . $user->level),
    ]),

]);


$_data_notice = function () {
    ob_start(); ?>
    <div name="user_unlock_error" class=" form-error alert alert-danger" style="display: none"></div>
    <?php return ob_get_clean();
};
echo macro('mr::form')->form([
    'action' => $action,
    //'title'      => lang('title_unlock'),
    //'btn_submit' => lang('button_activation'),
    'notice' => $_data_notice(),
    'rows' => [
        [
            'name' => lang('user_fee_unlock_account'),
            'param' => '_', 'type' => 'static',
            'value' => macro()->label($fee_unlock_format, 'danger f15'),
        ],
        macro('mr::form')->captcha($captcha),
    ],
]);