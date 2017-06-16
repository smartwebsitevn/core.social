<?php if ($row->is_sellbest): ?>
    <span class="item-label label-sellbest"><?php echo lang("label_sellbest") ?></span>
<?php elseif ($row->is_feature): ?>
    <span class="item-label label-hot"><?php echo lang("label_feature") ?></span>
<?php elseif ($row->is_new): ?>
    <span class="item-label label-new"><?php echo lang("label_new") ?></span>
<?php /*elseif ($row->is_soon): ?>
    <span class="item-label label-soon"><?php echo lang("label_soon") ?></span>
<?php */ endif; ?>
<?php if (isset($row->_price_special_percent) && $row->_price_special_percent): ?>
    <span class="item-label-discount">-<?php echo $row->_price_special_percent ?>%</span>
<?php endif;?>