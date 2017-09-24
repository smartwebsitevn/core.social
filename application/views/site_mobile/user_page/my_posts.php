<?php t('view')->load('tpl::user_page/_common/private') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 main-content">
            <?php  echo widget('product')->filter(['input_hidden'=>['page'=>$page]], "top") ?>
            <?php // t('view')->load('tpl::_widget/product/filter/filter_sidebar_user_streamline_post') ?>
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
                            <?php widget('product')->display_list($list,'owner_default'); ?>
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
    </div>
</div>
<?php widget('site')->footer_navi('product_filter'); ?>
