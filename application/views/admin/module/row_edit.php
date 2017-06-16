<!-- Common -->
<?php echo macro()->page(array(
    'toolbar' => macro('tpl::module/macros')->toolbar($table->module->key),
)); ?>

<!-- Main content wrapper -->
<div class="wrapper">
    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4><i class="fa fa-pencil"></i> <?php echo lang('edit') . ' ' . $table->name; ?></h4>
            </div>
        </div>
        <div class="portlet-body ">

            <form class="form" id="form" action="<?php echo $action; ?>" method="post">

                <?php
                foreach ($table->cols as $c => $o) {
                    $_data = array();
                    $_data['opt'] = $o;
                    $_data['name'] = $c;
                    $_data['id'] = "param_{$c}";
                    $_data['value'] = (isset($row->$c)) ? $row->$c : $o['value'];
                    $this->load->view('admin/_common/param_form', $_data);
                }
                ?>
                <div class="form-actions">
                    <div class="form-group formSubmit">
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="submit" class="btn btn-primary" value="<?php echo lang('button_update'); ?>">
                            <a class="btn"
                               href="<?php echo module_url('admin', $table->module->key, $table->key . '/list') ?>"><?php echo lang('button_cancel'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </form>

        </div>
    </div>
</div>
