<div class="heading-op3">Đánh giá khóa học</div>
<div class="comment">
        <?php if(mod("service")->setting('comment_allow')){
            widget('comment')->comment($info,'service');
        } ?>
        <?php  widget('comment')->comment_list($info,'service') ?>
</div>

