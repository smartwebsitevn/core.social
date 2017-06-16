<?php
/*echo macro('mr::box')->box([
    'title' => lang('title_upgrade'),
    'content' => macro()->info([
        lang('account') => $user->username,
        lang('email') => $user->email,
        lang('full_name') => $user->name,
        lang('level_current') => lang("user_level_" . $user->level),
        //lang('level_will_upgrade') => macro()->label(lang("level_upgrade"),'success '),
        //lang('level_upgrade_fee') =>macro()->label(lang("level_upgrade_fee"),'danger f15') ,
    ]),

]);*/




$_data = function () use ($user,$action, $level_upgrade, $level_upgrade_fee_format,$captcha) {
    $_data_notice = function () {
        ob_start(); ?>
        <div name="user_upgrade_error" class=" form-error alert alert-danger" style="display: none"></div>
        <?php return ob_get_clean();
    };
    ob_start(); ?>

    <div class="row">
        <div class="col-md-8">
            <?php
            $rows=[];
            $rows[]= [
                'name' => lang('level_will_upgrade'),
                'param' => '_', 'type' => 'static',
                'value' => macro()->label(lang("user_level_" . $level_upgrade), 'success '),
            ];

            $rows[]=   [
                'name' => lang('level_upgrade_fee'),
                'param' => '_', 'type' => 'static',
                'value' => macro()->label($level_upgrade_fee_format, 'danger f15'),
            ];
            if($user->expired_time>0){


            $rows[]= [
                'name' => lang('time_expired_upgrade'),
                'param' => '_', 'type' => 'static',
                'value' =>  widget('user')->countdown($user),
            ];
            }

            $rows[]=  macro('mr::form')->captcha($captcha);


            echo macro('mr::form')->form([
                'action' => $action,
                //'title'      => lang('title_upgrade'),
                //'btn_submit' => lang('button_activation'),
                'notice' => $_data_notice(),
                'rows' => $rows
            ]);
            ?>
        </div>

        <div class="col-md-4">
            <div class="alert alert-info">
                <?php echo module_get_setting('user','info_help_upgrade'); ?>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
};
if ($can_upgrade) {
echo macro('mr::box')->box([
    'title' => lang('title_upgrade'),
    'body' => $_data(),
]);

};

?>
