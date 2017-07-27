<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(); ?>
</head>
<body  >
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <div class="container">
            <?php echo $content; ?>
        </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

