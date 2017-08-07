<!DOCTYPE html>
<html>
<head>
	<?php //widget('site')->head(["css"=>"page_home"]); ?>
	<?php widget('site')->head(["css"=>"page_social"]); ?>
</head>
<body class="page-product-list" >
<div class="wrapper">
	<?php echo $header; ?>
	<!-- MAIN -->
	<div id="main">
		<div class="container">
			<?php
			/*$_cat_filter = [];
			if (isset($category)) {
				if ($category->parent_id)
					$_cat_filter['parent_id'] = $category->parent_id;
				else
					$_cat_filter['parent_id'] = $category->id;
			} else {
				$_cat_filter['parent_id'] = 0;
				echo widget("product")->filter($_cat_filter,"sidebar")
			}*/
			?>
			<?php echo widget('product')->filter([], "top") ?>
			<?php //echo $content_top; ?>
			<?php echo $content; ?>
			<?php //echo $content_bottom; ?>
		</div>
	</div>
	<?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

