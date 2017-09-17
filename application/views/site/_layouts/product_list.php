<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php widget('site')->head(); ?>
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
							<?php echo widget('product')->filter([], "sidebar") ?>
							<?php //widget('site')->notice('site_intro');?>
							<?php if(!user_is_login()): ?>
							<?php $notice = mod('notice')->get('site_intro'); ?>
							<?php if($notice): ?>
								<div class="panel">
									<div class="panel-heading">
										<?php echo $notice->name ?>
									</div>
									<div class="panel-body">
										<?php echo $notice->content ?>

									</div>
								</div>
							<?php endif; ?>
							<?php endif; ?>
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

