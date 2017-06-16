
<?php
	$setting = function($key, $default = null) use ($widget)
	{
		return array_get($widget->setting, $key, $default);
	};
?>

<div class="t-box">
	<div class="box-title">
		<h6><?php echo $widget->name; ?></h6>
	</div>
	
	<div class="box-content p0">
		
		<div id="fb-root"></div>
		
		<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		
		<div class="fb-like-box"
			data-href="<?php echo $setting('href'); ?>"
			data-width="<?php echo $setting('width'); ?>"
			data-height="<?php echo $setting('height'); ?>"
			data-colorscheme="<?php echo $setting('colorscheme'); ?>"
			data-show-faces="<?php echo $setting('show-faces') ? 'true' : 'false'; ?>"
			data-header="<?php echo $setting('header') ? 'true' : 'false'; ?>"
			data-stream="<?php echo $setting('stream') ? 'true' : 'false'; ?>"
			data-show-border="<?php echo $setting('show-border') ? 'true' : 'false'; ?>"
		></div>
		
		<div class="clear pb10"></div>
	</div>
</div>

