<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <?php widget('site')->head(["css" => "page_user"]); ?>
</head>
<body class="user-account">
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-2 sidebar">
                            <?php echo widget('user')->user_panel() ?>
                </div>
                <div class="col-md-10 main-content">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

