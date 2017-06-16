<div class="list-blog">
    <div class="item-blog">
        <?php if ($info->_cat): ?>
            <?php foreach ($info->_cat as $cat): ?>
                <div class="item-category">
                    <a href="<?php echo $cat->_url_view ?>"
                       title="<?php echo $cat->name ?>"><?php echo $cat->name ?></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php
        if ($info->image_id) {
            ?>
            <div class="item-thumbnail">
                <a href="<?php echo $info->_url_view ?>" title="<?php echo $info->name ?>"><img
                        src="<?php echo $info->image->url ?>" align="<?php echo $info->name ?>"></a>
            </div>
        <?php } ?>
        <div class="item-meta clearfix">
            <div class="pull-left">
                <span class="date"><?php //echo $info->_created ?></span>
                <?php if (isset($info->user_id) && $info->user_id) { ?>
                    <span class="author">
                            <?php echo lang("by") ?>
                        <?php if ($info->user_id && $info->_user) { ?>
                            <span><?php echo $info->_user->name ?></span>
                        <?php } ?>
                        </span>
                <?php } ?>
            </div>
            <?php //view('tpl::_widget/blog/share', array('row'=>$info)); ?>
        </div>
        <div class="item-content">
            <div class="descaption">
                <?php
                 $content =handle_content($info->description, 'output');
                 $content = html_entity_decode($content);
                 echo $content;
                ?>
            </div>
            <hr>
            <?php if (isset($info->user_id) && $info->user_id) { ?>
                <div class="item-tac-gia clearfix">
                    <?php if ($info->_user->avatar) { ?>
                        <div class="item-thumbnail">
                            <a class="img" href="<?php echo $info->_url_author_view ?>"
                               title="<?php echo $info->_user->name ?>">
                                <img class="img-responsive img-re" src="<?php echo $info->_user->avatar->url_thumb ?>"
                                     alt="<?php echo $info->_user->name ?>"
                                     onerror="this.src='<?php echo public_url('site/img/no_image.jpg') ?>'"/>
                            </a>
                            <button type="button" class="btn btn-outline btn-follow" id="follow"
                                    data-complete-text="Unfollow"><?php echo lang("follow") ?></button>
                        </div>
                    <?php } ?>
                    <div class="item-content">
                        <a class="item-name" href="<?php echo $info->_url_author_view ?>"
                           title="<?php echo $info->_user->name ?>"><?php echo $info->_user->name ?></a>

                        <div class="author"><?php echo $info->_content->author_pos ?></div>
                        <div class="descaption">
                            <?php echo handle_content($info->_content->author_intro, 'output'); ?>
                        </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
            <div id="comments">
                <div class="fb-comments"
                     data-href="<?php echo $info->_url_view ?>"
                     data-numposts="5" data-width="100%">
                </div>
            </div>
        </div>
    </div>
</div>
