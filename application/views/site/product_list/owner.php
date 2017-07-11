<div class="block-products-items">
    <?php /* ?>
    <div class="block-title heading-opt1">
        <strong class="title"><?php echo $category->name ?></strong>
        <?php //echo widget('product')->filter([], "base") ?>
    </div>
     <?php */ ?>
    <div class="block-content ajax-content-product-list">
        <?php if ($pages_config['total_rows'] > 0): ?>
            <?php
            $style_display ='';
            if (isset($category->common_data->display) && $category->common_data->display )
                $style_display=$category->common_data->display;

            ?>
            <?php widget('product')->display_list($list,$style_display); ?>
            <?php widget('product')->display_pagination($pages_config); ?>
        <?php else: ?>
            <div class="clearfix mt20"></div>
            <div class="well">
                <?php echo lang('have_no_list') ?>
            </div>
        <?php endif; ?>
    </div>
</div>