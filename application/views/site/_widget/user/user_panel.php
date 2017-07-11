<?php
/*
$make_menu = function () use ($menu_items, $item_cur) {
    ob_start(); ?>
    <div class="list-group">
        <?php    foreach ($menu_items as $item => $url) {     ?>
            <a href="<?php echo $url ?>"
               class="list-group-item  <?php if ($item == $item_cur) echo 'active'; ?>"
                ><?php echo lang('user_panel_' . $item) ?></a>
            <?php     }      ?>
    </div>

    <?php return ob_get_clean();
};

echo macro('mr::box')->panel([
    'title'   => 'Bảng điều khiển',
    'content' => $make_menu()
]);*/
//pr($user);
?>
<?php /*  ?>
<div id="user-short">
    <img src="<?php echo $user->avatar->url_thumb ?> "         class="user__avatar">
    <hgroup class="tooltip-container">
        <h2><?php echo $user->name; ?></h2>
							<span class="tooltip tooltip-neutral bottom">
								<span class="tooltip-inner">
									<?php echo $user->name; ?>
								</span>
							</span>

        <h3></h3>
    </hgroup>
</div>
<?php */ ?>

<div class="nav">
    <li class=" cai-dat">
        <a href="<?php echo site_url('my-account') ?>">
            <!--<i class="fa fa-user"></i>--> <?php echo lang('user_panel_my_account') ?></a>
    </li>
    <li class=" dang-ky">
        <a href="<?php echo site_url('my-balance') ?>"><!--<i
                        class="fa fa-history"></i>--> <?php echo lang('user_panel_my_balance') ?></a>
    </li>
    <li class=" log-out">
        <a href="<?php echo $user->_url_logout; ?>"
            ><!--<i class="fa fa-share"></i> --><?php echo lang('button_logout'); ?></a>
    </li>
</div>
