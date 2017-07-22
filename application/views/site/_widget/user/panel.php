<?php if (!$is_login): ?>
    <?php if (mod("user")->setting('register_allow')): ?>
        <li class="dropdown-singup register">
            <a class="cd-signup link-register" href="javascript:void(0)">
                <span class="text ">
                    <i class="fa fa-pencil f16" ></i>
                    <?php echo lang('button_register'); ?> </span>
            </a>
        </li>
    <?php endif; ?>
    <?php if (mod("user")->setting('login_allow')): ?>

        <li class="dropdown-login login">
            <a class="cd-signin link-login" href="javascript:void(0)">
                <span class="text ">
                    <i class="fa fa-sign-in f18" ></i>
                    <?php echo lang('button_login'); ?></span>
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
            <li>
                <a href="<?php echo site_url('my-page') ?>">
                    <i class="pe-7s-home " ></i> <?php echo lang('user_panel_my_page') ?></a>
            </li>
    <?php /* ?>

            <li >
                <a href="<?php echo site_url('my-posts') ?>">
                    <i class="pe-7s-note2 " ></i> <?php echo lang('user_panel_my_posts') ?></a>
            </li>
     <?php */ ?>
            <li >
                <a href="<?php echo site_url('my-account') ?>">
                    <i class="pe-7s-config " ></i> <?php echo lang('user_panel_my_account') ?></a>
            </li>

            <li>
                <a href="<?php echo site_url('my-balance') ?>">
                    <i class="pe-7s-credit " ></i>  <?php echo lang('user_panel_my_balance') ?></a>
            </li>
            <li>
                <a href="<?php echo $user->_url_logout; ?>">
                    <i class="pe-7s-power " ></i> <?php echo lang('button_logout'); ?></a>
            </li>

        </ul>
    </li>


<?php endif; ?>
