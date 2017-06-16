<div class="cackhoahoc">
    <div class="line-khoahoc">
        <ul>
            <?php foreach ($widgets as $k => $row):?>
            <li class="col-md-4 col-xs-4 <?php echo ($k == 0) ? 'active' : ''?>">
                <a data-toggle="tab" href="#<?php echo $row->widget.$k?>"><?php echo $row->name?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="tab-content">
        <?php foreach ($widgets as $k => $row):?>
            <div class="tab-pane fade in <?php echo ($k == 0) ? 'active' : ''?>" id="<?php echo $row->widget.$k?>">
                <div id="widget_order_<?php echo $row->widget?>">
                    <?php echo $row->contents?>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>
