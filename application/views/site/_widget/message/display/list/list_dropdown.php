<li class=" dropdown dropdown-notice">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
       aria-expanded="false">
        <i class="pe-7s-comment" style="font-size:26px"></i>
        <span class="count"><?php echo $total_unread ?></span>
    </a>

    <?php if (isset($list) && $list): ?>
        <ul class="dropdown-menu">
            <div class="heading clearfix">
                <span class="title pull-left">Tin nhắn</span>
                <?php if($total_unread==0): ?>
                <span class="check-count pull-right"> <i class="fa fa-check"></i>&nbsp;đã xem hết</span>
                <?php endif; ?>
            </div>
            <div class="slimscroll p2">
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
            </div>
            <div class="p10 text-center"><a href="<?php echo site_url('message/inbox') ?>">Xem tất cả</a></div>
        </ul>


    <?php else: ?>
        <span class="red"><?php echo lang("have_no_list") ?></span>
    <?php endif; ?>
</li>
