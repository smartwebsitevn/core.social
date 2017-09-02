<!DOCTYPE html>
<html>
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
				<div class="col-md-3 sidebar ">
					<div class="sticky-element" data-spacing="65" data-limiter="#footer">
						<div class="slimscroll_" data-height="90vh">
							<?php echo widget('user')->filter([], "sidebar") ?>
						</div>
					</div>
				</div>
				<div class="col-md-6 main-content">
					<?php echo $content; ?>
				</div>
				<div class="col-md-3 sidebar">
					<?php widget('user')->adsed(null, 'sidebar_adsed') ?>

				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

