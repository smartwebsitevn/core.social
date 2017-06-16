<!-- Content -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6">
                <div class="aside">
                    <h4 class="aside-title"><?php echo $widget->setting["name1"] ?></h4>
                    <?php foreach ($list1 as $row): ?>
                        <div class="aside-block">
                            <a class="block-title" href="<?php echo $row->_url_view; ?>"
                               title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>

                            <p class="block-body"><?php echo character_limiter($row->target, 50); ?></p>
                            <a href="<?php echo $row->_url_view; ?>" class="cus-btn cus-btn-info">Đăng ký</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-md-push-6 col-sm-6">
                <div class="aside aside-right">
                    <h4 class="aside-title"><?php echo $widget->setting["name3"] ?></h4>
                    <?php foreach ($list3 as $row): ?>
                        <div class="aside-block">
                            <a class="block-title" href="<?php echo $row->_url_view; ?>"
                               title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>

                            <p class="block-body"><?php echo character_limiter($row->target, 50); ?></p>

                            <p class="block-price">Giá: <?php echo $row->_price ?></p>
                            <a href="<?php echo $row->_url_view; ?>" class="aside-register">Đăng ký ngay >></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-md-pull-3 col-sm-12">
                <div class="listen">
                    <h4 class="aside-title"><?php echo $widget->setting["name2"] ?></h4>
                    <div class="owl-carousel-fixed">
                        <?php foreach ($list2 as $row): //pr($row); ?>
                            <div class="carousel-item">
                                <a href="<?php echo $row->_url_view; ?>"
                                   title="<?php echo $row->name; ?>"> <img src="<?php echo $row->image->url_thumb; ?>"/></a>
                                <?php if(isset($row->target)&& $row->target):
                                  $target=  handle_content($row->target,"output") ?>
                                    <div class="content-slide">
                                        <div class="cus-slide2">
                                            <p>
                                            <?php echo character_limiter($target,100) ?>
                                            </p>
                                            <a style="font-size: 14px;  margin-bottom: 13px;   padding: 8px 40px;" class="cus-btn cus-btn-border cus-btn-info" href="<?php echo $row->_url_view; ?>">Xem thêm</a>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>