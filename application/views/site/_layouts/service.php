<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(["css" => "page_service"]); ?>
</head>
<body class="service-page">
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 sidebar left-sidebar mobile-hide-sidebar">
                        <?php if (isset($service_list) && $service_list):
                            ?>
                        <aside>
                            <h3><?php echo $category->name ?></h3>
                            <ul class="nav">
                                    <li><a class="<?php echo  !isset($info)? ' active' : '' ?>" href="<?php echo $category->_url_view?>">Tất cả dịch vụ</a></li>
                                    <?php foreach ($service_list as $c):;
                                        $active = (isset($info) && $info->id == $c->id) ? ' active' : '';
                                        ?>
                                        <li ><a class="<?php echo $active ?>"
                                                href="<?php echo $c->_url_view ?>"><?php echo $c->name ?></a></li>
                                    <?php endforeach; ?>
                            </ul>
                        </aside>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9 main-content">
                        <?php //echo $content_top; ?>
                        <?php echo $content; ?>
                        <?php //echo $content_bottom; ?>
                    </div>
                </div>
            </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

