<div class="portlet">
    <div class="portlet-heading bg-primary">
        <div class="portlet-title">
            <h4><i class="fa fa-info"></i> <?php echo lang('question_answer_info'); ?></h4>
        </div>

    </div>
    <div class="portlet-body ">
        <table class="table table-bordered table-striped table-hover tc-table">
            <tbody>
            <tr>
                <td class="row_label"><?php echo lang('id'); ?></td>
                <td class="row_item">
                    <?php echo $info->id; ?>
                </td>
            </tr>
            <tr>
                <td class="row_label"><?php echo lang('user'); ?></td>
                <td class="row_item">
                    <?php if ($info->user)
                        echo $info->user->name;
                    else echo '[deleted]' ?>
                </td>
            </tr>
            <tr>
                <td class="row_label"><?php echo lang('created'); ?></td>
                <td class="row_item">
                    <?php echo $info->_created_full; ?>
                </td>
            </tr>
            <tr>
                <td class="row_label"><?php echo lang('verify'); ?></td>
                <td class="row_item">
                    <?php if ($info->status): ?>
                        <a href="#0" class="btn btn-warning btn-xs  verify_action2 mt5"
                           notice="Bạn có chắc muốn hủy xác thực bình luận này?<?php ?>"
                           _url="<?php echo admin_url('question_answer/unverify/' . $info->id) ?>">Hủy xác thực</a>
                    <?php else: ?>
                        <a href="#0" class="btn btn-primary btn-xs  verify_action2 mt5"
                           notice="Bạn có chắc muốn xác thực bình luận này?<?php ?>"
                           _url="<?php echo admin_url('question_answer/verify/' . $info->id) ?>">Xác thực</a>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <b><?php echo lang("question") ?></b><br>
                    <?php echo nl2br(htmlentities($info->question)); ?>
                    <hr class="m20">
                    <b><?php echo lang("answer") ?></b><br>

                    <form class="form_action2 form-horizontal" method="post">
                        <input type="hidden" name="id" value="<?php echo $info->id ?>">
                        <div class="form-group param_html   ">
                            <div class="col-sm-12">
                              <textarea name="answer"  class="form-control editor" _config="{height:200}"></textarea>
                                <div class="clear"></div>
                                <div name="answer_error" class="error help-block"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-actions">
                            <div class="form-group formSubmit">
                                    <input type="submit" value="<?php echo lang('button_update'); ?>"
                                           class="btn btn-primary"/>
                            </div>
                        </div>

                        <div class="clear"></div>
                    </form>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('.verify_action2').nstUI('verifyAction');
        $('.form_action2').each(function()
        {
            var $this = $(this);
            $this.nstUI('formAction', {
                field_load: $this.attr('_field_load'),
            });
        })
    })
</script>