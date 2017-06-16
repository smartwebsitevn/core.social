<div class="heading-op3">Đánh giá khóa học</div>
<div class="comment">
        <?php if(mod("blog_author")->setting('comment_allow')){
            widget('comment')->comment($info,'blog_author');
        } ?>
        <?php  widget('comment')->comment_list($info,'blog_author') ?>
</div>

