<?php if (isset($list) && $list): ?>
    <div class="list-user-feature">
        <?php foreach ($list as $row):    //pr($row);?>
            <div class="item-user">
                <div class="item-photo">
                    <a href="<?php echo $row->_url_view; ?>" class="item-img">
                        <img src="<?php echo $row->avatar->url_thumb ?>"
                             alt="<?php echo $row->name; ?>">
                    </a>
                </div>
                <div class="item-info">
                    <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $row)); ?>
                    <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                            <?php echo $row->name; ?></a>


                    </div>
                    <div class="item-meta">
                        <span
                            class="posts"> <b><?php echo number_format($row->post_total) ?></b> <?php echo lang("count_post") ?></span>

<span
    class="points"> <b><?php echo number_format($row->vote_total) ?></b> <?php echo lang("count_point") ?></span>
<span
    class="follows"> <b><?php echo number_format($row->follow_total) ?></b> <?php echo lang("count_follow") ?></span>


                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>