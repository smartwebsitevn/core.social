<?php //pr($row); ?>
<span class="lec-info fxdc">
    <?php if (isset($row->stats_data->lesson) && $row->stats_data->lesson): ?>
        <span class="row-one">
            <span><?php echo $row->stats_data->lesson ?> Bài giảng</span>
        </span>
    <?php endif; ?>
    <?php if (isset($row->stats_data->video) && $row->stats_data->video): ?>

        <span class="row-two">
            <span><?php echo $row->stats_data->video ?> Videos</span>
        </span>
    <?php endif; ?>

</span>