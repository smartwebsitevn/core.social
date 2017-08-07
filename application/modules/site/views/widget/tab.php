<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <ul class="nav nav-tabs product-tabs">
            <?php foreach ($widgets as $k => $row):?>
                <li class="<?php echo ($k == 0) ? 'active' : ''?>">
                    <a data-toggle="tab" href="#<?php echo $row->widget.$k?>"><?php echo $row->name?></a></li>
            <?php endforeach;?>
        </ul>
    </div>
    <div class="col-sm-12">
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
</div>



