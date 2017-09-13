<form method="POST" action="<?php echo $user->_url_login ?>" accept-charset="UTF-8"
      class="cd-form form_action">
    <input name="return" type="hidden" value="<?php echo current_url(1) ?>">

    <p class="sub-title">Đăng nhập bằng tài khoản trên site</p>

    <div name="login_error" class="alert alert-danger" role="alert" style=" display:none;"></div>

    <div class="form-control-wrapper">
        <input class="form-control" name="email"
               placeholder="Email" required="true"
               value="" type="text">

        <div class="clear"></div>
        <span name="email_error" class="error"></span>
    </div>
    <div class="form-control-wrapper">
        <input class="form-control" name="password"
               placeholder="Mật khẩu" required="true"
               value="" type="password">

        <div class="clear"></div>
        <span name="password_error" class="error"></span>
    </div>

    <div class="form-control-wrapper">
        <button class="btn btn-login-submit" type="submit">Đăng nhập</button>
    </div>


    <p class="forgot-password-link">
        <a class="btn-link" data-dismiss="modal" data-target="#forgot-password-modal"
           data-toggle="modal"
           href="#" id="forgot-password-button">Quên mật khẩu đăng nhập?</a>
    </p>
</form>
<div class="modal-footer">
    <?php view('tpl::user/_common/oauth', array("user" => $user, 'title' => 'Đăng nhập')) ?>
    <?php if (mod("user")->setting('register_allow')): ?>
        <div class="bottom-text">
            Chưa có tài khoản?
            <a class="btn-link" data-dismiss="modal" data-target="#register-modal" data-toggle="modal"
               href="#">Đăng ký</a>
        </div>
    <?php endif; ?>
</div>