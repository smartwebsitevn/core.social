<?php if (isset($info)): ?>

    <?php if (isset($load_more) && $load_more): ?>
        <?php echo t('view')->load('tpl::_widget/product/comment/_common/list', ['info' => $info,'list' => $list]); ?>
        <?php echo t('view')->load('tpl::_widget/product/comment/_common/pagination', ['pages_config' => isset($pages_config)?$pages_config:null]); ?>
    <?php else: ?>
        <div class="item-comments media-list comment-list">
            <div class="comment-list-wraper">
                <?php echo t('view')->load('tpl::_widget/product/comment/_common/form', ['info' => $info,'user' => $user]); ?>
                <?php echo t('view')->load('tpl::_widget/product/comment/_common/list', ['info' => $info,'list' => $list]); ?>
                <?php echo t('view')->load('tpl::_widget/product/comment/_common/pagination', ['pages_config' => isset($pages_config)?$pages_config:null]); ?>

            </div>
        </div>
    <?php endif; ?>
    <?php echo t('view')->load('tpl::_widget/product/comment/_common/facebook', ['info' => $info]); ?>

    <?php widget('site')->js_reboot(); ?>

<?php endif; ?>