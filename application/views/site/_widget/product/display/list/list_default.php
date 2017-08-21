<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):
            $row = mod('product')->add_info_images($row);
            $layout_full = (!$row->video && !$row->images && !$row->link);
            $author = $row->_author;// pr($row);
            ?>
            <div class="item-social <?php echo isset($row->_ads) ? 'item-social-ads' : '' ?> ">
                <div class="clearfix">
                    <div class="item-photo">
                        <a href="<?php echo $author->_url_view; ?>" class="item-img">
                            <img src="<?php echo thumb_img($author->avatar)//$row->image->url_thumb;
                            ?>"
                                 alt="<?php echo $author->name; ?>">
                            <span class="name"><?php echo $author->name ?></span><br>
                            <span class="profession"><?php echo $author->profession ?></span>


                        </a>

                    </div>
                    <div class="item-info<?php echo $layout_full ? '-full' : '' ?>">
                        <?php //echo t('view')->load('tpl::_widget/product/display/item/info_label', array('row' => $row));
                        ?>
                        <div class="item-name">
                            <a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?>
                                <?php if(isset($row->files) && $row->files): ?>
                                    <i class="pe-7s-paperclip"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="item-time"><?php echo $row->_created_full; ?>  </div>
                        <div class="item-desc">
                            <?php echo macro()->more_word($row->description, 45); ?>
                        </div>
                        <div class="item-files">
                            <?php t('view')->load('tpl::_widget/product/display/item/info_files',['row'=>$row])?>
                        </div>
                        <div class="item-meta">
                            <?php echo widget('product')->action_vote($row) ?>
                             <?php //echo widget('product')->action_comment($row) ?>

                        </div>

                        <div class="item-actions">
                            <?php echo widget('product')->action_favorite($row) ?>
                            <?php widget('product')->action_share($row) ?>
                            <?php //widget('product')->action_close()
                            ?>
                        </div>
                        <div class="clear"></div>
                        <div id="<?php echo $row->id; ?>_comment_load" class="tab_load"></div>
                        <div id="<?php echo $row->id; ?>_comment_show"></div>
                        <?php //t('view')->load('tpl::_widget/product/display/item/infos')
                        ?>
                    </div>
                    <div class="item-media">
                        <?php t('view')->load('tpl::_widget/product/display/item/info_media',['row'=>$row])?>

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
        <div class="product-list list-social list-social-default">
            <?php echo $_data_list() ?>
        </div>

    <?php endif; ?>
    <?php if (t('input')->is_ajax_request() && isset($pages_config)) : ?>
        <?php echo t('view')->load('tpl::_widget/product/display/list/_reload_js'); ?>
        <?php widget('product')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <div class="clearfix"></div>
    <div class="well">
        <?php echo lang('have_no_list') ?>
    </div>
<?php endif; ?>
