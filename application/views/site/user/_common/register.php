<form method="POST" action="<?php echo $user->_url_register ?>" accept-charset="UTF-8"
      class="form_action">
    <p class="sub-title">Đăng ký bằng email</p>

    <div class="form-control-wrapper">
        <input class="form-control" name="name" placeholder="Họ và tên" required="true"
               value="" type="text">

        <div class="clear"></div>
        <span name="name_error" class="error"></span>
    </div>
    <?php /* ?>
                    <div class="form-control-wrapper">
                        <input class="form-control"  name="username" placeholder="Username" required="true"
                               value="" type="text">
                        <div class="clear"></div>
                        <span name="username_error" class="error"></span>
                    </div>

                        <div class="form-control-wrapper">
                        <input class="form-control"  name="phone" placeholder="Số điện thoại" required="true"
                               value="" type="tel">
                        <div class="clear"></div>
                        <span name="phone_error" class="error"></span>
                    </div>
                    <?php */ ?>

    <div class="form-control-wrapper">
        <input class="form-control" name="email" placeholder="Email" required="true"
               value="" type="email">

        <div class="clear"></div>
        <span name="email_error" class="error"></span>
    </div>
    <div class="form-control-wrapper">
        <input class="form-control" name="password" placeholder="Mật khẩu"
               required="true" type="password">

        <div class="clear"></div>
        <span name="password_error" class="error"></span>
    </div>
    <div class="form-control-wrapper">
        <input class="form-control" name="password_repeat" placeholder="Nhập lại mật khẩu"
               required="true" type="password">

        <div class="clear"></div>
        <span name="password_repeat_error" class="error"></span>
    </div>
    <?php /* ?>

                    <div class="form-control-wrapper">
                        <input class="form-control"  name="pin" placeholder="Mật khẩu cấp 2" required="true"
                               value="" type="text">
                        <div class="clear"></div>
                        <span name="pin_error" class="error"></span>
                    </div>
                        <div class="form-control-wrapper">
                        <input class="form-control"  name="pin_repeat" placeholder="Nhập lại mật khẩu cấp 2" required="true"
                               value="" type="text">
                        <div class="clear"></div>
                        <span name="pin_repeat_error" class="error"></span>
                    </div>
                     <?php */ ?>

    <div class="form-control-wrapper">
        <input class="btn btn-login-submit" value="Đăng ký" type="submit">
    </div>
</form>
<div class="modal-footer">
    <?php view('tpl::user/_common/oauth', array("user" => $user, 'title' => 'Đăng ký')) ?>
    <div class="bottom-text">
        Đã có tài khoản?
        <a class="btn-link" data-dismiss="modal" data-target="#modal-login" data-toggle="modal"
           href="#">Đăng
            nhập</a>
    </div>
</div>