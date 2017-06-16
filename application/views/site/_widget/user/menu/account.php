<ul class="nav nav-tabs mb20">
        <li class="<?php echo ($current == 'edit') ? 'active' : '' ?>"><a  href="<?php echo site_url('user/edit')?>"><?php echo lang('title_account')?></a></li>
        <li class="<?php echo ($current == 'change_pass') ? 'active' : '' ?>"><a  href="<?php echo site_url('user_security/change_pass')?>"><?php echo lang('title_login_password')?></a></li>
        <li class="<?php echo ($current == 'change_pin') ? 'active' : '' ?>"><a  href="<?php echo site_url('user_security/change_pin')?>"><?php echo lang('title_transaction_password')?></a></li>
       <?php /* ?>
        <li class="<?php echo ($current == 'status') ? 'active' : '' ?>"><a href="<?php echo site_url('user/status')?>"><?php echo lang('title_account_status')?></a></li>
        <?php  */?>

</ul>
