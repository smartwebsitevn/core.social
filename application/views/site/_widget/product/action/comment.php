<hr>
<div id="<?php echo $row->id; ?>_comment_load" class="tab_load"></div>
<?php if($list): ?>
        Có <b id="<?php echo $row->id; ?>_comment_total"><?php echo number_format($row->comment_count) ?></b> Bình luận của Ban Quản Trị
    <?php   echo $list;  ?>
    <?php //if($total): ?>
    <div  class="act-load-ajax act-display-comment comment_by_facebook" _field="#<?php echo $row->id; ?>_comment" _url="<?php echo $url_comment_facebook ?>">
        <i class="fa fa-facebook-square"></i> Bình Luận <i class="fa fa-angle-down"></i>
    </div>
     <?php //endif; ?>
<?php else: ?>
    <div  class="act-load-ajax act-display-comment comment_by_facebook" _field="#<?php echo $row->id; ?>_comment" _url="<?php echo $url_comment ?>">
       <?php /* ?>
        <b id="<?php echo $row->id; ?>_comment_total"><?php echo number_format($row->comment_count) ?></b>
        <?php */?>

        <i class="fa fa-facebook-square"></i> Bình luận <i class="fa fa-angle-down"></i>
    </div>
<?php endif; ?>
<div class="clear"></div>
<div id="<?php echo $row->id; ?>_comment_show"></div>