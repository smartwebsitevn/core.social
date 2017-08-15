<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):
            $row = mod('product')->add_info_images($row);
            $author = $row->_author;// pr($row);
            ?>
            <div class="item-social <?php echo isset($row->_ads) ? 'item-social-ads' : '' ?> ">
                <div class="clearfix">
                    <div class="item-photo">
                        <a href="<?php echo $author->_url_view; ?>" class="item-img">
                            <img src="<?php echo thumb_img($author->avatar) ?>"                     alt="<?php echo $author->name; ?>">
                        </a>
                        <div>
                            <span class="name">
                                 <a href="<?php echo $author->_url_view; ?>" >
                                     <?php echo $author->name ?>

                                 </a>
                               </span>
                            <?php /* ?>
                            <span class="profession"><?php echo $author->profession ?></span>
                            <?php */ ?>
                            <span class="item-time"><?php echo $row->_created_full; ?>  </span>
                        </div>


                    </div>
                    <div class="item-media">
                        <div class="item-name">
                            <a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?>
                                <?php if(isset($row->files) && $row->files): ?>
                                    <i class="pe-7s-paperclip"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="item-desc">
                            <?php echo macro()->more_block($row->description, 110); ?>
                        </div>
                        <div class="item-files">
                            <?php t('view')->load('tpl::_widget/product/display/item/info_files',['row'=>$row])?>
                        </div>
                        <?php t('view')->load('tpl::_widget/product/display/item/info_media',['row'=>$row])?>

                        <?php echo widget('product')->action_comment($row) ?>


                    </div>
                        <div class="item-actions">
                            <div class="item-meta item-action">
                                <?php echo widget('product')->action_vote($row) ?>
                            </div>
                            <div class="item-action">
                                <?php echo widget('product')->action_favorite($row) ?>
                            </div>
                            <div class="item-action">
                                <?php widget('product')->action_share($row) ?>
                            </div>




                        </div>
                                    <?php //t('view')->load('tpl::_widget/product/display/item/infos')
                        ?>

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
