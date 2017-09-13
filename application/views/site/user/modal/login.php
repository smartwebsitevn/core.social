<div class="modal fade" id="login-modal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                <h3 class="modal-title">Đăng nhập</h3>
            </div>
            <div class="modal-body">
                <?php view('tpl::user/_common/login', array("user" => $user)) ?>

            </div>
        </div>
    </div>
</div>

