<?php if (isset($list) && $list): ?>
    <?php foreach ($list as $row):    //pr($row);?>
        <div class="item-kh">
            <div class="item-info">
                <div class="item-photo">
                    <a href="<?php echo $row->_url_view; ?>" class="item-img">
                        <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                             alt="<?php echo $row->name; ?>"/>
                    </a>
                    <?php echo view('tpl::_widget/product/display/item/info_label', array('row' => $row)); ?>
                </div>
                <div class="item-detail">
                    <strong class="item-name"><a
                            href="<?php echo $row->_url_view; ?>"> <?php echo $row->name; ?></a>
                        <?php echo widget('product')->action_favorite($row) ?>
                    </strong>

                    <div class="item-author"><?php echo character_limiter($row->brief, 50); ?></div>
                    <?php //echo view('tpl::_widget/product/display/item/info_rate', array('row' => $row)); ?>
                    <?php echo view('tpl::_widget/product/display/item/info_price', array('row' => $row)); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>