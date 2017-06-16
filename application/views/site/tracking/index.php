<?php widget('site')->breadcrumbs($breadcrumbs) ?>
<div class="about-us">
    <h4 class="node-title"><?php echo lang('tracking_search') ?></h4>
    <div class="content">
        <p class="text-danger"></p>
        <p class="text-success"></p>
        <form class="form-horizontal" data-postformdata="<?php echo $action ?>">
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo lang("no")?></label>
                <div class="col-sm-10">
                    <input type="text" name="no" class="form-control required" placeholder="<?php echo lang("no")?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo lang("security_code")?></label>
                <div class="col-sm-10">
                    <div class="pull-left dInline">
                        <?php $_id = random_string(); ?>
                        <input name="security_code" class="form-control required" style='display: inline-block; width: 100px' id="<?php echo $_id; ?>"
                               autocomplete="off" type="text"/>

                        <div name="security_code_autocheck" class="autocheck inner"></div>
                    </div>

                    <div class="pull-left dInline ml5">
                        <img id="<?php echo $_id . '_captcha'; ?>" src="<?php echo $captcha; ?>"
                             _captcha="<?php echo $captcha; ?>" class="dInline captcha">
                        <a href="#reset" class="resetcapcha" onclick="change_captcha('<?php echo $_id . '_captcha'; ?>'); return false;"
                           class="dInline" title="Reset captcha">
                            <img src="<?php echo public_url('site'); ?>/css/img/reset.png" class="dInline ml5">
                        </a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default"><?php echo lang("search")?></button>
                </div>
            </div>
        </form>

        <div id="tracking_content">

        </div>
    </div>
</div>

<script>
    jQuery('[data-postformdata]').submit(function(){

        var validate = true;
        jQuery(this).find('input.required, textarea.required').each(function() {
            if(!jQuery(this).val())
            {
                jQuery('.text-danger').html(jQuery(this).attr('placeholders'));
                jQuery(this).focus();
                validate = false;
                return false;
            }
        });
        if(!validate)
            return false;
        jQuery('.text-danger').html('');

        var form = jQuery(this);
        // gui xu ly
        jQuery.ajax({
            url: jQuery(this).data('postformdata'),
            dataType: "json",
            type: "POST",
            data: new FormData( this ),
            processData: false,
            contentType: false,
            success: function(data){

                if(data.status)
                {
                    $('#tracking_content').html(data.content);
                    jQuery('.text-danger').html('');
                    jQuery('.text-success').html(data.label);
                    //huy form
                    // hủy dữ liệu tại các ô input
                    form.find('input[type="text"]:not(.sort,.datepicker,.notclear), input[type="email"]:not(.notclear), input[type="file"], input[type="password"], input[type="hidden"], textarea').each(function(index){
                        var input = jQuery(this);
                        input.val('');
                    });
                    form.find('.resetcapcha').click();
                }
                else
                {
                    jQuery('.text-danger').html(data.label);
                }
            },
            error : function ()
            {

            }
        });
        return false;
    })
    function change_captcha(field)
    {
        var t = jQuery('#'+field);
        var url = t.attr('_captcha')+'?id='+Math.random();
        t.attr('src', url);
        return false;
    }
</script>