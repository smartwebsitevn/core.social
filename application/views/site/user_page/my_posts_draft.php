<?php t('view')->load('tpl::user_page/_common/private') ?>
<div class="container">
    <div class="data-wraper">
        <?php echo widget('product')->filter(['input_hidden'=>['page'=>$page]], "top") ?>

        <div class="block-products-items">
            <?php /* ?>
    <div class="block-title heading-opt1">
        <strong class="title">Tất cả khóa học<?php //echo $category->name ?></strong>
        <?php //echo widget('product')->filter([], "base") ?>
    </div>
     <?php */ ?>
            <div class="block-content ajax-content-product-list">
                <?php if ($pages_config['total_rows'] > 0): ?>
                    <?php widget('product')->display_list($list,'owner_default'); ?>
                    <?php widget('product')->display_pagination($pages_config); ?>
                <?php else: ?>
                    <div class="clearfix mt20"></div>
                    <div class="well">
                        Thành viên này không có bài viết nào
                        <?php //echo lang('have_no_list') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>