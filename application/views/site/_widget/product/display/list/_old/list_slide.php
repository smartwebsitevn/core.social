<?php //pr($list);
if (isset($list) && $list): ?>
    <ul class=" channel-products-list one-line caroulsel-bxslider">

        <?php foreach ($list as $row): //pr($row);

            ?>
            <li class="product-box small promotion ">
                <a href="<?php echo $row->_url_view; ?>" class="mask">
										<span class="product-thumb pos-r dib">

											<?php view('tpl::_widget/product/display/item/info_stats', array('info' => $row)); ?>
                                            <?php view('tpl::_widget/product/display/item/info_label', array('info' => $row)); ?>
                                            <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"/>
                                            <?php view('tpl::_widget/product/display/item/info_author', array('info' => $row)); ?>


										</span>

										<span class="box-second-row">
											<span class="title"> <?php echo $row->name; ?></span>
											<span
                                                class="dib title ins-title"> <?php echo character_limiter($row->target, 50); ?></span>

                                            <?php view('tpl::_widget/product/display/item/info_rate', array('info' => $row)); ?>


                                            <span class="price-wrap fxac pl10">
												<span>
													<span class="price"><?php echo $row->_price ?></span>
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
    <div style="height: 40px"></div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>