<?php
$asset = public_url();
$asset_theme = $asset . '/site/theme/';
$_menu_data = function ($menu, $menu_name = '', $a_class = '') {
    ob_start() ?>


    <?php if (isset($menu) && $menu): ?>
        <?php foreach ($menu as $item):
            $has_sub = (isset($item->_sub) && $item->_sub) ? 1 : 0;

            $actice = $item->_is_active ? 'active' : '';
            $item->url = handle_content($item->url, 'output');
            $target = $item->target ? ' target="' . $item->target . '" ' : '';
            $icon = $item->icon ? '<i class="fa fa-' . $item->icon . '"></i>' : '';
            $nofollow = $item->nofollow ? ' rel="nofollow" ' : '';

            if (!$has_sub && $item->holder) {
                $tmp = explode(':', $item->holder);
                if (count($tmp) >= 2 && $tmp[1]) {
                    $ids = explode(',', $tmp[1]);
                    $list_sub = model($tmp[0])->filter_get_list(['id' => $ids, 'show' => 1]);
                    // pr_db();
                    if ($list_sub) {

                        $has_sub = true;
                    }
                }
            }
            ?>
            <li class="<?php echo $actice; ?>  <?php if ($has_sub) echo "dropdown" ?>  ">
                <a href="<?php echo $item->url; ?>"
                   class="  <?php echo $a_class . ' ' . $actice ?> <?php if ($has_sub) echo "dropdown-toggle" ?> "
                    <?php if ($has_sub) echo 'data-toggle="dropdown"' ?>
                    <?php echo $target . ' ' . $nofollow ?>>
                    <?php echo $icon ?><?php echo $item->title; ?>
                </a>

                <?php if ($has_sub): ?>
                    <ul class="dropdown-menu ">
                        <?php if ($item->_sub): ?>
                            <?php foreach ($item->_sub as $row) { ?>
                                <li><a href="<?php echo $row->url ?>"><?php echo $row->title ?></a></li>
                            <?php } ?>
                        <?php else: ?>
                            <?php foreach ($list_sub as $row) {
                                $row = mod($tmp[0])->_url($row);
                                ?>
                                <li><a href="<?php echo $row->_url_view ?>"><?php echo $row->name ?></a></li>
                            <?php } ?>
                        <?php endif; ?>

                    </ul>
                <?php endif; ?>
            </li>

        <?php endforeach; ?>
    <?php endif; ?>

    <?php /* ?>
		</ul>
	</div>
 	<?php */ ?>

    <?php return ob_get_clean();

};
?>
<div id="header" class="auto">
    <div class="container">
        <span data-action="toggle-navbar-left" class="nav-toggle-menu nav-toggle-navbar-left"><span>Menu</span></span>

        <div class="nav-menu navbar-left">
            <span data-action="close-nav" class="close-nav"><span>close</span></span>
            <ul class="nav">
                <?php echo $_menu_data($menu) ?>
            </ul>
        </div>

        <div class="logo">
            <a href="<?php echo site_url() ?>">
                <img src="<?php echo widget("site")->setting_image() ?>" alt="logo"/>
            </a>
        </div>


        <a href="<?php echo site_url('product_post') ?>" title="Đăng tin" class="btn btn-default btn-round btn-post">
            Post
        </a>
        <?php
        $user_current = user_get_account_info();
        if ($user_current)
            $user_current = mod('user')->add_info($user_current);

        ?>

        <div class="user-avatar nav-toggle-navbar-right" data-action="toggle-navbar-right" href="#0">
            <div class="item-photo">
                <span class="item-img">
                    <?php if ($user_current && $user_current->avatar): ?>
                        <img src="<?php echo $user_current->avatar->url_thumb ?> " class="avatar"/>
                    <?php else: ?>
                        <i class="pe-7s-user avatar"></i>
                    <?php endif; ?>
                </span>
                <?php if ($user_current): ?>
                    <?php if ($user_current->user_group_type == 'user_manager'): ?>
                        <span class="item-label label-user-manager">  <i class="pe-7s-helm"></i></span>
                    <?php elseif ($user_current->user_group_type == 'user_active'): ?>
                        <span class="item-label label-user-active">  <i class="pe-7s-medal"></i></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="nav-menu navbar-right">

            <span data-action="close-nav" class="close-nav"><span>close</span></span>
            <ul class="nav login  pull-right">
                <?php widget("user")->account_panel() ?>
                <?php //widget('site')->lang(); ?>
            </ul>
        </div>
    </div>
</div>
