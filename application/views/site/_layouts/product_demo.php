<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(["css" => ["page_demo"],"js" => ["demo"]]); ?>
</head>
<body class="page-product-demo">
    <?php echo $content; ?>
    <?php view('tpl::_widget/site/js') ?>
</body>
</html>



