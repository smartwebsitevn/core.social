<div class="block-products-items">
    <div class="block-title heading-opt1">
        <?php echo widget('product')->filter([], "base") ?>
    </div>
    <div class="block-content">
        <?php if ($pages_config['total_rows'] > 0): ?>
            <?php widget('product')->display_list($list,'block'); ?>
            <?php widget('product')->display_pagination($pages_config); ?>
        <?php else: ?>
            <div class="clearfix mt20"></div>
            <div class="well">
                <?php echo lang('have_no_list') ?>
            </div>
        <?php endif; ?>
    </div>
</div>