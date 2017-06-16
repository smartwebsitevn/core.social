<div id="modal-balance-deposit" class="modal fade"  tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('notice'); ?></h4>
            </div>
            <div class="modal-body">
                Số dư không đủ để thực hiện giao dịch, vui lòng nạp tiền vào tài khoản.
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal"  class="btn">Hủy bỏ<?php //echo $this->lang->line('Ok'); ?></button>
                <a type="button" href="<?php echo site_url("deposit_card")?>" class="btn btn-danger">Nạp tiền vào tài khoản<?php //echo $this->lang->line('Ok'); ?></a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->