<?php if (isset($list) && $list): ?>
    <div class="slide-blog">
    <div class="owl-carousel">
        <?php foreach($list as $row){?>
            <div class="item-category-blog">
                <div class="img">
                    <a href="<?php echo $row->_url_view?>" title="<?php echo $row->name ?>">
                        <img class="img-responsive" src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>">
                    </a>
                </div>
                <div class="caption">
                    <a href="<?php echo $row->_url_view ?>" class="name" title="<?php echo $row->name ?>"><?php echo $row->name ?></a>
                    <span><?php //echo $row->total ?></span>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>