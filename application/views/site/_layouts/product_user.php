<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php //widget('site')->head(["css" => ["pages/page_user","pages/page_product_list"]]); ?>
	<?php widget('site')->head(["css" => ["page_user"]]); ?>
</head>
<body class="page-product-user">
<?php //view('tpl::_widget/site/header') ?>
<div class="wrapper">
	<?php echo $header; ?>
	<div class="container">
		<div id="edit-template">
			<div id="main-section" style="padding-top: 0">
				<div class="side-nav fx-lc-sm db-xs">
					<?php echo widget('user')->user_panel() ?>
				</div>
				<div class="form-wrapper">
					<?php echo widget('product')->filter([], "top") ?>
					<div class="p20">
					<?php  echo $content; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>
	<?php view('tpl::_widget/site/js') ?>
</div>
</body>
</html>



