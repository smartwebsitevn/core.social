<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php //widget('site')->head(["css"=>"page_home"]); ?>
	<?php widget('site')->head(); ?>
</head>
<body >
<?php //view('tpl::_widget/site/header') ?>
<?php echo $header; ?>
<section class="content">
	<div class="container">
		<div class="row">
			<!-- sidebar -->
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
				<aside>
					<h3 class="gv-title">Bộ Môn</h3>
					<div class="nav-aside">
						<ul>
							<?php foreach($subjects as $row): ?>
							<li class="active">
								<a href="<?php echo site_url("author")."?subject_id=".$row->id ?>" ><?php echo  $row->name?></a>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</aside>
				<?php echo $left; ?>
			</div>
			<!-- content gv -->
			<div class="col-lg-9 col-md-9 col-sm-9 main-gv">
				<?php echo $content_top; ?>
				<?php echo $content; ?>
				<?php echo $content_bottom; ?>
			</div>
		</div>

	</div>
</section>
<?php echo $footer; ?>
<?php view('tpl::_widget/site/js') ?>
</body>

</html>

