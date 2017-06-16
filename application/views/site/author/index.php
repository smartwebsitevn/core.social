<?php  //echo macro()->page_heading("Danh sách giảng viên")?>
<?php //echo macro()->page_body_start()?>
    <div class="row">
        <div class="col-md-12">
            <h3 class="gv-title"><?php echo lang('author') ?></h3>
        </div>
        <?php if (isset($list) && $list): ?>
                <?php foreach ($list as $row): ?>
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="entry-gv">
                            <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>"><img src="<?php echo $row->avatar->url_thumb; ?>" /></a>
                            <p><a href="#"><?php echo $row->name; ?></a></p>
                            <p><?php echo $row->profession; ?></p>
                        </div>
                    </div>

                <?php endforeach; ?>
            <div class="clear"></div>
        <?php else: ?>
            <span class="red"><?php echo lang("have_no_list") ?></span>
        <?php endif; ?>


    </div>
    <!-- paga_navi -->
    <div class="row">
        <div class="auto_check_pages">
            <?php $this->widget->site->pages($pages_config); ?>
        </div>
    </div>
<?php /* ?>
    <!-- slider -->
    <div class="row">
        <div class="col-md-12">
            <h3 class="gv-title">Các khóa học liên quan</h3>
            <div class="slider-content">
                <div class="owl-carousel_gv">
                    <div class="item">
                        <a href=""><img src="avatars/gv-slider-1.jpg" alt=""></a>
                        <p><a href="#">Tiếng pháp</a></p>
                    </div>
                    <div class="item">
                        <a href=""><img src="avatars/gv-slider-2.jpg" alt=""></a>
                        <p><a href="#">Tiếng pháp</a></p>
                    </div>
                    <div class="item">
                        <a href=""><img src="avatars/gv-slider-1.jpg" alt=""></a>
                        <p><a href="#">Tiếng pháp</a></p>
                    </div>
                    <div class="item">
                        <a href=""><img src="avatars/gv-slider-2.jpg" alt=""></a>
                        <p><a href="#">Tiếng pháp</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php */ ?>

    <div class="clearfix"></div>
<?php //echo macro()->page_body_end()  ?>