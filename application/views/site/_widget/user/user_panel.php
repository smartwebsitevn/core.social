<?php
/*
$make_menu = function () use ($menu_items, $item_cur) {
    ob_start(); ?>
    <style type="text/css">

        a.list-group-item {
            background-color: #fff;
            border: none;
        }

        a.list-group-item.active:hover,
        a.list-group-item.active:focus,
        a.list-group-item.active {
            background-color: #ae3b39;
            color: #fff;
        }
    </style>
    <div class="list-group">

        <?php
        foreach ($menu_items as $item => $url) {
            ?>

            <a href="<?php echo $url ?>"
               class="list-group-item  <?php if ($item == $item_cur) echo 'active'; ?>"
                ><?php echo lang('user_panel_' . $item) ?></a>

            <?php
        }
        ?>

    </div>

    <?php return ob_get_clean();
};

echo macro('mr::box')->panel([
    'title'   => 'Bảng điều khiển',
    'content' => $make_menu()
]);*/
//pr($user);

?>
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
<div class="list-group">
    <?php foreach ($menu_items as $item => $url): ?>
        <div class="list-group-item <?php if ($item == $item_cur) echo 'on active'; ?>">
            <a href="<?php echo $url ?>"><?php echo lang('user_panel_' . $item) ?></a>
        </div>
    <?php endforeach; ?>

</div>
