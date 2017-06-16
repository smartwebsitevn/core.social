<?php $_id = '_' . random_string('unique'); ?>


<?php if ($sort): ?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                handle_sort_list($('#<?php echo $_id; ?>'), '<?php echo $sort_url_update; ?>');
            });
        })(jQuery);
    </script>
<?php endif; ?>


<!-- Common -->
<?php echo macro()->page(array(
    'toolbar' => macro('tpl::module/macros')->toolbar($table->module->key),
)); ?>


<!-- Main content wrapper -->
<div class="wrapper" id="<?php echo $_id; ?>">
    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4><i class="fa fa-list-ul"></i> <?php echo $table->name; ?></h4>
            </div>
        </div>
        <div class="portlet-body no-padding">

            <table class="table table-bordered table-striped table-hover tc-table ">
                <thead>
                <tr>
                    <th data-sort-ignore="true" class="col-small center"><label><input type="checkbox" class="tc"><span class="labels"></span></label></th>
                    <?php foreach ($table->cols as $c => $o): ?>
                        <?php if (!$o['show']) continue; ?>

                        <th class="sortCol"><?php echo $o['name']; ?></th>
                    <?php endforeach; ?>

                    <th class="col-2 textC"><?php echo lang('action'); ?></th>
                </tr>
                </thead>

                <tfoot class="auto_check_pages">
                <tr>
                    <td colspan="<?php echo count($table->cols) + 2; ?>">
                        <?php if (count($actions)): ?>
                            <div class="list_action itemActions">
                                <select name="action" class="left mr10">
                                    <option value=""><?php echo lang('select_action'); ?></option>
                                    <?php foreach ($actions as $a => $u): ?>
                                        <option value="<?php echo $u; ?>"><?php echo lang('action_' . $a); ?></option>
                                    <?php endforeach; ?>
                                </select>

                                <a href="#submit" id="submit" class="button blueB">
                                    <span class="white"><?php echo lang('button_submit'); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>


                        <div class="right">
                            <a href="<?php echo admin_url("md-{$table->module->key}/{$table->key}/update"); ?>"
                               class="btn btn-primary hide">
                               <?php echo lang('button_update'); ?>
                            </a>
                            <a href="<?php echo admin_url("md-{$table->module->key}/{$table->key}/add"); ?>"
                               class="btn btn-primary">
                              <?php echo lang('button_add'); ?>
                            </a>
                        </div>

                    </td>
                </tr>
                </tfoot>

                <tbody class="list_item">
                <?php //pr($rows);
                foreach ($rows as $row): ?>

                    <?php if ($sort): ?>
                        <tr _list="1" _item="<?php echo $row->_id; ?>">
                    <?php else: ?>
                        <tr>
                    <?php endif; ?>

                    <td class="col-small center"><label><input type="checkbox" class="tc" value="<?php echo $row->_id_full; ?>" name="id[]"><span class="labels"></span></label>
                    </td>
                    <?php foreach ($table->cols as $c => $o): ?>
                        <?php
                        if (!$o['show']) continue;

                        $v = (isset($row->{$c})) ? $row->{$c} : '';
                        $v = (is_array($v)) ? implode(', ', $v) : $v;
                        //echo '<br>name:';pr($v,0);
                        ?>

                        <td>
                            <?php if ($o['type'] == 'bool'): ?>
                                <div class="status textC"><span class="<?php echo ($v) ? 'on' : 'off'; ?>">
									<?php echo ($v) ? lang('on') : lang('off'); ?>
								</span></div>

                            <?php elseif (!empty($v)): ?>
                                <?php if ($o['type'] == 'file'): ?>
                                    <a href="<?php echo upload_url($v); ?>" target="_blank">File</a>

                                <?php elseif ($o['type'] == 'image'): ?>
                                    <a href="<?php echo upload_url($v); ?>" target="_blank">
                                        <img src="<?php echo upload_url($v); ?>" style="height:50px; max-width:100px;">
                                    </a>

                                <?php elseif ($o['type'] == 'date'): ?>
                                    <?php echo get_date($v); ?>

                                <?php elseif ($o['type'] == 'color'): ?>
                                    <?php echo '#' . $v; ?>

                                <?php else: ?>
                                    <?php // echo character_limiter(strip_tags($v), 30); ?>
                                    <?php echo $v; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>

                    <td class="option column_action textC">
                        <div class="btn-group btn-group-xs action-buttons">
                            <?php if ($row->_can_edit): ?>
                                <a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>"
                                   class="btn btn-warning btn-xs">
                                    <?php echo lang('button_edit'); ?>
                                </a>

                            <?php endif; ?>

                            <?php if ($row->_can_del): ?>
                                <a href="" _url="<?php echo $row->_url_del; ?>"
                                   title="<?php echo lang('delete'); ?>"
                                   class="btn btn-danger btn-xs verify_action"
                                   notice="<?php echo lang('notice_row_confirm_delete'); ?>"
                                    >
                                    <?php echo lang('button_delete'); ?>
                                </a>

                            <?php endif; ?>
                            <?php if ($sort): ?>

                                <a title="<?php echo lang('sort'); ?>" class="  js-sortable-handle"
                                   style="cursor:move;">
                                    <i class="fa fa-arrows-alt icon-only"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>