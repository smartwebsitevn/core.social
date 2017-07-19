<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):
            $row = mod('product')->add_info_images($row);

            $layout_full = (!$row->video && !$row->images);
            $author = $row->_author;// pr($row);
            ?>
            <div class="item-social <?php echo isset($row->_ads) ? 'item-social-ads' : '' ?> ">
            <div class="clearfix">
            <div class="item-photo">
                <a href="<?php echo $author->_url_view; ?>" class="item-img">
                    <img src="<?php echo thumb_img($author->avatar)//$row->image->url_thumb;
                    ?>"
                         alt="<?php echo $author->name; ?>">
                    <?php echo $author->name; ?></a>

                </a>
            </div>
            <div class="item-info<?php echo $layout_full ? '-full' : '' ?>">
                <?php //echo t('view')->load('tpl::_widget/product/display/item/info_label', array('row' => $row));
                ?>
                <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                        <?php echo $row->name; ?></a>
                </div>
                <div class="item-desc"><?php echo character_limiter($row->brief, 250); ?>    </div>
                        <div class="item-meta">
                            <span>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('product/vote/' . $row->id) . "?act=like" ?>"><i
                                    class="pe-7s-up-arrow"></i></a>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('product/vote/' . $row->id) . "?act=dislike" ?>"><i
                                    class="pe-7s-bottom-arrow"></i></a>
                            </span>
                                <span
                                    class="points"> <b><?php echo number_format($row->vote_total) ?></b> <?php echo lang("count_point") ?></span>
                            <span
                                class="views"> <b><?php echo number_format($row->view_total) ?></b> <?php echo lang("count_view") ?></span>
                            <span
                                class="comments"> <b><?php echo number_format($row->comment_count) ?></b> <?php echo lang("count_comment") ?></span>
                            <!--<span class="date_created"> <b><?php /*echo $row->_created */
                            ?></b> </span>-->

                        </div>

                        <div class="item-actions">
                            <?php echo widget('product')->action_favorite($row) ?>
                            <?php widget('product')->action_share($row) ?>
                            <?php //widget('product')->action_close() ?>
                        </div>

                <?php //t('view')->load('tpl::_widget/product/display/item/infos')
                ?>
            </div>
            <div class="item-media">
            <?php if ($row->video):
            if (is_string($row->video_data)) {
                $link = json_decode($row->video_data);
            }
            $link = $link->link;
            ?>

            <iframe width="100%" height="300px" src="https://www.youtube.com/embed/<?php echo $link ?>?rel=0"
                    frameborder="0" allowfullscreen></iframe>
        <?php else: ?>
            <?php if (isset($row->images) && $row->images): //pr($info->images) ?>
                <div class="product-images">
                    <div class="owl-carousel">
                    <?php $i = 0;
                    foreach ($row->images as $img): $i++;// pr($row)?>
                        <div class="item" data-dot="<img src='<?php echo $img->_url_thumb; ?>'>">
                            <img class="img-slide" src="<?php echo $img->_url; ?>">

                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
        <?php endif; ?>

        <?php endif; ?>
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
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
