<form action="<?php echo current_url() ?>" method="post" data-postformdata="<?php echo current_url()?>">
    <p class="text-danger box-danger"></p>
    <div class="box-success">
        <div class="alert alert-warning" role="alert"><?php echo lang("notice_first_download")?></div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label><?php echo lang("name")?> <span class="red">*</span>:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="required form-control" name="name">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo lang("email")?> <span class="red">*</span>:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
                        </span>
                        <input type="email" class="required form-control" name="email">
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label><?php echo lang("phone")?> <span class="red">*</span>:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" name="phone">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo lang("address")?>:</label>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" name="address">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label><?php echo lang("content")?>:</label>
            <textarea name="content" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label><?php echo lang("security_code")?> <span class="red">*</span>:</label>
            <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-qrcode" aria-hidden="true"></i>
            </span>
                <input id="security_code" type="text" class="form-control input-sm required" value="" name="security_code"  placeholders="<?php echo lang('please_enter').lang('security_code')?>">
                <img id="register_captcha" src="<?php echo $captcha; ?>" _captcha="<?php echo $captcha; ?>" class="dInline">
            </div>
        </div>
        <button type="submit" class="btn btn-default"><?php echo lang("btn_download")?></button>

        <input type="hidden" value="<?php echo $info->_url_view?>" name="url"/>
    </div>
</form>