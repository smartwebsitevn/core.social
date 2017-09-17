<?php if (!$is_login): ?>
    <?php if (mod("user")->setting('login_allow')): ?>
        <li class="dropdown-login login">
            <a data-dismiss="modal" data-target="#login-modal" data-toggle="modal" href="#0" class="link-login">
                <span class="text ">
                    <i class="fa fa-sign-in f18"></i>
                    <?php echo lang('button_login'); ?></span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (mod("user")->setting('register_allow')): ?>
        <li class="dropdown-singup register">
            <a data-dismiss="modal" data-target="#register-modal" data-toggle="modal" href="#0" class="link-register">
                <span class="text ">
                    <i class="fa fa-pencil f16"></i>
                    <?php echo lang('button_register'); ?> </span>
            </a>
        </li>
    <?php endif; ?>

<?php else:// pr($user);?>
    <li>
        <a href="<?php echo site_url('my-page') ?>"  >
            <i class="pe-7s-home" ></i> Trang của tôi"
        </a>
    </li>
    <?php /* ?>

            <li class="active user">
                <a href="javascript:void(0)">

                    <b class="red">
                        <!--<i class="fa fa fa-money"></i>--> <?php echo ($user->purses->first()->balance_decode > 0) ? $user->purses->first()->format('balance') : '0' ?>
                    </b>
                </a>
            </li>
            <li>
                <a href="<?php echo site_url('my-page') ?>">
                    <i class="pe-7s-home "></i> <?php echo lang('user_panel_my_page') ?></a>
            </li>
            <li >
                <a href="<?php echo site_url('my-posts') ?>">
                    <i class="pe-7s-note2 " ></i> <?php echo lang('user_panel_my_posts') ?></a>
            </li>
     <?php */ ?>
    <li>
        <a href="<?php echo site_url('my-account') ?>">
            <i class="pe-7s-config "></i> <?php echo lang('user_panel_my_account') ?></a>
    </li>

    <li>
        <a href="<?php echo site_url('my-balance') ?>">
            <i class="pe-7s-credit "></i> <?php echo lang('user_panel_my_balance') ?></a>
    </li>
    <li>
        <a href="<?php echo $user->_url_logout; ?>">
            <i class="pe-7s-power "></i> <?php echo lang('button_logout'); ?></a>
    </li>


<?php endif; ?>
