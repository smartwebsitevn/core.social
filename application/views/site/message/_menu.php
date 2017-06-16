<nav class="navbar-menu">
    <div class="navbar-header">
        <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div  class="collapse navbar-collapse">
        <ul class="nav nav-tabs mb20">
            <li class="<?php echo ($current == 'inbox') ? 'active' : '' ?>"><a  href="<?php echo site_url('message/inbox')?>"><?php echo lang('message_inbox')?></a></li>
            <li class="<?php echo ($current == 'sended') ? 'active' : '' ?>"><a  href="<?php echo site_url('message')?>"><?php echo lang('message_sended')?></a></li>
            <li class="<?php echo ($current == 'send') ? 'active' : '' ?>"><a  href="<?php echo site_url('message/send')?>"><?php echo lang('message_send')?></a></li>
        </ul>
    </div>
</nav>

