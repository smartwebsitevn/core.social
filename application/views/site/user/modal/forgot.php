<div class="modal fade" id="forgot-password-modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                <h3 class="modal-title">Lấy lại mật khẩu</h3>
            </div>
            <div class="modal-body">
                <?php view('tpl::user/_common/forgot', array("user" => $user)) ?>
            </div>
        </div>
    </div>
</div>
