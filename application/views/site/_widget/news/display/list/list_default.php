<?php //pr($list);

if (isset($list) && $list): ?>
    <div style="height: 40px"></div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>