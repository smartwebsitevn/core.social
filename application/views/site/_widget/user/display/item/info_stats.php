<?php //pr($row-?); ?>
<div class="item-stats">
    <p>Chuyên mục: <?php echo $row->_cat_name ?>  &nbsp;&nbsp;
        Cập nhập: <?php echo $row->_updated ?> &nbsp;&nbsp;
        Lượt xem: <?php echo $row->count_view ?></p>
    <?php if (isset($row->stats_data->time) && $row->stats_data->time): ?>
        <p><?php echo $row->stats_data->time ?></p>
    <?php endif; ?>
    <?php if (isset($row->stats_data->lesson) && $row->stats_data->lesson): ?>
        <p><?php echo $row->stats_data->lesson ?> Bài giảng</p>
    <?php endif; ?>
    <?php if (isset($row->stats_data->video) && $row->stats_data->video): ?>
        <p><?php echo $row->stats_data->video ?> Videos</p>
    <?php endif; ?>
</div>