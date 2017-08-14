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
                            <?php t('view')->load('tpl::_widget/user/display/item/info_meta',['row'=>$row]) ?>
                        </div>

                        <div class="item-desc">
                            <?php echo macro()->more_word($row->desc,40); ?>
                        </div>


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
                        <?php t('view')->load('tpl::_widget/user/display/item/info_contact',['row'=>$row]) ?>
                        <div class="mt10">

                        <?php widget('user')->action_subscribe($row) ?>
                        <?php widget('user')->action_message($row) ?>
                        </div>
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
    <div class="clearfix "></div>
    <div class="well">
        <?php echo lang('have_no_list') ?>
    </div>
<?php endif; ?>
