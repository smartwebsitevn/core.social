<!DOCTYPE html>
<html>
<head>
	<?php widget('site')->head(["css"=>"page_social2"]); ?>
</head>
<body class="page-product-list" >
<div class="wrapper">
	<?php echo $header; ?>
	<!-- MAIN -->
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="col-md-3 sidebar">
					<?php echo widget('product')->filter([], "sidebar") ?>
				</div>
				<div class="col-md-6 main-content">
					<?php echo $content; ?>
				</div>
				<div class="col-md-3 sidebar">

					<div class="panel">
						<div class="panel-heading">
							Được đề xuất
						</div>
						<div class="panel-body slimscroll">
							<?php widget('user')->feature(null, 'sidebar_feature') ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

