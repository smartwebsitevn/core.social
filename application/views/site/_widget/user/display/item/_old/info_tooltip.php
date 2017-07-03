<?php //pr($row); ?>
<div id="movie-data-tooltip-<?php echo $row->id; ?>" style="display:none">

    <div class="block-quickview">
        <div class="block-title">
            <span class="title"><?php echo $row->name; ?></span>

            <?php if (in_array($row->_quality, array('hd_720', 'hd_1080'))): ?>
                <span class="hd-type"><?php echo lang("hd"); ?><span><?php echo $row->_quality == 'hd_720' ? '720' : '1080' ?></span></span>
            <?php endif; ?>
        </div>
        <div class="block-content">

            <p class="des"><?php echo character_limiter($row->desc,210); ?></p>

            <p><b><?php echo lang("length"); ?>: </b><?php echo $row->length ?> <?php echo lang("minute"); ?></p>

            <p><b><?php echo lang("cat"); ?>: </b><?php echo $row->_cat_name; ?></p>
        </div>
        <div class="block-bottom">
            <p><b><?php echo lang('imdb') ?>: <?php echo $row->imdb ?></b></p>
            <b><?php echo lang('rate') ?>:</b>
            <div class="rateit" data-rateit-value="<?php echo $row->rate ?>" data-rateit-ispreset="true"
                 data-rateit-readonly="true"></div>
            <span class="count-review">(<?php echo $row->rate ?> - <?php echo $row->rate_total . lang('rate_num') ?>
                )</span>

        </div>
    </div>
</div>