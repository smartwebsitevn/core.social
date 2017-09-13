<div class="modal fade" id="register-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" class="close" data-dismiss="modal" type="button">×</button>
                <h3 class="modal-title">Đăng ký</h3>
            </div>
            <div class="modal-body">
                <?php view('tpl::user/_common/register', array("user" => $user)) ?>
            </div>
        </div>
    </div>
</div>
