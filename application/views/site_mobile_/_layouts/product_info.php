<!DOCTYPE html>
<html>
<head>
    <?php //widget('site')->head(["css"=>"page_home"]); ?>
    <?php widget('site')->head(); ?>
</head>
<body class="page-catalog-view">
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
        <div class="breadcrumb-wrapper">
            <div class="container">
                <?php widget("site")->breadcrumbs() ?>
                <div class="next-page">
                    <?php /* ?>
                     <span class="count-search"><?php echo lang("total") ?><?php echo number_format($recruit_total_rows) ?></span>
                   <?php */ ?>

                    <?php if (isset($info_prev) && $info_prev): ?>
                        <a title="<?php echo $info_prev->name ?>" class="prev-search"
                           href="<?php echo $info_prev->_url_view ?>"></a>
                        <span class="text-search text-search-prev"><a title="<?php echo $info_prev->name ?>"
                                                    href="<?php echo $info_prev->_url_view ?>"><?php echo $info_prev->name ?></a></span>

                    <?php endif; ?>
                    <?php if (isset($info_next) && $info_next): ?>
                        <span class="text-search text-search-next"><a title="<?php echo $info_next->name ?>"
                                                    href="<?php echo $info_next->_url_view ?>"><?php echo $info_next->name ?></a></span>
                        <a title="<?php echo $info_next->name ?>" class="next-search"
                           href="<?php echo $info_next->_url_view ?>"></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <?php // echo $content_top; ?>
            <?php echo $content; ?>
            <?php //echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
</body>
</html>

