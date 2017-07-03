<?php if($info->author_id): ?>
<div class=" p15">
    <h4>Cùng tác giả</h4>
    <hr/>
    <div class="block-khRelated">
        <?php widget('product')->same_author($info->author_id, [], 'sidebar_simple'); ?>
    </div>
</div>
<?php endif; ?>