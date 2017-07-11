<?php 
echo macro('mr::form')->form([
    'action'     => current_url(),
    'title'      => lang('title_edit_confirm'),
    'btn_submit' =>  lang('button_confirm'),
    'rows' => [
        mod('user_security')->form($key_confirm)
    ],
]);
?>
