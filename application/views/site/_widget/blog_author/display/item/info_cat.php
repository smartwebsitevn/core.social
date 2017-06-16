<?php
$_data_link = function ($list) {
    ob_start(); ?>
    <?php if ($list): ?>
        <?php foreach ($list as $it):
            if (!isset($it->name) || !$it->name) continue;
            $link_cat = '';
            if (is_numeric($it->id))
                $link_cat = 'href="javascript:void(0)" class=" act-display-cat"  data-url="test?id=' . $it->id . '"';
            ?>
            <a <?php echo $link_cat; ?> ><?php echo ucfirst($it->name) ?></a>,
        <?php endforeach; ?>
    <?php endif; ?>
    <?php return ob_get_clean();
};
?>
<?php //foreach (array('_training_name', '_school_name', '_subject_name') as $f) : ?>
    <?php if (isset($info->_training_name)): ?>
        <br>
        <span>Hệ: <?php echo $info->_training_name ?></span>
    <?php endif; ?>
    <?php if (isset($info->_school_name)): ?> ,
        <span>Trường: <?php echo $info->_school_name ?></span>
    <?php endif; ?>
    <?php if (isset($info->_subject_name)): ?> ,
        <span>Bộ môn: <?php echo $info->_subject_name ?></span>
    <?php endif; ?>
<?php //endforeach; ?>

