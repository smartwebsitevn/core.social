<!DOCTYPE html>
<html>
<head>
	<?php widget('site')->head(["css" => ["pages/page_question_answer"]]); ?>
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