<?php if ($user): // pr($user); ?>
        <form class="form_action"  action="<?php echo $url_set_point; ?>" method="post">
            <?php //form_csrf(); ?>
            <div class="pull-left">
            (Point: <?php echo $info->point_total ?>) <input type="text" name="set_point" value="<?php echo $info->point_fake ?>" class="form-control input-sm" >
            <a href="#0" _submit="true" class="btn btn-default btn-xs"> Set điểm</a>
            </div>

            <div class="checkbox pull-right">
                <a class="do_action" data-url="<?php echo $url_set_feature; ?>">
                <label title="Mới nổi">
                    <input  value="1" type="checkbox" <?php echo form_set_checkbox(1,$info->is_feature) ?>>
                    <span>&nbsp;</span>
                </label>
                <span >Mới nổi</span>
                </a>
            </div>
            <div class="checkbox pull-right mr10">
                <a class="do_action" data-url="<?php echo $url_set_lock; ?>">
                    <label title="Khóa tin">
                        <input  value="1" type="checkbox" <?php echo form_set_checkbox(1,$info->is_lock) ?>>
                        <span>&nbsp;</span>
                    </label>
                    <span >Khóa tin</span>
                </a>
            </div>
        </form>

<?php endif; ?>