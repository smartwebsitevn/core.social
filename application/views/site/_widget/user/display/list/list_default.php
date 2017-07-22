<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):// pr($row);?>
            <div class="item-user <?php echo isset($row->_ads) ? 'item-user-ads' : '' ?> ">
                <div class="clearfix">
                    <div class="item-photo">
                        <a href="<?php echo $row->_url_view; ?>" class="item-img">
                            <img src="<?php echo $row->avatar->url_thumb  ?>"
                                 alt="<?php echo $row->name; ?>">
                        </a>
                    </div>
                    <div class="item-info">
                        <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $row)); ?>
                        <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?></a>
                            <?php echo widget('user')->action_favorite($row) ?>
                        </div>
                        <div class="item-profession"><?php echo character_limiter($row->profession, 250); ?>    </div>
                        <div class="item-meta">
                            <?php if(isset($row->_working_city) && $row->_working_city): ?>
                                <span class="place"> <i class="pe-7s-map-marker"></i> <b><?php echo $row->_working_city_name.', '.$row->_working_country_name?></b></span>
                            <?php endif; ?>
                            <span  class="posts"> <b><?php echo number_format($row->post_total) ?></b> <?php echo lang("count_post") ?></span>

                            <span class="points"> <b><?php echo number_format($row->vote_total) ?></b> <?php echo lang("count_point") ?></span>
                            <span   class="follows"> <b><?php echo number_format($row->follow_total) ?></b> <?php echo lang("count_follow") ?></span>


                        </div>
                        <div class="item-desc"><?php echo character_limiter($row->desc, 250); ?>    </div>


                        <div class="item-action">

                            <?php // widget('user')->action_follow($row,$user) ?>
                            <?php //widget('user')->action_favorite($row,$user) ?>
                            <?php //widget('user')->action_share($row) ?>
                            <?php //widget('user')->action_close() ?>
                        </div>
                        <?php //t('view')->load('tpl::_widget/user/display/item/infos') ?>
                        <?php t('view')->load('tpl::_widget/user/display/item/info_tags',['row'=>$row]) ?>
                    </div>
                    <div class="item-profile">
                        <div class="avatar">
                            <a href="<?php echo $row->_url_view ?>">
                                <img
                                    src="<?php echo $row->avatar->url_thumb ?>"    alt="<?php echo $row->name; ?>"> </a>

                        </div>
                        <div class="group">  <?php echo $row->user_group_name; ?></div>
                        <div class="links">
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-angle-right-circle"></i>

                            </a>
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-mail"></i>

                            </a>
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-call"></i>

                            </a>
                            <a data-toggle="modal" data-target="#modal-company-info">
                                <i class="pe-7s-id"></i>

                            </a>
                        </div>

                        <a class="btn btn-default btn-outline"><i class="pe-7s-like"></i> Theo dõi</a>
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
