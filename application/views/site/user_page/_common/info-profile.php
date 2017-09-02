
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
    <div class="infos">
       <span class="group"> <?php echo $info->user_group_name; ?></span>  <br>
        <span>Tham gia <?php echo $info->_created  ?></span>  <br>
        <span>ID - <?php echo $info->_id  ?></span>  <br>
    </div>