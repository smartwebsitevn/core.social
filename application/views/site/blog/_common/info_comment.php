<div class="heading-op3">Đánh giá khóa học</div>
<div class="comment">
        <?php if(mod("blog")->setting('comment_allow')){
            widget('comment')->comment($info,'blog');
        } ?>
        <?php  widget('comment')->comment_list($info,'blog') ?>
</div>

