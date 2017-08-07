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
                        <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?></a>
                        </div>
                        <div class="item-desc">
                            <?php echo macro()->more_word($row->description, 45); ?>
                        </div>
                        <?php t('view')->load('tpl::_widget/product/display/item/info_media',['row'=>$row])?>
                    </div>
                    <div class="item-info">
                        <div class="item-meta">
                            <?php echo widget('product')->action_vote($row) ?>

                           <span
                                    class="points"> <i class="pe-7s-star"></i> <?php echo lang("count_point") ?> <b><?php echo number_format($row->vote_total) ?></b> </span>
                            <!--<span  class="views"> <b><?php /*echo number_format($row->view_total) */
                            ?></b> <?php /*echo lang("count_view") */
                            ?></span>-->
                                                  <!--<span class="date_created"> <b><?php /*echo $row->_created */
                            ?></b> </span>-->
                            <?php echo widget('product')->action_comment($row) ?>

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
    <div class="clearfix mt20"></div>
    <div class="well">
        <?php echo lang('have_no_list') ?>
    </div>
<?php endif; ?>
