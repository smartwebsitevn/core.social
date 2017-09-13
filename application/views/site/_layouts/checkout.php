<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <?php widget('site')->head(["css" => ["page_checkout"]]); ?>
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

