<?php
$_data_cat_type = function ($list, $name) use ($filter) {
    ob_start(); ?>
    <?php if ($list): ?>
        <?php foreach ($list as $row):
            $active_status = (isset($filter[$name]) && is_array($filter[$name]) && in_array($row->id, $filter[$name])) ? 1 : 0;
            ?>
            <div class="filter-tick <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
                 data-name="<?php echo $name ?>[]" data-value="<?php echo $row->id ?>">
                <label><?php echo $row->name ?></label>
                <?php if ($active_status): ?>
                    <input name="<?php echo $name ?>[]" value="<?php echo $row->id ?>" type="hidden">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php return ob_get_clean();
};
$_data_array = function ($list, $name) use ($filter) {
    ob_start(); ?>
    <?php if ($list): ?>
        <?php foreach ($list as $v=>$label):
            $active_status = (isset($filter[$name]) &&  $filter[$name] == $v) ? 1 : 0;
            ?>
            <div class="filter-tick <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
                 data-name="<?php echo $name ?>[]" data-value="<?php echo $v ?>">
                <label><?php echo $label ?></label>
                <?php if ($active_status): ?>
                    <input name="<?php echo $name ?>[]" value="<?php echo $v ?>" type="hidden">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php return ob_get_clean();
};
$_data_single = function ($key,$value,$name) use ($filter) {
    ob_start();
    $active_status = (isset($filter[$key]) && $filter[$key])== $value? 1 : 0;
    ?>
    <div class="filter-tick <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
         data-name="<?php echo $key ?>" data-value="<?php echo $value ?>">
        <label><?php echo $name ?></label>
        <?php if ($active_status): ?>
            <input name="<?php echo $key ?>" value="<?php echo $value ?>" type="hidden">
        <?php endif; ?>
    </div>
    <?php return ob_get_clean();
};
?>


<div class="block">
    <div class="block-title">
        Đang theo dõi
    </div>
    <div class="block-content slimscroll">
        <?php widget('user')->feature(null,'sidebar_feature') ?>
    </div>
</div>