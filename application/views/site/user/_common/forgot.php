<form class="form_action" action="<?php echo $user->_url_forgot; ?>">
    <div class="forgot-password-content">
        <div class="form-control-wrapper">
            <input class="form-control" name="email_valid" placeholder="Email đăng nhập"
                   required="true" type="email">

            <div class="clear"></div>
            <span name="email_valid_error" class="error"></span>
        </div>
        <div class="form-control-wrapper">
            <button class="btn" type="submit">Đặt lại mật khẩu</button>
        </div>
        <div class="form-control-wrapper">
            <span>Hoặc</span>
        </div>
        <div class="form-control-wrapper">
            <a class="btn-link" data-dismiss="modal" data-target="#login-modal" data-toggle="modal">Đăng
                nhập</a>
        </div>
    </div>
</form>