<?php t('view')->load('tpl::user_page/_common/public') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 main-content">
            <?php echo widget('user')->filter(['input_hidden'=>['page'=>$page]], "top") ?>

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
    </div>
</div>
<?php widget('site')->footer_navi('user_filter'); ?>
