<hr>
<div id="<?php echo $row->id; ?>_comment_load" class="tab_load"></div>
<?php if($list): ?>
        Tất cả <b id="<?php echo $row->id; ?>_comment_total"><?php echo number_format($row->comment_count) ?></b> Bình luận
    <?php   echo $list;  ?>
    <?php if($total): ?>
    <div  class="act-load-ajax act-display-comment hideit" _field="#<?php echo $row->id; ?>_comment" _url="<?php echo $url_comment ?>">
        Xem thêm bình luận khác
    </div>
        <?php endif; ?>
<?php else: ?>
    <div  class="act-load-ajax act-display-comment" _field="#<?php echo $row->id; ?>_comment" _url="<?php echo $url_comment ?>">
        <b id="<?php echo $row->id; ?>_comment_total"><?php echo number_format($row->comment_count) ?></b> Bình luận
    </div>
<?php endif; ?>
<div class="clear"></div>
<div id="<?php echo $row->id; ?>_comment_show"></div>