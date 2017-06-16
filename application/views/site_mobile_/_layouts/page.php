<!DOCTYPE html>
<html>
<head>
    <?php //widget('site')->head(["css"=>"page_home"]); ?>
    <?php widget('site')->head(); ?>
</head>
<body>
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main-big">
        <div id="page-big">
            <div class="page-big-bg">
                <div class="container">
                    <h1 class="page-big-title">
                        <?php if (isset($page->title) && $page->title): ?>
                            <?php echo $page->title; ?>
                        <?php endif; ?>
                    </h1>
                    <?php if (isset($page->intro) && $page->intro): ?>
                        <p><?php echo $page->intro; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($page->type == 1): ?>
                <?php $menus = model('page')->filter_get_list(['show' => 1, 'type' => 1]);
                //pr_db($menus); ?>
                <?php if ($menus): ?>
                    <ul class="link-tab">
                        <?php foreach ($menus as $m):
                            $m = mod('page')->add_info_url($m);
                            ?>
                            <li class="<?php echo ($page->id == $m->id) ? 'active' : '' ?>">
                                <a href="<?php echo $m->_url_view ?>"><?php echo $m->title ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
            <div class="container">
                <div class="page-big-body">
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

