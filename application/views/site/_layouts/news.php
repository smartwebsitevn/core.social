<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php //widget('site')->head(["css"=>"page_home"]); ?>
	<?php widget('site')->head(); ?>
</head>
<body  >
<div class="wrapper">
	<?php echo $header; ?>
	<!-- MAIN -->
	<div id="main">
		<?php echo $content_top; ?>

		<div class="container">
			<?php //widget("site")->message(); ?>
			<div class="box-wraper row">
				<div class="box-left col-sm-8 col-md-8">
					<?php  echo $content; ?>
				</div>
				<div class="box-right col-sm-4 col-md-4">
					<?php echo $right; ?>
				</div>
			</div>

		</div>
		<?php echo $content_bottom; ?>

	</div>
	<?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

