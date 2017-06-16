<div class="clearfix"></div>
<div class="bl-tab-home mt20">
    <div class="tab-promotion">
        <ul class="nav nav-pills">
            <?php foreach ($cats_name as $i => $row): ?>
                <li class="<?php if ($i == 0) echo 'active'; ?>">
                    <a data-toggle="pill" href="#cat_tab_<?php echo $i ?>"
                        ><?php echo $row->name; ?></a>
                </li>


            <?php endforeach; ?>

        </ul>
        <div class="tab-content">
            <?php foreach ($cats as $i => $cat): ?>
                <div class="tab-pane fade <?php if ($i == 0) echo 'active in'; ?> " id="cat_tab_<?php echo $i ?>">
                    <div class="row">
                        <?php foreach ($cat as $row): //pr($row);?>
                            <div class="col-sm-4">
                                <div class="item">

                                        <img alt="<?php echo $row->title; ?>"
                                             src="<?php echo $row->image->url_thumb; ?>">

                                    <div class="desc">
                                        <h4><a href="<?php echo $row->_url_view; ?>"><?php echo $row->title; ?></a></h4>
                                        <span><?php echo $row->_created_time; ?></span>

                                        <p><?php echo $row->intro; ?>.</p>
                                        <a href="<?php echo $row->_url_view; ?>"
                                            >Chi tiết<i class="fa fa-angle-double-right"
                                                                                        aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>


        </div>
    </div>
</div>
<div class="clearfix"></div>