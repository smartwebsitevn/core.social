<?php
$row = $info;
$user_manager = user_current_is_manager_special();
$row = mod('product')->add_info_images($row);
$author = $row->_author; //pr($author);
?>
<div class="block-products-items-popup block-products-items">
    <div class="list-social">
        <div class="item-social <?php echo isset($row->_ads) ? 'item-social-ads' : '' ?> ">
            <div class="clearfix">
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
                    <span class="item-time"><?php echo $row->_created_carbon->diffForHumans(); ?>  </span>

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
                    <?php t('view')->load('tpl::_widget/product/display/item/info_media_flat', ['row' => $row]) ?>

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
                <?php if ($user_manager): ?>
                    <div class="item-manager">
                        <?php echo widget('product')->action_manager($row, $user_manager) ?>
                    </div>
                <?php endif; ?>
                <?php //t('view')->load('tpl::_widget/product/display/item/infos')
                ?>

            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        nfc.reboot();
    })
</script>