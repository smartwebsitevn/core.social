<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php
        $now = now();

        foreach ($list as $row):
            $row = mod('product')->add_info_images($row);
            $layout_full = (!$row->video && !$row->images && !$row->link);
            $author = $row->_author;// pr($row);

            // if($row->id == 35) pr($row);
            $row_status = 'status-on';
            $row_label = '';
            if ($row->verified == 0) {
                $row_status = 'status-pendding';
                $row_label = 'Đang chờ duyệt';
            }
            /*if ($row->_ads) {
                $row_status = 'tuyen-gap';
                $row_label = $row->ads_title;
            }*/
            /*if ($row->expired <= $now) {
                $row_status = 'het-han';
                $row_label = 'Hết hạn';

            }*/
            if ($row->is_draft) {
                $row_status = 'status-draft';
                $row_label = 'Bản nháp';
            }
            if (!$row->status) {
                $row_status = 'status-off';
                $row_label = 'Đang ẩn';
            }
            ?>
            <div class="item-social <?php echo isset($row->_adsed) ? 'status-adsing' : '' ?> <?php echo $row_status ?>  ">
                <div class="clearfix">
                    <?php if ($row_label): ?>
                        <label class="label <?php echo 'label-'.$row_status ?> "><?php echo $row_label ?></label>
                    <?php endif; ?>
                    <?php if ( isset($row->_adsed) && $row->_adsed): ?>
                        <label class="label-job"><?php echo $row->ads_title ?></label>
                    <?php endif; ?>

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
                        <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?></a>
                        </div>
                        <div class="item-desc">
                            <?php echo macro()->more_word($row->brief, 45); ?>
                        </div>
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
                            <!--<span  class="views"> <b><?php /*echo number_format($row->view_total) */
                            ?></b> <?php /*echo lang("count_view") */
                            ?></span>-->
                            <span
                                class="comments"> <b><?php echo number_format($row->comment_count) ?></b> <?php echo lang("count_comment") ?></span>
                            <!--<span class="date_created"> <b><?php /*echo $row->_created */
                            ?></b> </span>-->

                        </div>

                        <div class="item-actions">
                            <?php echo widget('product')->action_favorite($row) ?>
                            <?php widget('product')->action_share($row) ?>
                            <?php //widget('product')->action_close()
                            ?>
                        </div>

                        <?php //t('view')->load('tpl::_widget/product/display/item/infos')
                        ?>
                    </div>
                    <div class="item-media">
                        <?php t('view')->load('tpl::_widget/product/display/item/info_media',['row'=>$row])?>
                     </div>
                     <?php t('view')->load('tpl::_widget/product/display/item/info_config',['row'=>$row,'row_status'=>$row_status])?>

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
