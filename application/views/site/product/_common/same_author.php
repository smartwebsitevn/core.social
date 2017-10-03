<?php if($info->user_id): ?>
<div class="p15">
    <h4>Cùng tác giả</h4>
    <hr/>
    <div class="block-author-post">
        <?php widget('product')->same_author($info->user_id, [], 'sidebar_simple'); ?>
    </div>
</div>
<?php endif; ?>