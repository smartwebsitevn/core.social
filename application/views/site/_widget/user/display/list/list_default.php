<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row): //pr($row);?>
            <div class="item-social <?php echo isset($row->_ads) ? 'item-social-ads' : '' ?> ">
                <div class="clearfix">
                    <div class="item-photo">
                        <a href="<?php echo $row->_url_view; ?>" class="item-img">
                            <img src="<?php //echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                                 alt="<?php echo $row->name; ?>">
                        </a>
                    </div>
                    <div class="item-info-full">
                        <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $row)); ?>
                        <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?></a>
                            <?php echo widget('user')->action_favorite($row) ?>
                        </div>
                        <div class="item-desc"><?php echo character_limiter($row->desc, 250); ?>    </div>
                        <div class="item-meta">
                            <span>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('user/vote/' . $row->id) . "?act=like" ?>"><i
                                    class="pe-7s-angle-up-circle"></i></a>
                            <a class="do_action" data-type=""
                               data-url="<?php echo site_url('user/vote/' . $row->id) . "?act=dislike" ?>"><i
                                    class="pe-7s-angle-down-circle"></i></a>
                            </span>
                                <span
                                    class="points"> <b><?php echo number_format($row->vote_total) ?></b> <?php echo lang("count_point") ?></span>
                            <span
                                class="views"> <b><?php echo number_format($row->view_total) ?></b> <?php echo lang("count_view") ?></span>
                            <span
                                class="comments"> <b><?php echo number_format($row->comment_total) ?></b> <?php echo lang("count_comment") ?></span>
                            <span class="date_created"> <b><?php echo $row->_created ?></b> </span>

                        </div>


                        <div class="item-action">

                            <?php // widget('user')->action_follow($row,$user) ?>
                            <?php //widget('user')->action_favorite($row,$user) ?>
                            <?php //widget('user')->action_share($row) ?>
                            <?php //widget('user')->action_close() ?>
                        </div>
                        <?php //t('view')->load('tpl::_widget/user/display/item/infos') ?>
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
        <div class="list-user list-user-default">
            <?php echo $_data_list() ?>
        </div>

    <?php endif; ?>
    <?php if (t('input')->is_ajax_request() && isset($pages_config)) : ?>
        <?php echo t('view')->load('tpl::_widget/user/display/list/_reload_js'); ?>
        <?php widget('user')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
