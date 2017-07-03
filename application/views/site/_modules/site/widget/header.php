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
<div id="header">
    <div class="container">
        <div class="logo pull-left">
            <a href="<?php echo site_url() ?>">
                <img src="<?php echo widget("site")->setting_image() ?>" alt="logo"/>
            </a>
        </div>
        <span data-action="toggle-navbar-left" class="nav-toggle-menu nav-toggle-navbar-left"><span>Menu</span></span>

        <div class="nav-menu navbar-left">
            <span data-action="close-nav" class="close-nav"><span>close</span></span>
            <ul class="nav">
                <?php echo $_menu_data($menu) ?>
            </ul>
        </div>
        <span data-action="toggle-navbar-right" class="nav-toggle-menu nav-toggle-navbar-right"><span>Menu</span></span>

        <div class="nav-menu navbar-right">
            <span data-action="close-nav" class="close-nav"><span>close</span></span>
            <ul class="nav login  pull-right">
                <li class="dropdown">
                    <a href="<?php echo site_url('product_post') ?>" title="Đăng tin" class="btn btn-default">
                        Đăng tin
                    </a>
                </li>
                <?php widget("message")->newest() ?>
                <?php widget("user_notice")->newest() ?>
                <?php /* if (!mod("product")->setting('turn_off_function_order')): ?>
                    <li id="product-my-favorited" class="dropdown">
                        <?php widget("product")->owner('favorited') ?>

                    </li>
                    <?php if (!mod("product")->setting('product_order_quick')): ?>

                        <li id="product-cart-mini" class="dropdown">
                            <?php widget("product")->cart(null, 'cart_mini') ?>
                        </li>
                    <?php endif; ?>

                <?php endif; */ ?>
                <?php widget("user")->account_panel() ?>
                <?php //widget('site')->lang(); ?>

            </ul>
        </div>
    </div>
</div>
