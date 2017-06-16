<?php if (!$is_login): ?>
    <?php if (mod("user")->setting('register_allow')): ?>
        <li class="dropdown-singup register">
            <a class="cd-signup link-register" href="javascript:void(0)">
                <span class="icon"></span>
                <span class="text"><?php echo lang('button_register'); ?> </span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (mod("user")->setting('login_allow')): ?>

        <li class="dropdown-login login">
            <a class="cd-signin link-login" href="javascript:void(0)">
                <span class="icon"></span>
                <span class="text"><?php echo lang('button_login'); ?></span>
            </a>
        </li>
    <?php endif; ?>

<?php else:// pr($user);?>
    <li class="dropdown-user dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
           aria-expanded="false">
            <span class="caret-user user"></span><span class="name"><?php echo $user->name ?></span><span
                class="caret"></a>
        <ul class="dropdown-menu">
            <?php /* ?>
            <li class="active user">
                <a href="javascript:void(0)">

                    <b class="red">
                        <!--<i class="fa fa fa-money"></i>--> <?php echo ($user->purses->first()->balance_decode > 0) ? $user->purses->first()->format('balance') : '0' ?>
                    </b>
                </a>
            </li>
             <?php */ ?>

            <li class=" cai-dat">
                <a href="<?php echo site_url('user') ?>">
                    <!--<i class="fa fa-user"></i>--> <?php echo lang('user_panel_user') ?></a>
            </li>
            <?php if (!mod("product")->setting('turn_off_function_order')): ?>
                <li class=" dang-ky">
                    <a href="<?php echo site_url('my-favorited') ?>">
                        <!--<i class="fa fa-user"></i>--> <?php echo lang('user_panel_product_favorited') ?></a>
                </li>
            <?php endif; ?>
            <?php /* ?>
            <li class=" dang-ky">
                <a href="<?php echo site_url('deposit_card') ?>"><!--<i
                        class="fa fa-bank"></i>--> <?php echo lang('user_panel_deposit') ?></a>
            </li>
     <?php */ ?>

            <li class=" dang-ky">
                <a href="<?php echo site_url('invoice_order') ?>"><!--<i
                        class="fa fa-history"></i>--> <?php echo lang('user_panel_tran') ?></a>
            </li>
            <li class=" log-out">
                <a href="<?php echo $user->_url_logout; ?>"
                    ><!--<i class="fa fa-share"></i> --><?php echo lang('button_logout'); ?></a>
            </li>

        </ul>
    </li>


<?php endif; ?>
