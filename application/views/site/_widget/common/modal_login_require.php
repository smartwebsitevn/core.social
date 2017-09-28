<div id="modal-login-require" class="modal fade"  tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('notice'); ?></h4>
            </div>
            <div class="modal-body">
               <?php echo lang("notice_please_login_to_use_function") ?>
            </div>
            <div class="modal-footer">
                <a  data-dismiss="modal"  class="btn">Hủy bỏ<?php //echo $this->lang->line('Ok'); ?></a>
                <a data-dismiss="modal"  data-toggle="modal" data-target="#modal-login" href="#" class="btn btn-default">Đăng nhập<?php //echo $this->lang->line('Ok'); ?></a>
                <?php /* ?>
                 <a type="button" href="<?php echo site_url("login")?>" class="btn btn-danger">Đăng nhập<?php //echo $this->lang->line('Ok'); ?></a>

           <?php */ ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->