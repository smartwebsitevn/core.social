<?php t('view')->load('tpl::user_page/_common/private') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 main-content">
            <div class="data-wraper">
                <div class="block-products-items">
                    <div class="block-title heading-opt1">
                        <strong class="title">Thông báo của bạn</strong>
                    </div>
                    <div class="block-content ajax-content-product-list">
                        <?php if ($pages_config['total_rows'] > 0): ?>
                            <?php widget('user_notice')->display_list($list); ?>
                            <?php widget('user_notice')->display_pagination($pages_config); ?>
                        <?php else: ?>
                            <div class="clearfix mt20"></div>
                            <div class="well">
                                Bạn không có thông báo nào.
                                <?php //echo lang('have_no_list') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>