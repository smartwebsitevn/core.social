
<?php if (isset($list) && $list):// pr($list);?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):    //pr($row);?>
            <div class="item-blog">
                <?php if(isset($row->_cat) && $row->_cat):?>
                    <?php foreach($row->_cat as $cat):
                        $cat = mod('blog_cat')->add_info_url($cat)?>
                        <div class="item-category">
                            <a href="<?php echo $cat->_url_view ?>" title="<?php echo $cat->name ?>"><?php echo $cat->name ?></a>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
                <div class="item-thumbnail <?php if(!$row->image_id){ echo 'item-thumbnail-no';} ?>">
                    <?php if($row->image_id){ ?>
                        <a href="<?php echo $row->_url_view ?>" title="<?php echo $row->name ?>">
                            <img class="img-responsive" src="<?php echo $row->image->url ?>" alt="<?php echo $row->name?>" onerror="this.src='<?php echo public_url('site/img/no_image.jpg')?>'"/>
                        </a>
                    <?php } ?>
                    <a href="<?php echo $row->_url_view ?>" class="item-title" title="<?php echo $row->name ?>"><?php echo $row->name ?></a>
                </div>
                <div class="item-meta clearfix">
                    <div class="pull-left">
                        <span class="date"><?php //echo $row->_created ?></span>
                        <?php if(isset($row->user_id)){ ?>
                            <span class="author">
							<?php echo lang("by")?>
                                <?php if($row->user_id && $row->_user){ ?>
                                    <span><?php echo $row->_user->name ?></span>
                                <?php } ?>
									</span>
                        <?php } ?>
                    </div>
                    <?php //view('tpl::_widget/blog/share', array('row'=>$row)); ?>
                </div>
                <div class="item-content">
                    <div class="descaption">
                        <?php echo $row->brief ?>
                    </div>
                    <a href="<?php echo $row->_url_view ?>" class="see-more" target="_blank">Xem thêm<?php //echo lang("seemore")?></a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php return ob_get_clean();
    }
    ?>
    <?php if (isset($load_more) && $load_more): ?>
        <?php echo $_data_list(); ?>
    <?php else: ?>

            <?php echo $_data_list() ?>

    <?php endif; ?>
    <?php if (t('input')->is_ajax_request() && isset($pages_config)) : ?>
        <?php widget('blog')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>

