<?php
$asset = public_url();
$asset_style = $asset . '/site/style/';
/*
$_menu_data_bangoc = function ($menu, $menu_name,$a_class='') {
	ob_start() ?>
	<div class="col-xs-6 col-md-3">
		<div class="widget widgetFooter">
			<h4 class="widgettitle"><?php echo (isset($menu_name) && $menu_name) ? $menu_name->name : '' ?></h4>
			<ul>

				<?php if (isset($menu) && $menu): ?>
					<?php foreach ($menu as $item):
						$actice = $item->_is_active ? 'active' : '';
						$item->url = handle_content($item->url, 'output');
						$target = $item->target ? ' target="' . $item->target . '" ' : '';
						$icon = $item->icon ? '<i class="fa fa-' . $item->icon . '"></i>' : '';
						$nofollow = $item->nofollow ? ' rel="nofollow" ' : '';
						?>
						<li><a href="<?php echo $item->url; ?>"  class="<?php echo $a_class . ' '.$actice ?> " <?php echo $target . ' ' . $nofollow ?>>
								<?php echo $icon ?> <?php echo $item->title; ?>
							</a>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php return ob_get_clean();
};
*/
$_menu_data = function ($menu, $menu_name, $a_class = '') {
    ob_start() ?>
    <?php if (isset($menu) && $menu): ?>
        <h3 class="title"><?php echo (isset($menu_name) && $menu_name) ? $menu_name->name : '' ?></h3>
        <ul class="list-unstyle">
            <?php foreach ($menu as $item):
                $actice = $item->_is_active ? 'active' : '';
                $item->url = handle_content($item->url, 'output');
                $target = $item->target ? ' target="' . $item->target . '" ' : '';
                $icon = $item->icon ? '<i class="fa fa-' . $item->icon . '"></i>' : '';
                $nofollow = $item->nofollow ? ' rel="nofollow" ' : '';
                ?>
                <li><a href="<?php echo $item->url; ?>"
                       class="<?php echo $a_class . ' ' . $actice ?> " <?php echo $target . ' ' . $nofollow ?>>
                        <?php echo $icon ?><?php echo $item->title; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php return ob_get_clean();
};
$_image_data = function ($image) {
    ob_start() ?>
    <?php if (isset($image->link) && $image->link): ?>
        <a href="<?php echo $image->link ?>"><img src="<?php echo $image->url ?>" alt="img"></a>
    <?php else: ?>
        <img src="<?php echo $image->url ?>" alt="img">
    <?php endif; ?>
    <?php return ob_get_clean();
}
?>
<div id="footer">
    <?php //$this->widget->site->ads('footer','tpl::_widget/ads/footer'); ?>
    <div class="footer-center">
        <div class="container">

            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo $_menu_data($menu1, $menu1_name) ?>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo $_menu_data($menu2, $menu2_name) ?>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo $_menu_data($menu3, $menu3_name) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom container">
        <?php widget('site')->follow(); ?>
        <?php if($widget->setting["copyright"]): ?>
        <p class="text-center"><?php echo $widget->setting["copyright"] ?></p>
        <?php endif; ?>
        <p style="text-align: center;">
            Powered by <strong><a href="http://smartwebsite.vn/" target="_blank">SmartWebsite.vn</a></strong>
        </p>
    </div>
    <script type="text/javascript">
        function add_text_social(txt) {
            $(".text-social").text(txt);
        }
    </script>
</div>
<?php //echo $widget->setting['js']; ?>

