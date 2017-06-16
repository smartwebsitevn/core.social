<?php //pr($info-?); ?>
<div class="item-stats">
    <?php if (isset($info->stats_data->time) && $info->stats_data->time): ?>
        <p><?php echo $info->stats_data->time ?></p>
    <?php endif; ?>
    <?php if (isset($info->stats_data->lesson) && $info->stats_data->lesson): ?>
        <p><?php echo $info->stats_data->lesson ?> Bài giảng</p>
    <?php endif; ?>
    <?php if (isset($info->stats_data->video) && $info->stats_data->video): ?>
        <p><?php echo $info->stats_data->video ?> Videos</p>
    <?php endif; ?>
</div>