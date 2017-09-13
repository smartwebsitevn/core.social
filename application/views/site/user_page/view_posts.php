<?php t('view')->load('tpl::user_page/_common/public') ?>
<div class="container">
    <div class="row">
        <div class="col-md-3 sidebar ">
            <div class="sticky-element" data-spacing="65" data-limiter="#footer">
                <div class="slimscroll_" data-height="90vh">
                    <?php echo widget('product')->filter(['input_hidden'=>['page'=>$page]], "sidebar_user") ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 main-content">
            <div class="data-wraper">
                <div class="block-products-items">
                    <?php /* ?>
    <div class="block-title heading-opt1">
        <strong class="title">Tất cả khóa học<?php //echo $category->name ?></strong>
        <?php //echo widget('product')->filter([], "base") ?>
    </div>
     <?php */ ?>
                    <div class="block-content ajax-content-product-list">
                        <?php if ($pages_config['total_rows'] > 0): ?>
                            <?php
                            $style_display = '';
                            ?>
                            <?php widget('product')->display_list($list); ?>
                            <?php widget('product')->display_pagination($pages_config); ?>
                        <?php else: ?>
                            <div class="clearfix"></div>
                            <div class="well">
                                Thành viên này không có bài viết nào
                                <?php //echo lang('have_no_list') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 sidebar">
            <div class="sticky-element" data-spacing="65" data-limiter="#footer">
                <div class="panel">
                    <div class="panel-heading">
                       Tự giới thiệu
                    </div>
                    <div class="panel-body">
                        <div class="slimscroll" data-height="90vh">
                            <?php echo n_to_br($info->desc) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>