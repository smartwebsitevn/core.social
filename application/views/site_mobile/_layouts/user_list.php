<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php widget('site')->head(["css"=>"page_user"]); ?>
</head>
<body class="page-product-list" >
<div class="wrapper">
	<?php echo $header; ?>
	<!-- MAIN -->
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="main-content">
					<?php echo widget('user')->filter([], "top") ?>
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>
	<?php widget('site')->footer_navi('user_list'); ?>


</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

