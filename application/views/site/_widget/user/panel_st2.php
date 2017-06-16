<?php if (!$is_login): ?>
        <div class="dropdown dropdown--login">
            <div class="dropdown__toggle">
                <a class="cd-signin dropdown__main-link"  href="javascript:void(0)"  >Login</a>
            </div>
        </div>
        <div class="dropdown dropdown--signup">
            <div class="dropdown__toggle">
                <a class="cd-signup dropdown__main-link" href="javascript:void(0)" >Sign up</a>
            </div>
        </div>
<?php else:// pr($user);?>
    <div class="dropdown dropdown--ufb">
        <div class="dropdown__toggle">
            <a href="<?php echo site_url('user');  ?>" class="dropdown__main-link">
                <?php echo lang('hello');  ?>:
                <b><?php echo $user->name;  ?></b>
            </a>
        </div>
    </div>
    <div class="dropdown dropdown--ufb">
        <div class="dropdown__toggle">
            <a href="<?php echo $user->_url_logout?>" class="dropdown__main-link">
                <?php echo lang('button_logout');  ?>
            </a>
        </div>
    </div>
<?php endif; ?>
