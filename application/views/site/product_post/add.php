
<div class="product-info-main detail-social">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <!-- Form -->
            <form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
                <div class="tab-pane active" id="tab_general"><?php t('view')->load('tpl::product_post/form/general'); ?></div>

                <div class="form-actions">
                    <div class="form-group formSubmit">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="submit_back" value="<?php echo lang('button_update'); ?>"
                                   class="btn btn-primary"/>
                            <input value="<?php echo lang("button_reset") ?>" class="btn" type="reset">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <?php t('view')->load('tpl::product/_common/info_author') ?>
        </div>

    </div>
</div>


