    <div class="block-content ajax-content-list">
        <?php if ($pages_config['total_rows'] > 0): ?>
            <?php
            $style_display ='';
            if (isset($category->common_data->display) && $category->common_data->display )
                $style_display=$category->common_data->display;

            ?>
            <?php widget('blog')->display_list($list,$style_display); ?>
            <?php widget('blog')->display_pagination($pages_config); ?>
        <?php else: ?>
                <?php echo lang('have_no_list') ?>
        <?php endif; ?>
    </div>