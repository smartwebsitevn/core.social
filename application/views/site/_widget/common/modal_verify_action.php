<div id="modal-verify-action" class="modal fade" data-keyboard="false" data-backdrop="static"  tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo $this->lang->line('notice'); ?></h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn thực hiện hành động này?</p>
            </div>
            <div id="modal-verify-action-load" class="form_load"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger accept-action"><?php echo $this->lang->line('button_accept'); ?></button>
                <button type="button" class="btn  cancel-action"><?php echo $this->lang->line('button_cancel'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->