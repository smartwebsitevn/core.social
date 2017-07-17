<div class="heading-op3">Đánh giá khóa học</div>
<div class="comment">
        <?php if(mod("type")->setting('comment_allow')){
            widget('comment')->comment($info,'type');
        } ?>
        <?php  widget('comment')->comment_list($info,'type') ?>
</div>

