<?php if (isset($list) && $list): ?>
    <ol class="products-list-items">
        <?php foreach ($list as $row):    //pr($row);?>
            <li class="item-product">
                <div class="item-info">
                    <div class="item-photo">
                        <a href="<?php echo $row->_url_view; ?>" class="item-img">
                            <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                                 alt="<?php echo $row->name; ?>">
                        </a>
                    </div>
                    <div class="item-detail">
                        <strong class="item-name"><a href="<?php echo $row->_url_view; ?>"><?php echo $row->name; ?></a></strong>

                        <div class="item-meta"><?php echo character_limiter($row->target, 50); ?>
                        </div>
                        <div class="item-des">
                            <?php echo character_limiter($row->description, 250); ?>
                        </div>
                        <div class="item-actions">
                            <?php if (isset($info->stats_data->lesson) && $info->stats_data->lesson): ?>
                                <i class="fa fa-play-circle" aria-hidden="true"></i><span><?php echo $info->stats_data->lesson ?> Bài giảng</span>
                            <?php endif; ?>
                            <?php if (isset($info->stats_data->time) && $info->stats_data->time): ?>
                                <i class="fa fa-clock-o" aria-hidden="true"></i><span><?php echo $info->stats_data->time ?></span>
                            <?php endif; ?>
                            <?php if (isset($info->stats_data->video) && $info->stats_data->video): ?>
                                <i class="fa fa-sliders" aria-hidden="true"></i><span>All levels</span>
                                <p><?php echo $info->stats_data->video ?> Videos</p>
                            <?php endif; ?>
                        </div>
                        <div class="item-info-price">
                            <?php echo view('tpl::_widget/aboutus/display/item/info_price', array('info' => $row)); ?>
                            <?php echo view('tpl::_widget/aboutus/display/item/info_rate', array('info' => $row)); ?>
                        </div>
                    </div>
                </div>
            </li>


        <?php endforeach; ?>

    </ol>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>