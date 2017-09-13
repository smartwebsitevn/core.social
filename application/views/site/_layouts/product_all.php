<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php //widget('site')->head(["css"=>"page_home"]); ?>
	<?php widget('site')->head(); ?>
</head>
<body >
<div class="wrapper">
	<?php echo $header; ?>
	<!-- MAIN -->
	<div class="site-main">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<?php //echo widget('product')->filter([],"top") ?>
					<div class="block-categories">
						<div class="block-title heading-opt1">
							<strong class="title">Danh mục khóa học</strong>
						</div>
						<div class="block-content">
							<ul class="list-categories">
								<?php echo model('product_cat')->get_tree_custom(); ?>
							</ul>
						</div>
					</div>

				</div>
				<div class="col-md-9">
					<?php widget("site")->breadcrumbs() ?>
					<div class="block-khoahoc">
						<div class="block-content">
							<div class="owl-carousel carousel-khoahoc2">
								<?php
								$category_id=isset($category)?$category->id:false;
								widget('product')->same_cat($category_id,['feature'=>'1'], 'slide'); ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="container">
			<?php //echo $content_top; ?>
			<?php echo $content; ?>
			<?php echo $content_bottom; ?>
		</div>
	</div>
	<?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

