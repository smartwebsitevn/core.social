<?php if (isset($list) && $list): ?>
    <?php $user_selected= t('input')->get('user_id') ?>
    <div class="list-user-feature">
        <?php foreach ($list as $row):    //pr($row);?>
            <div class="item-user  act-input <?php echo $user_selected == $row->id?'active':'' ?>" data-name="user_id" data-value="<?php echo $row->id ?>">
                <div class="item-photo">
                    <?php echo view('tpl::_widget/user/display/item/info_avatar', array('row' => $row)); ?>
                </div>
                <div class="item-info">
                    <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $row)); ?>
                    <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                            <?php echo $row->name; ?></a>


                    </div>
                    <div class="item-meta">
                        <span class="posts"> <b><?php echo number_format($row->post_total) ?></b> <?php echo lang("count_post") ?></span>
                        <span class="points"> <b><?php echo number_format($row->point_total) ?></b> <?php echo lang("count_point") ?></span>
                        <span class="follows"> <b><?php echo number_format($row->follow_total) ?></b> <?php echo lang("count_follow") ?></span>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>