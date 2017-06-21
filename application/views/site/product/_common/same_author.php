<?php if($info->user_id): ?>
<div class="block-info p15">
    <h4>Cùng tác giả</h4>
    <hr/>
    <div class="block-khRelated">
        <?php widget('product')->same_manufacture($info->manufacture_id, [], 'sidebar'); ?>
    </div>
</div>
<?php endif; ?>