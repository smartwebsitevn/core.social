<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <?php //widget('site')->head(["css"=>"page_home"]); ?>
    <?php widget('site')->head(); ?>
</head>
<body  >
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <div class="container">
            <?php echo $content_top; ?>
            <?php echo $content; ?>
            <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

