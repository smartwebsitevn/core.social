<div class="item-photo">
    <?php t('view')->load('tpl::_widget/user/display/item/info_avatar',['row'=>$info]) ?>
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
        <?php $is_user_special=isset($info->user_group_type) && in_array($info->user_group_type,['user_active','user_manager']); ?>
        <?php  if($is_user_special): ?>
            <?php if($info->user_group_type == 'user_manager' ): ?>
                <i class="pe-7s-helm"></i>
            <?php else: ?>
                <i class="pe-7s-medal"></i>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="group">
        <?php echo $info->user_group_name; ?>  <br>
        <span>Tham gia <?php echo $info->_created  ?></span>  <br>
        <span>ID - <?php echo $info->_id  ?></span>  <br>
    </div>
</div>