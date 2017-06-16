<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):    //pr($row);?>
            <div class="item-kh">
                <div class="item-info">
                    <div class="item-photo">
                        <a href="<?php echo $row->_url_view; ?>" class="item-img">
                            <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                                 alt="<?php echo $row->name; ?>">
                        </a>
                        <?php echo view('tpl::_widget/product/display/item/info_label', array('row' => $row)); ?>
                        <?php //echo view('tpl::_widget/product/display/item/info_author', array('row' => $row)); ?>
                        <?php //echo view('tpl::_widget/product/display/item/info_stats', array('row' => $row)); ?>
                    </div>
                    <div class="item-detail">
                        <strong class="item-name"><a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?></a>
                            <?php echo widget('product')->action_favorite($row) ?>

                        </strong>

                        <div class="item-author"><?php echo character_limiter($row->brief, 50); ?>
                        </div>
                        <?php //echo view('tpl::_widget/product/display/item/info_rate', array('row' => $row)); ?>
                        <?php echo view('tpl::_widget/product/display/item/info_price', array('row' => $row)); ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php return ob_get_clean();
    }
    ?>
    <?php if (isset($load_more) && $load_more): ?>
        <?php echo $_data_list(); ?>
    <?php else: ?>
        <div class="product-list product-list-default">
            <?php echo $_data_list() ?>
        </div>

    <?php endif; ?>
    <?php if (t('input')->is_ajax_request() && isset($pages_config)) : ?>
        <?php echo view('tpl::_widget/product/display/list/_reload_js'); ?>
        <?php widget('product')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
