<?php
if ($info->is_feature): ?>
    <span class="item-label label-hot"><?php echo lang("label_feature") ?></span>
<?php elseif ($info->is_new): ?>
    <span class="item-label label-new"><?php echo lang("label_new") ?></span>
<?php elseif ($info->is_soon): ?>
    <span class="item-label label-soon"><?php echo lang("label_soon") ?></span>
<?php endif; ?>
