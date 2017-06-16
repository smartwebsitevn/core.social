<?php if($can_do): ?>
    <a  href="javascript:void(0)" title="<?php echo lang("action_request") ?>" data-toggle="modal" data-target="#modal-request" class="btn btn-danger"><i class="fa fa-bullhorn"></i> <?php echo lang('action_request') ?></a>
    <div id="modal-request" class="modal fade" tabindex="-1" role="dialog"     >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"></span></button>
                    <h4 class="modal-title"><?php echo lang("action_request") ?></h4>
                </div>
                <div class="modal-body">
                    <form class="form_action form-horizontal" method="post" action="<?php echo $url_request ?>">
                        <div class="form-group">
                            <label  class="col-sm-3 control-label"><?php echo lang("name") ?></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name">
                                <div name="content_error" class="error"></div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label"><?php echo lang("notice") ?></label>
                            <div class="col-sm-9">
                                   <textarea name="content"  rows="4"       class="form-control"></textarea>
                                <div name="content_error" class="error"></div>

                            </div>
                        </div>
                        <?php echo macro('mr::form')->captcha($captcha); ?>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button class="btn btn-danger" type="submit"><?php echo lang("button_submit") ?></button>
                                <button data-dismiss="modal" class="btn btn-link" type="button"><?php echo lang("button_cancel") ?></button>
                            </div>
                        </div>

                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <a  href="javascript:void(0)" title='<?php echo lang("action_request") ?>'    class="btn btn-danger act-notify-modal" data-content="<?php echo lang('notice_please_login_to_use_function') ?>" ><i class="fa fa-bullhorn"></i> <?php echo lang('action_request') ?></a>
<?php endif; ?>