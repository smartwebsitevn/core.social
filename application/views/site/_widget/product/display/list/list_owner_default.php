<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php
        $now = now();
        $user_manager = user_current_is_manager_special();
        foreach ($list as $row):
            $row = mod('product')->add_info_images($row);
            $layout_full = (!$row->video && !$row->images && !$row->link);
            $author = $row->_author;// pr($row);
            // if($row->id == 35) pr($row);
            $status=mod('product')->parse_status($row);
            ?>
            <div class="item-social item-owner <?php echo isset($row->_adsed) ? 'status-adsing' : '' ?> <?php echo $status['status'] ?>  ">
                <div class="clearfix">
                    <?php if ($status['label']): ?>
                        <label class="label <?php echo 'label-'.$status['status'] ?> "><?php echo $status['label'] ?></label>

                    <?php endif; ?>
                    <?php if ($status['text']): ?>
                        <label class="label label-text "><?php echo $status['text'] ?></label>

                    <?php endif; ?>
                    <?php if ( isset($row->_adsed) && $row->_adsed): ?>
                        <label class="label-job"><?php echo $row->ads_title ?></label>
                    <?php endif; ?>
                    <span class="item-time item-time-manager"><?php echo $row->_created_carbon->diffForHumans(); ?>  </span>

                    <div class="item-author">
                        <?php t('view')->load('tpl::_widget/product/display/item/info_author', ['row' => $row,'item_time_manager'=>1]) ?>
                    </div>
                    <div class="item-media">
                        <div class="item-name">
                            <?php t('view')->load('tpl::_widget/product/display/item/info_name', ['row' => $row]) ?>

                        </div>
                        <div class="item-desc">
                            <?php echo macro()->more_block($row->description, 110); ?>
                        </div>
                        <div class="item-files">
                            <?php t('view')->load('tpl::_widget/product/display/item/info_files', ['row' => $row]) ?>
                        </div>
                        <?php t('view')->load('tpl::_widget/product/display/item/info_media', ['row' => $row]) ?>

                        <?php echo widget('product')->action_comment($row) ?>


                    </div>
                    <div class="item-actions">
                        <?php t('view')->load('tpl::_widget/product/display/item/info_action', ['row' => $row]) ?>
                    </div>
                    <?php if ($user_manager): ?>
                        <div class="item-manager">
                            <?php echo widget('product')->action_manager($row, $user_manager) ?>
                        </div>
                    <?php endif; ?>
                    <?php if($status['status'] != 'status-locked'): ?>
                     <?php t('view')->load('tpl::_widget/product/display/item/info_config',['row'=>$row,'row_status'=>$status['status']])?>
                    <?php endif; ?>
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
        <?php widget('site')->js_reboot(); ?>
        <?php widget('product')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <div class="clearfix"></div>
    <div class="well">
        <?php echo lang('have_no_list') ?>
    </div>
<?php endif; ?>
