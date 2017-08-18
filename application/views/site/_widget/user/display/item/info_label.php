<?php /* if ($row->is_feature): ?>
    <span class="item-label label-feature"><?php echo lang("label_feature") ?></span>
<?php elseif ($row->is_feature): ?>
    <span class="item-label label-hot"><?php echo lang("label_feature") ?></span>
<?php elseif ($row->is_new): ?>
    <span class="item-label label-new"><?php echo lang("label_new") ?></span>
<?php endif; */?>

<?php if ($row->user_group_type == 'user_manager'): ?>
    <span class="item-label label-user-manager"><i class="fa fa-star"></i></span>
<?php elseif ($row->user_group_type =='user_active'): ?>
    <span class="item-label label-user-active"><i class="fa fa-star-harf-o"></i></span>
<?php endif; ?>
