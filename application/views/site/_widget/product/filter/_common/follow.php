<?php
$user = user_get_account_info();
if ($user):
    ?>
    <?php
    $users = model('user')->followers($user->id);
   // pr_db($users);
    ?>
    <?php if ($users): ?>
    <div>
        <h5 style="border-bottom:1px solid #ccc; padding:10px 5px  ">
            Đang theo dõi
        </h5>

        <div class="slimscroll">
        <?php
        foreach($users as $row){
            $row = mod('user')->add_info($row);
        }
        //pr($users);
        ?>
            <?php widget('user')->display_list($users, 'sidebar_follow') ?>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>