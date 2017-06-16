<div id="modal-cart" class="modal fade"  tabindex="-1" role="dialog" >
    <div class="modal-dialog" style="width: <?php echo $count>0?"90":"40"?>%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Giỏ hàng<?php //echo $this->lang->line('notice'); ?></h4>
            </div>
            <div class="modal-body">
                <?php if($count): ?>
                <?php t('view')->load('tpl::_widget/product/cart/list/list_default') ?>
                <?php else: ?>
                Giỏ hàng trống
                <?php endif; ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->