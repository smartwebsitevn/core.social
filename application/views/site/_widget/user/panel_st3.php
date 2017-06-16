<?php /*if (!$is_login): ?>
    <ul class="nav-action">
        <li><a class="cd-signin " href="javascript:void(0)">Đăng nhập</a></li>
        <li><a class="cd-signup" href="javascript:void(0)">Đăng ký</a></li>
    </ul>
<?php else:// pr($user);?>
    <ul class="nav-action">
        <li><a href="<?php echo site_url('user'); ?>">
                <?php echo lang('hello'); ?>:
                <b><?php echo $user->name; ?></b>
            </a></li>

        <li>
            <a href="<?php echo $user->_url_logout ?>">
                <?php echo lang('button_logout'); ?>
            </a>
        </li>
    </ul>
<?php endif; */ ?>


<?php if (!$is_login): ?>
    <a class="cd-signin " href="javascript:void(0)"><img
            src="<?php echo public_url("site/style") ?>/images/user-icon.png"/> <?php echo lang('button_login'); ?></a>
    <a class="cd-signup " href="javascript:void(0)"><img
            src="<?php echo public_url("site/style") ?>/images/signup-icon.png"/> <?php echo lang('button_register'); ?>
    </a>
<?php else:// pr($user);?>
    <a href="<?php echo site_url('user'); ?>"><img
            src="<?php echo public_url("site/style") ?>/images/user-icon.png"/> <?php echo lang('hello'); ?>
        :<?php echo $user->name; ?></a>
    <a href="<?php echo $user->_url_logout ?>">
        <?php echo lang('button_logout'); ?>
    </a>
<?php endif; ?>
