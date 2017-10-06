<?php if (isset($list) && $list): ?>


<?php echo macro()->modal_start(['id'=>'system_user_notify','name'=>'Thông báo mới']); ?>
            <div class="heading clearfix">
                <a class="check-count pull-right do_action" data-url="<?php echo site_url('user_notice/view_all') ?>"> <i class="fa fa-check"></i>&nbsp;Đã đọc tất cả</a>

                <?php /*if($total_unread==0): ?>
                <span class="check-count pull-right"> <i class="fa fa-check"></i>&nbsp;đã xem hết</span>
                <?php endif; */?>
            </div>
    <ul>

    <?php foreach ($list as $row):  //  pr($row);?>
                    <li>
                        <a href="<?php echo $row->url?$row->url:'#0'; ?>">
                        <div class="title"> <?php echo $row->title ?> </div>
                            <div class="created"><i class="fa fa-clock-o"></i> Cách đây <?php echo  timespan($row->created,'',1);//$row->_created ?></div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="p10 text-center"><a href="<?php echo site_url('my-page').'?page=notice' ?>">Xem tất cả</a></div>
    <?php echo  macro()->modal_end(); ?>

<?php endif; ?>
