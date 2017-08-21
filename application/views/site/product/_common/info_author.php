<?php if (isset($info->_author) && $info->_author): ?>
    <?php
    //pr($info->_author);
    $_data_author = function ($author) {
        // pr($author);
        //$author =user_add_info($author);
        //$author =user_add_info_other($author);
        ob_start() ?>
        <div class="block block-author">
            <div class="block-content">
                <div class="logo-cty">
                    <a href="<?php echo $author->_url_view ?>">
                        <img
                            src="<?php echo $author->avatar->url_thumb ?>"> </a>

                </div>
                <?php t('view')->load('tpl::_widget/user/display/item/info_contact',['row'=>$author]) ?>

                <div class="name-cty">
                    <a href="<?php echo $author->_url_view ?>"><?php echo $author->name ?></a>
                </div>
                <div class="short-cty">  <?php echo $author->profession ?></div>
                <div class="item-meta">
                    <?php t('view')->load('tpl::_widget/user/display/item/info_meta',['row'=>$author]) ?><br>
                </div>

                <?php t('view')->load('tpl::_widget/user/display/item/info_tags',['row'=>$author]) ?>

                <hr>
                <?php widget('user')->action_subscribe($author) ?>
                <?php widget('user')->action_message($author) ?>
                <hr>

                <div class="item-des">
                    <?php echo macro()->more_block($author->desc,110); ?>
                </div>

            </div>

        </div>

        <?php return ob_get_clean();
    };
    ?>
    <?php echo $_data_author($info->_author); ?>

<?php endif; ?>