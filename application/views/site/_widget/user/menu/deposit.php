<nav class="navbar-menu">
    <div class="navbar-header">
        <button data-target="#navbar-collapse-tran-bonus" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div id="navbar-collapse-tran-bonus"  class="collapse navbar-collapse">
        <ul class="nav nav-tabs mb20">
            <li class="<?php echo ($current == 'cw') ? 'active' : '' ?>"><a  href="<?php echo site_url('tran_cw/history')?>"><?php echo lang('title_history_cw')?></a></li>
            <li class="<?php echo ($current == 'rw') ? 'active' : '' ?>"><a  href="<?php echo site_url('tran_rw/history')?>"><?php echo lang('title_history_rw')?></a></li>
            <li class="<?php echo ($current == 'mb') ? 'active' : '' ?>"><a  href="<?php echo site_url('tran_mb/history')?>"><?php echo lang('title_history_mb')?></a></li>
        </ul>
    </div>
</nav>

