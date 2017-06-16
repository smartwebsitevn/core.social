<?php if (isset($list) && $list): ?>
    <?php foreach ($list as $row):    //pr($row);?>
        <div class="media">
            <div class="media-left">
                <a href="<?php echo $row->_url_view; ?>">
                    <img class="media-object" src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>" alt="<?php echo $row->name; ?>">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><?php echo $row->name; ?></h4>
                <div class="media-content" >
                    <?php echo character_limiter($row->brief,500); ?><br>

                </div>
                <a class="readmore " href="<?php echo $row->_url_view; ?>">
                    Xem thêm >
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>