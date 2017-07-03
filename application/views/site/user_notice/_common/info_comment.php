<div class="heading-op3">Đánh giá khóa học</div>
<div class="comment">
        <?php if(mod("user_notice")->setting('comment_allow')){
            widget('comment')->comment($info,'user_notice');
        } ?>
        <?php  widget('comment')->comment_list($info,'user_notice') ?>
</div>

