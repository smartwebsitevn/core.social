<!DOCTYPE html>
<html>
<head>
    <?php //widget('site')->head(["css"=>"page_home"]); ?>
    <?php widget('site')->head(); ?>
</head>
<body>
<div class="wrapper">
    <?php echo $header; ?>
    <?php echo $slide_home ?>
    <!-- MAIN -->
    <div class="site-main">
        <?php echo $content_top; ?>
        <?php echo $content_bottom; ?>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

