<?php if (isset($list) && $list): ?>
    <a data-dismiss="modal" data-toggle="modal" data-target="#system_message_notify"
       href="#">
        <i class="pe-7s-comment" style="font-size:26px"></i>
        <span class="count"><?php echo $total_unread ?></span>
    </a>
<?php echo  macro()->modal_start(['id'=>'system_user_notify','name'=>'Thông báo mới']); ?>

            <div class="heading clearfix">
                <?php if($total_unread==0): ?>
                <span class="check-count pull-right"> <i class="fa fa-check"></i>&nbsp;Đã đọc tất cả</span>
                <?php endif; ?>
            </div>
            <ul>
                <?php foreach ($list as $row):  //pr($row);?>
                    <li>
                        <a href="<?php //echo $row->url?$row->url:'#0'; ?>">
                            <img  class="avatar" src="img/icon/logo-user.png" class="logo-job pull-left">
                            <div class="infos">

                                <div class="title"> <?php echo $row->title ?> </div>
                                <div class="content"> <?php echo character_limiter($row->content,50) ?> </div>
                            <div class="created"><i class="fa fa-clock-o"></i> Cách đây <?php echo  timespan($row->created,'',1);//$row->_created ?></div>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="p10 text-center"><a href="<?php echo site_url('my-page').'?page=message' ?>"">Xem tất cả</a></div>
    <?php echo macro()->modal_end(); ?>
<?php endif; ?>
