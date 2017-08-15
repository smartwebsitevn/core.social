<div class="block block-author">
    <div class="block-content">
        <div class="logo-cty">
            <a href="<?php echo $user->_url_view ?>">
                <img
                    src="<?php echo $user->avatar->url_thumb ?>"> </a>
        </div>
        <?php t('view')->load('tpl::_widget/user/display/item/info_contact', ['row' => $user]) ?>

        <div class="name-cty">
            <a href="<?php echo $user->_url_view ?>"><?php echo $user->name ?></a>
        </div>
        <div class="short-cty">  <?php echo $user->profession ?></div>
        <?php t('view')->load('tpl::_widget/user/display/item/info_meta', ['row' => $user]) ?><br>
        <?php t('view')->load('tpl::_widget/user/display/item/info_tags', ['row' => $user]) ?>
        <hr>
        <div class="item-des">
            <?php echo macro()->more_block($user->desc, 110); ?>
        </div>

    </div>

</div>
