<a class="load_ajax comments" _field="<?php echo $row->id; ?>_comment" _url="<?php echo $url_comment ?>">
    <i class="pe-7s-comment"></i> Bình luận
    <b id="<?php echo $row->id; ?>_comment_total"><?php echo number_format($row->comment_count) ?></b> <?php //echo lang("count_comment") ?>
</a>

<div class="clear"></div>
<div id="<?php echo $row->id; ?>_comment_load" class="tab_load"></div>
<div id="<?php echo $row->id; ?>_comment_show"></div>