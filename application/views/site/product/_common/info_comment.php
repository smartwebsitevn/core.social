<hr>
<div class="comment">
    <div class="comment_load_ajax" _field="<?php echo $info->id; ?>_comment" _url="<?php echo $info->_url_comment ?>">
        Có <b id="<?php echo $info->id; ?>_comment_total"><?php echo number_format($info->comment_count) ?></b> Bình luận của chuyên gia
    </div>

    <div class="clear"></div>
    <div id="<?php echo $info->id; ?>_comment_load" class="tab_load"></div>
    <div id="<?php echo $info->id; ?>_comment_show"></div>

</div>

<script type="text/javascript">
    $(document).ready(function () {
        var $load = $('.comment_load_ajax');
        var field = $load.attr('_field');
        var url = $load.attr('_url');
        jQuery($load).nstUI('loadAjax', {
            url: url,
            field: {load: field + '_load', show: field + '_show'},
        });

    })
</script>