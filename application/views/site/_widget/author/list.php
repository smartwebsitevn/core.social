
<?php if (isset($list) && $list): ?>
    <div class="content row">
        <?php foreach ($list as $info): ?>
            <div class="views-row col-xs-12 col-sm-4 col-lg-4">
                <div class="box-wrap">
                    <div class="images">
                        <a href="<?php echo $info->_url_view; ?>" title="<?php echo $info->name; ?>"><img src="<?php echo $info->image->url_thumb; ?>" /></a>
                    </div>
                    <div class="info">
                        <div class="title">
                            <a href="<?php echo $info->_url_view; ?>" title="<?php echo $info->name; ?>"><?php echo $info->name; ?></a>
                        </div>
                        <div class="name-gv"><?php echo $info->information; ?></div>
                       <!-- <div class="views">26</div>
                        <div class="comment">30</div>-->
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
    <div class="clear"></div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>


