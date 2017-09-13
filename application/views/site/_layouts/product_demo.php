<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"   xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <?php widget('site')->head(["css" => ["page_demo"],"js" => ["demo"]]); ?>
</head>
<body class="page-product-demo">
    <?php echo $content; ?>
    <?php view('tpl::_widget/site/js') ?>
</body>
</html>



