<div class="user_authentication">
    <?php
    echo macro('mr::form')->form([
        'action' => $action,
        'title' => lang('title_forgot_result'),
        'btn_submit' => lang('button_update'),
        'rows' => [
            [
                'param' => 'password_old',
                'type' => 'custom',
                'name' => lang('password_new'),
                'html' => '<h4>' . $password_new . '</h4>',
            ],
            macro('mr::form')->row_title(lang('block_change_pass')),
            [
                'param' => 'password',
                'type' => 'password',
                'name' => lang('password_new'),
                'req' => true,
            ],
            [
                'param' => 'password_repeat',
                'type' => 'password',
                'req' => true,
            ],
        ],

    ]);
    ?>
</div>