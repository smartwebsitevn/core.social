<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(["css" => "page_user"]); ?>
</head>
<body class="user-page">
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <?php t('view')->load('tpl::user_page/top') ?>
        <?php echo $content; ?>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

