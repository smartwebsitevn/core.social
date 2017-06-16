<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
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
                            <?php if (isset($row->stats_data->lesson) && $row->stats_data->lesson): ?>
                                <i class="fa fa-play-circle" aria-hidden="true"></i>
                                <span><?php echo $row->stats_data->lesson ?> Bài giảng</span>
                            <?php endif; ?>
                            <?php if (isset($row->stats_data->time) && $row->stats_data->time): ?>
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span><?php echo $row->stats_data->time ?></span>
                            <?php endif; ?>
                            <?php if (isset($row->stats_data->video) && $row->stats_data->video): ?>
                                <i class="fa fa-sliders" aria-hidden="true"></i><span>All levels</span>
                                <p><?php echo $row->stats_data->video ?> Videos</p>
                            <?php endif; ?>
                        </div>
                        <div class="item-info-price">

                            <?php echo view('tpl::_widget/product/display/item/info_price', array('row' => $row)); ?>
                            <?php echo view('tpl::_widget/product/display/item/info_rate', array('row' => $row)); ?>
                        </div>
                    </div>
                </div>
            </li>


        <?php endforeach; ?>
        <?php return ob_get_clean();
    }
    ?>
    <?php if (isset($load_more) && $load_more): ?>
        <?php echo $_data_list(); ?>
    <?php else: ?>
        <ol class="products-list-items">
            <?php echo $_data_list() ?>
        </ol>
    <?php endif; ?>
    <?php if (t('input')->is_ajax_request() && isset($pages_config)) : ?>
        <?php widget('product')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>

