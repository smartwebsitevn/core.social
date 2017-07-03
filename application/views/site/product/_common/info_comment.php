<hr>
<div class="comment">
        <?php //if(mod("product")->setting('comment_allow')){
            //widget('comment')->comment($info,'product');
        //} ?>
        <?php  widget('comment')->comment_list($info,'product') ?>

    <!-- Facebook comment -->
    <?php /*if (mod("product")->setting('comment_fb_allow')): //pr($info);?>
        <div class="clearfix"></div>
        <div class="fb-comments"
             data-href="<?php echo $info->_url_view ?>"
             data-numposts="5" data-width="100%">
        </div>
    <?php endif; */?>
</div>

