<?php //pr($info); ?>
<div id="movie-data-tooltip-<?php echo $info->id; ?>" style="display:none">

    <div class="block-quickview">
        <div class="block-title">
            <span class="title"><?php echo $info->name; ?></span>

            <?php if (in_array($info->_quality, array('hd_720', 'hd_1080'))): ?>
                <span class="hd-type"><?php echo lang("hd"); ?><span><?php echo $info->_quality == 'hd_720' ? '720' : '1080' ?></span></span>
            <?php endif; ?>
        </div>
        <div class="block-content">

            <p class="des"><?php echo character_limiter($info->desc,210); ?></p>

            <p><b><?php echo lang("length"); ?>: </b><?php echo $info->length ?> <?php echo lang("minute"); ?></p>

            <p><b><?php echo lang("cat"); ?>: </b><?php echo $info->_cat_name; ?></p>
        </div>
        <div class="block-bottom">
            <p><b><?php echo lang('imdb') ?>: <?php echo $info->imdb ?></b></p>
            <b><?php echo lang('rate') ?>:</b>
            <div class="rateit" data-rateit-value="<?php echo $info->rate ?>" data-rateit-ispreset="true"
                 data-rateit-readonly="true"></div>
            <span class="count-review">(<?php echo $info->rate ?> - <?php echo $info->rate_total . lang('rate_num') ?>
                )</span>

        </div>
    </div>
</div>