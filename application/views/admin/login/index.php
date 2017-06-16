<?php

$logo = public_url('admin/ekoders/images/logo.png');
$logo_uploaded = setting_get('config-logo_admin');
if ($logo_uploaded) {
    $logo_uploaded = file_get_image_from_name($logo_uploaded);
    if ($logo_uploaded)
        $logo = $logo_uploaded->url;
}
?>
<div class="login-container">
    <h2>
        <img src="<?php echo $logo ?>" alt="logo" class="img-responsive logo">
    </h2>
    <!-- BEGIN LOGIN BOX -->
    <div id="login-box" class="login-box visible">
        <div class="hr hr-8 hr-double dotted"></div>
        <form class="form" id="form" action="<?php echo $action; ?>" method="post">
            <div name="login_error" class="error"></div>
            <div class="form-group">
                <div class="input-icon">
                    <span class="fa fa-key text-gray"></span>
                    <input type="text" name="username" id="param_username" class="form-control"
                           placeholder="<?php echo lang('username') ?>"/>

                    <div name="username_error" class="error"></div>
                </div>
            </div>
            <div class="form-group">
                <div class="input-icon ">
                    <span class="fa fa-lock text-gray"></span>
                    <input type="password" name="password" id="param_password" class="form-control"
                           placeholder="<?php echo lang('password') ?>">

                    <div name="password_error" class="error"></div>
                </div>
            </div>
            <?php if ($matrix): ?>
                <div class="form-group">
                    <label><?php echo lang('matrix_card') ?>:</label>

                    <div class="row">
                        <div class="col-md-6">

                            <font class="fontB f15 pull-left mt5 mr10"><?php echo implode('', $matrix[0]); ?></font>
                            <input type="password" name="matrix_0" class="form-control"
                                   maxlength="<?php echo $matrix_length; ?>" style="width:40px!important;"/>
                        </div>

                        <div class="col-md-6">

                            <font class="fontB f15 pull-left mt5 mr10"><?php echo implode('', $matrix[1]); ?></font>
                            <input type="password" name="matrix_1" class="form-control"
                                   maxlength="<?php echo $matrix_length; ?>" style="width:40px!important;"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <?php $_id = random_string('unique'); ?>
                <div class="input-group">
                    <input type="text" name="security_code" id="param_security_code" class="form-control"
                           placeholder="<?php echo lang('security_code') ?>"/>

                    <div class="input-group-addon" style="padding:0 4px;background:#fff">
                        <img id="<?php echo $_id; ?>" src="<?php echo $captcha; ?>" _captcha="<?php echo $captcha; ?>"
                             class="dInline captcha">
                        <a href="#reset" onclick="change_captcha('<?php echo $_id; ?>'); return false;"
                           title="Reset captcha" class="dInline">
                            <img src="<?php echo public_url('admin'); ?>/images/icons/reset.png" class="dInline"
                                 style="margin:5px;">
                        </a>
                    </div>
                </div>
                <div name="security_code_error" class=" error"></div>
            </div>


            <div class="tcb">
                <label>
                    <input type="checkbox" name="remember" id="param_remember" class="tc">
                    <span class="labels"> <?php echo lang('remember_me') ?></span>
                </label>
                <button class=" btn btn-primary btn-block" type="submit">
                    <i class="fa fa-key"></i>
                    <?php echo lang('button_login') ?>
                </button>
                <div class="clearfix"></div>
            </div>
            <div class="space-4"></div>

            <div class="footer-wrap">
                <?php
                $year_start = 2015;
                $year_cur 	= date('Y', now());
                $year 		= ($year_cur > $year_start) ? $year_start.'-'.$year_cur : $year_start;
                ?>
                <?php echo lang('copyright_full',$year) ?>


                <div class="clearfix"></div>
            </div>


        </form>
    </div>
</div>
	