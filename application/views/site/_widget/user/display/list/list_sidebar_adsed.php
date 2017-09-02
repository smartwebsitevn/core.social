<?php if (isset($list) && $list): ?>
    <div class="sticky-element"  data-spacing="65" data-limiter="#footer">
        <div class="panel">
            <div class="panel-heading">
                Được đề xuất
            </div>
            <div class="panel-body" >
                <div class="slimscroll" data-height="90vh">

                    <div class="list-user-feature">
                        <?php foreach ($list as $row):    //pr($row);?>
                            <div class="item-user">
                                <div class="item-photo">
                                    <?php echo view('tpl::_widget/user/display/item/info_avatar', array('row' => $row)); ?>
                                </div>
                                <div class="item-info">
                                    <?php //echo t('view')->load('tpl::_widget/user/display/item/info_label', array('row' => $row)); ?>
                                    <div class="item-name"><a href="<?php echo $row->_url_view; ?>">
                                            <?php echo $row->name; ?></a>
                                    </div>
                                    <div class="item-meta">
                                        <?php echo $row->profession; ?>
                                    </div>
                                </div>
                                <?php widget('user')->action_subscribe($row,'subscribe_min') ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>