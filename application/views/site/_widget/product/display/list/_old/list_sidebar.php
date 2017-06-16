<?php if (isset($list) && $list): ?>
    <ul class="list-product">
    <?php foreach ($list as $row):    //pr($row);?>
        <li>
            <a href="<?php echo $row->_url_view; ?>" class="images-list-widget pull-left">
                <img  src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>" alt="<?php echo $row->name; ?>" /></a>
					<a href="<?php echo $row->_url_view; ?>" class="title-list-widget">
                <?php echo $row->name; ?><!--<br>TỪ 6-12 TUỔI--></a>
            <p>
                Giá: <span><?php echo $row->_price ?></span>
                <?php if(isset($row->_price_old)): ?>
                    <span class="price--old">
												<?php echo $row->_price_old ?>
												</span>
                <?php  endif;?>
            </p>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>