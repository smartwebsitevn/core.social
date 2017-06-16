<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(["css" => "pages/page_project"]); ?>
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
                        <?php
                        if (isset($category->name) && $category->name): ?>
                            <?php echo $category->name; ?>
                        <?php else: ?>
                            Tất cả dự án
                        <?php endif; ?>
                    </h1>
                    <?php if (isset($category->brief) && $category->brief): ?>
                        <p><?php echo $category->brief; ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (isset($categories) && $categories): ?>
                <ul class="link-tab">
                    <li class="<?php echo  !isset($category)? ' active' : '' ?>"><a  href="<?php echo site_url('danh-sach-du-an')?>">Tất cả dự án</a></li>

                    <?php foreach ($categories as $c):
                        $active = (isset($category) && $category->id == $c->id) ? ' active' : '';
                        ?>
                        <li class="<?php echo $active ?>"><a
                                href="<?php echo $c->_url_view ?>"><?php echo $c->name ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (isset($category->description) && $category->description): ?>
                <p><?php echo $category->description; ?></p>
            <?php endif; ?>
            <?php //echo $content_top; ?>
            <?php echo $content; ?>
            <?php //echo $content_bottom; ?>
        </div>

    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

