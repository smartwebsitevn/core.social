<div class="container">
    <div class="nav-links">
        <a href="<?php echo site_url('user_list') ?>" class="btn <?php echo $page=='all'?'btn-default':'btn-outline'?>">Tất cả</a>
        <a href="<?php echo site_url('user_list/follow') ?>" class="btn <?php echo $page=='follow'?'btn-default':'btn-outline'?>">Tôi theo dõi</a>
        <a href="<?php echo site_url('user_list/follow_me') ?>" class="btn <?php echo $page=='follow_me'?'btn-default':'btn-outline'?>">Theo dõi tôi</a>
    </div>
</div>
