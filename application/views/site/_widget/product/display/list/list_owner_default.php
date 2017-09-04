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
            $row_text = '';
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
            if (($row->point_total + $row->point_fake) <=-10)  {
                $row_status = 'status-locked';
                $row_label = 'Tin đã bị khóa';
                $row_text = '<a href="'.site_url('tro-giup').'" target="_blank">Tại sao tin này bị khóa?</a>' ;
            }
            ?>
            <div class="item-social <?php echo isset($row->_adsed) ? 'status-adsing' : '' ?> <?php echo $row_status ?>  ">
                <div class="clearfix">
                    <?php if ($row_label): ?>
                        <label class="label <?php echo 'label-'.$row_status ?> "><?php echo $row_label ?></label>

                    <?php endif; ?>
                    <?php if ($row_text): ?>
                        <label class="label label-text "><?php echo $row_text ?></label>

                    <?php endif; ?>
                    <?php if ( isset($row->_adsed) && $row->_adsed): ?>
                        <label class="label-job"><?php echo $row->ads_title ?></label>
                    <?php endif; ?>

                    <div class="item-author">
                        <div class="item-photo">
                            <?php echo view('tpl::_widget/user/display/item/info_avatar', array('row' => $author)); ?>

                        </div>
                        <div class="item-info">
                                <span class="name">
                                 <a href="<?php echo $author->_url_view; ?>">
                                     <?php echo $author->name ?>

                                 </a>
                               </span>

                            <div class="item-meta">
                                <span class="profession"><?php echo $author->profession ?></span>
                                <?php if (isset($author->_working_city_name) && $author->_working_city_name): ?>
                                    <span class="place"> <i
                                            class="pe-7s-map-marker"></i> <?php echo $author->_working_city_name ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php /* ?>
                        <span class="item-time"><?php echo $row->_created_carbon->diffForHumans(); ?>  </span>
                        <?php */ ?>

                    </div>
                    <div class="item-media">
                        <div class="item-name">
                            <a href="<?php echo $row->_url_view; ?>">
                                <?php echo $row->name; ?>
                                <?php if (isset($row->files) && $row->files): ?>
                                    <i class="pe-7s-paperclip"></i>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="item-desc">
                            <?php echo macro()->more_block($row->description, 110); ?>
                        </div>
                        <div class="item-files">
                            <?php t('view')->load('tpl::_widget/product/display/item/info_files', ['row' => $row]) ?>
                        </div>
                        <?php t('view')->load('tpl::_widget/product/display/item/info_media', ['row' => $row]) ?>

                        <?php //echo widget('product')->action_comment($row) ?>


                    </div>
                    <?php if($row_status != 'status-locked'): ?>
                     <?php t('view')->load('tpl::_widget/product/display/item/info_config',['row'=>$row,'row_status'=>$row_status])?>
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
    <div class="clearfix mt20"></div>
    <div class="well">
        <?php echo lang('have_no_list') ?>
    </div>
<?php endif; ?>
