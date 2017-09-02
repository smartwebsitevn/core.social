<div class="avatar">
    <?php $is_user_special=isset($row->user_group_type) && in_array($row->user_group_type,['user_active','user_manager']); ?>
    <?php  if($is_user_special): ?>
        <?php if($row->user_group_type == 'user_manager' ): ?>
            <i class="pe-7s-helm"></i>
        <?php else: ?>
            <i class="pe-7s-medal"></i>
        <?php endif; ?>
    <?php endif; ?>
</div>
<div class="group">
    <?php echo $row->user_group_name; ?>
    <?php  if(!$is_user_special): ?>
        <br>
        <span>Tham gia <?php echo $row->_created  ?></span>
    <?php endif; ?>
</div>
<?php t('view')->load('tpl::_widget/user/display/item/info_contact',['row'=>$row]) ?>
<div class="mt10">

    <?php widget('user')->action_subscribe($row) ?>
    <?php widget('user')->action_message($row) ?>
</div>