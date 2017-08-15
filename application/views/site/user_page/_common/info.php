<div class="item-photo">
    <a href="<?php echo $info->_url_view; ?>" class="item-img">
        <img src="<?php echo $info->avatar->url_thumb ?>"
             alt="<?php echo $info->name; ?>">
    </a>

    <?php t('view')->load('tpl::_widget/user/display/item/info_contact',['row'=>$info]) ?>


</div>
<div class="item-info">
    <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $info)); ?>
    <div class="item-name"><a href="<?php echo $info->_url_view; ?>">
            <?php echo $info->name; ?></a>
        <?php echo widget('user')->action_favorite($info) ?>
    </div>
    <div class="item-profession">
        <?php echo character_limiter($info->profession, 250); ?>
    </div>
    <div class="item-meta">
        <?php t('view')->load('tpl::_widget/user/display/item/info_meta',['row'=>$info]) ?>
    </div>
    <?php t('view')->load('tpl::_widget/user/display/item/info_tags',['row'=>$info]) ?>


</div>
<div class="item-profile">
    <div class="avatar">
        <a href="<?php echo $info->_url_view ?>">
            <img
                src="<?php echo $info->avatar->url_thumb ?>" alt="<?php echo $info->name; ?>"> </a>
    </div>
    <div class="group">  <?php echo $info->user_group_name; ?></div>
</div>