    <a href="<?php echo $row->_url_view; ?>" class="item-img">
        <img src="<?php echo $row->avatar->url_thumb  ?>"
             alt="<?php echo $row->name; ?>">
    </a>
    <?php if ($row->user_group_type == 'user_manager'): ?>
        <span class="item-label label-user-manager">  <i class="pe-7s-helm"></i></span>
    <?php elseif ($row->user_group_type =='user_active'): ?>
        <span class="item-label label-user-active">  <i class="pe-7s-medal"></i></span>
    <?php endif; ?>

