
<?php
$setting = function($key, $default = null) use ($widget)
{
	return array_get($widget->setting, $key, $default);
};
?>

<div class="row">
	<div id="social-top">
		<div class="content">

			<div id="fb-root"></div>
			<style>
				.fb_iframe_widget, .fb_iframe_widget span, .fb_iframe_widget span iframe[style] {
					width: 100% !important;
				}
			</style>
			<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));
			</script>

			<div id="likebox-wrapper">
				<div class="fb-like-box"
				<?php /* ?>
 				 data-width="<?php echo $setting('width'); ?>"
 				 data-height="<?php echo $setting('height'); ?>"
				<?php  */?>
				 data-href="<?php echo $setting('href'); ?>"
				 data-colorscheme="<?php echo $setting('colorscheme'); ?>"
				 data-show-faces="<?php echo $setting('show-faces') ? 'true' : 'false'; ?>"
				 data-header="<?php echo $setting('header') ? 'true' : 'false'; ?>"
				 data-stream="<?php echo $setting('stream') ? 'true' : 'false'; ?>"
				 data-show-border="<?php echo $setting('show-border') ? 'true' : 'false'; ?>"
				></div>
			</div>
			<!--<div class="clear pb10"></div>-->
		</div>
	</div>
</div>

