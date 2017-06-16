<?php if (isset($list) && $list): ?>

    <ul class="cards" id="ud_productimpressiontracker">
        <?php foreach ($list as $row):

            //pr($row);?>
            <li class="card--home">

                <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>" class="fxdc">
                                    <span class="product-thumb">
                                        <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                                             alt="<?php echo $row->name; ?>"/>
									</span>
                                    <span class="card-info fx fxdc">
										<span class="title">
                                            <?php echo $row->name; ?>
										</span>
										<span class="by fx">
											<?php echo $row->_author_name ?>
										</span>
										<span class="card-details fxac">
											<i class="udi udi-users stud-icon"></i>
											<span class="stud-count fx">
												<?php echo number_format($row->count_view) ?> lượt xem
											</span>
											<span class="price"><?php echo $row->_price ?>
											</span>
											<?php if(isset($row->_price_old)): ?>
												<span class="price--old">
												<?php echo $row->_price_old ?>
												</span>
											<?php  endif;?>

										</span>
                                    </span>
                </a>
            </li>

        <?php endforeach; ?>
    </ul>
    <div class="clear"></div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>