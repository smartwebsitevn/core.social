<?php if (isset($list) && $list): ?>
    <?php foreach ($list as $row):    //pr($row);?>
        <div class="item-kh">
            <div class="item-info">
                <div class="item-photo">
                    <a href="<?php echo $row->_url_view; ?>" class="item-img">
                        <img src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>"
                             alt="<?php echo $row->name; ?>">
                    </a>
                    <?php echo view('tpl::_widget/blog_author/display/item/info_label', array('info' => $row)); ?>
                    <?php echo view('tpl::_widget/blog_author/display/item/info_author', array('info' => $row)); ?>
                    <?php echo view('tpl::_widget/blog_author/display/item/info_stats', array('info' => $row)); ?>
                </div>
                <div class="item-detail">
                    <strong class="item-name"><a href=""> <?php echo $row->name; ?></a></strong>
                    <div class="item-author"><?php echo $row->_author_name;;//character_limiter($row->target, 50); ?></div>

                    <?php echo view('tpl::_widget/blog_author/display/item/info_rate', array('info' => $row)); ?>
                    <?php echo view('tpl::_widget/blog_author/display/item/info_price', array('info' => $row)); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>