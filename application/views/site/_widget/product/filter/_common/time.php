<?php
$user = user_get_account_info();
if ($user):
    ?>
    <?php
    $input['where']['us.action'] = 'subscribe';
    $input['where']['us.table'] = 'user';
    $input['where']['us.user_id'] = $user->id;
    $input['join'] = array(array('user_storage us', 'us.table_id = user.id'));
    //$input['limit'] = array(0,2);
    $filter = array();
    $users = mod('user')->get_list($filter, $input);
    //pr_db($users);
    ?>
    <?php if ($users): ?>
    <div>
        <h5 style="border-bottom:1px solid #ccc; padding:10px 5px  ">
            Đang theo dõi
        </h5>

        <div class="slimscroll">

            <?php widget('user')->display_list($users, 'sidebar_follow') ?>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>