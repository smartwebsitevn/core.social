<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<?php widget('site')->head(["css" => ["page_question_answer"]]); ?>
</head>
<body id="udemy" class="udemy  pageloaded ud-angular-loaded featured--v5 c_full-width-grid c_link-bar-wrap">
<?php echo $header; ?>
<section id="main-section">
	<div class="container">
		<?php echo $content_top; ?>
		<?php echo $content; ?>
	</div>
</section>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>