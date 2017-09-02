
    <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $info)); ?>
    <div class="item-name">
        <a href="<?php echo $info->_url_view; ?>">
            <?php echo $info->name; ?>
            <?php t('view')->load('tpl::_widget/user/display/item/info_attach',['row'=>$info]) ?>

        </a>
        <?php echo widget('user')->action_favorite($info) ?>
    </div>
    <div class="item-profession">
        <?php echo character_limiter($info->profession, 250); ?>
    </div>
    <div class="item-meta">
        <?php t('view')->load('tpl::_widget/user/display/item/info_meta',['row'=>$info]) ?>
    </div>
    <?php t('view')->load('tpl::_widget/user/display/item/info_tags',['row'=>$info]) ?>
