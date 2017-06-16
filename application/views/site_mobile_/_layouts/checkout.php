<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(["css" => ["pages/page_checkout"]]); ?>
</head>
<body >
<?php echo $header; ?>
<?php //echo $slide_home ?>
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

