<?php

$_menu_data = function ($menu, $menu_name,$a_class='') {
	ob_start() ?>
	    <div class="footer-title">
					<p><?php echo (isset($menu_name) && $menu_name) ? $menu_name->name : '' ?></p>
		</div>
	<div class="footer-body">
		<ul>
			<?php if (isset($menu) && $menu): ?>
				<?php foreach ($menu as $item):
					$actice = $item->_is_active ? 'active' : '';
					$item->url = handle_content($item->url, 'output');
					$target = $item->target ? ' target="' . $item->target . '" ' : '';
					$icon = $item->icon ? '<i class="fa fa-' . $item->icon . '"></i>' : '';
					$nofollow = $item->nofollow ? ' rel="nofollow" ' : '';
					?>
					<li><a href="<?php echo $item->url; ?>"  class="  <?php echo $a_class . ' '.$actice ?> " <?php echo $target . ' ' . $nofollow ?>>
							<?php echo $icon ?> <?php echo $item->title; ?>
						</a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
	<?php return ob_get_clean();

}
?>
<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sm-3 col-xs-6">
				<?php echo $widget->setting['copyright'] ?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6">
				<?php echo $_menu_data($menu1, $menu1_name,'white-text') ?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6">
				<?php echo $_menu_data($menu2, $menu2_name,'gray-text') ?>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-6">
				<div class="footer-title">
					<p>Chia sẻ</p>
				</div>
				<div class="footer-body">
					<ul class="footer-socials">
						<li><a href="<?php echo widget("site")->setting("facebook") ?>"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
						<li><a href="<?php echo widget("site")->setting("googleplus") ?>"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
						<li><a href="<?php echo widget("site")->setting("youtube") ?>"><i class="fa fa-youtube-square" aria-hidden="true"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- Js -->
<?php echo $widget->setting['js']; ?>
