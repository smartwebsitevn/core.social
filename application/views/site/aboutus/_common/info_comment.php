<div class="heading-op3">Đánh giá khóa học</div>
<div class="comment">
        <?php if(mod("aboutus")->setting('comment_allow')){
            widget('comment')->comment($info,'aboutus');
        } ?>
        <?php  widget('comment')->comment_list($info,'aboutus') ?>
</div>

