<?php t('view')->load('tpl::user_page/_common/private') ?>
<div class="container">
    <div class="row">
        <div class="col-md-3 sidebar ">
            <div class="sticky-element" data-spacing="65" data-limiter="#footer">
                <div class="slimscroll_" data-height="90vh">
                    <?php echo widget('user')->filter(['input_hidden'=>['page'=>$page]], "sidebar_user") ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 main-content">
            <div class="data-wraper">
                <div class="block-items">
                    <?php /* ?>
    <div class="block-title heading-opt1">
        <strong class="title">Tất cả khóa học<?php //echo $category->name ?></strong>
        <?php //echo widget('product')->filter([], "base") ?>
    </div>
     <?php */ ?>
                    <div class="block-content ajax-content-list">
                        <?php if ($pages_config['total_rows'] > 0): ?>
                            <?php widget('user')->display_list($list); ?>
                            <?php widget('user')->display_pagination($pages_config); ?>

                        <?php else: ?>
                            <div class="clearfix"></div>
                            <div class="well">
                                Thành viên này không theo dõi ai
                                <?php //echo lang('have_no_list') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 sidebar">
            <?php widget('user')->adsed(null, 'sidebar_adsed') ?>

        </div>
    </div>
</div>