<?php if (isset($info) ): ?>
    <div class="item-comments media-list comment-list">
        <div class="comment-list-wraper">
            <?php echo t('view')->load('tpl::_widget/product/comment/_common/form', ['info' => $info, 'user' => $user]); ?>
            <?php echo t('view')->load('tpl::_widget/product/comment/_common/list', ['info' => $info, 'list' => $list]); ?>
        </div>
    </div>
<?php endif; ?>