<div  class="act-load-ajax act-display-comment" _field="#<?php echo $row->id; ?>_comment" _url="<?php echo $url_comment ?>">
    <b id="<?php echo $row->id; ?>_comment_total"><?php echo number_format($row->comment_count) ?></b> Bình luận
</div>

<div class="clear"></div>
<div id="<?php echo $row->id; ?>_comment_load" class="tab_load"></div>
<div id="<?php echo $row->id; ?>_comment_show"></div>