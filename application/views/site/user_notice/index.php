<div class="block-products-items">
    <div class="block-title heading-opt1">
        <strong class="title">Thông báo của bạn</strong>
        <?php //echo widget('product')->filter([], "base") ?>
    </div>
    <div class="block-content ajax-content-list">
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