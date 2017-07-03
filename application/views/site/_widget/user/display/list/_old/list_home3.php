<?php if (isset($list) && $list): ?>
    <?php foreach ($list as $row):    //pr($row);?>
        <div class="col-xs-6 col-md-3 user">
            <a href="<?php echo $row->_url_view; ?>" class="image">
                <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                     alt="<?php echo $row->name; ?>"/></a>
            <a href="<?php echo $row->_url_view; ?>" class="title">
                <?php echo $row->name; ?><!--<br>TỪ 6-12 TUỔI--></a>

            <div class="price">
                Giá: <span><?php echo $row->_price ?></span>
                <?php if(isset($row->_price_old)): ?>
                    <span class="price--old">
												<?php echo $row->_price_old ?>
												</span>
                <?php  endif;?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>