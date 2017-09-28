<div id="account-info" class="panel">
    <?php t('view')->load('tpl::user_account/info') ?>
</div>
<div id="account-info-edit" class="panel" style="display: none">
    <?php t('view')->load('tpl::user_account/edit_info') ?>
</div>
<?php /* ?>
<div id="account-setting" class="panel">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h1 class="panel-title">Cho phép tìm kiếm hồ sơ</h1>
            </div>
        </div>

    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8">
                <b>Bạn đang cho phép tìm kiếm hồ sơ<?php //echo lang('setting'); ?></b>
            </div>
            <div class="col-md-4 text-right">
                <a  class="btn btn-outline btn-sm show-account-setting-edit " href="#0">Chỉnh sửa</a>
            </div>
        </div>
    </div>
</div>
<div id="account-setting-edit" class="panel" style="display: none">
    <?php t('view')->load('tpl::user_account/edit_setting') ?>
</div>
 <?php */ ?>

<div id="account-password" class="panel">

    <div class="panel-heading">
        <div class="row">
            <div class="col-md-6">
                <h1 class="panel-title">Thay đổi mật khẩu</h1>
            </div>
        </div>

    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <b>Mật khẩu<?php //echo lang('setting'); ?></b>
            </div>
            <div class="col-md-3">
                <?php echo '******'; ?>
            </div>
            <div class="col-md-6 text-right">
                <a class="btn btn-outline btn-sm show-account-password-edit" href="#0">Chỉnh sửa</a>

            </div>
        </div>
    </div>
</div>
<div id="account-password-edit" class="panel" style="display: none">
    <?php t('view')->load('tpl::user_account/edit_password') ?>
</div>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $(".show-account-info-edit").click(function () {
                $("#account-info").hide();
                $("#account-info-edit").show();
                return false;
            });
            $(".show-account-info").click(function () {
                $("#account-info").show();
                $("#account-info-edit").hide();
                return false;
            });
            $(".show-account-setting-edit").click(function () {
                $("#account-setting").hide();
                $("#account-setting-edit").show();
                return false;
            });
            $(".show-account-setting").click(function () {
                $("#account-setting").show();
                $("#account-setting-edit").hide();
                return false;
            });

            $(".show-account-password-edit").click(function () {
                $("#account-password").hide();
                $("#account-password-edit").show();
                return false;
            });
            $(".show-account-password").click(function () {
                $("#account-password").show();
                $("#account-password-edit").hide();
                return false;
            });
        });
    })(jQuery);
</script>