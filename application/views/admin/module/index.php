<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var $main = $('#page-wrapper');

            // Sort list
            handle_sort_list($main, '<?php echo $url_update_order; ?>');

        });
    })(jQuery);
</script>


<!-- Common -->
<?php echo macro()->page(array('toolbar' => array())); ?>

<!-- Main content wrapper -->
<div class="wrapper">

    <!-- Danh sach da cai dat -->
    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4><i class="fa fa-list-ul"></i> <?php echo lang('title_list_install'); ?></h4>
            </div>
        </div>
        <div class="portlet-body no-padding">

            <table class="table table-bordered table-striped table-hover tc-table ">
                <thead>
                <tr>
                    <th class="sortCol" style="width:70px;"><?php echo lang('sort_order'); ?></th>
                    <th class="sortCol"><?php echo lang('name'); ?></th>
                    <th class="sortCol" style="width:100px;"><?php echo lang('status'); ?></th>
                    <th style="width:20%;"><?php echo lang('action'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($list_install as $row): ?>
                    <tr _list="1" _item="<?php echo $row->key; ?>">
                        <td class="textC"><?php echo $row->sort_order; ?></td>

                        <td><?php echo $row->name; ?></td>

                        <td class=" textC">
                            <?php
                            $v = ($row->status) ? 'on' : 'off';
                            echo macro()->status_color($v, lang($v)); ?>
                        </td>

                        <td class="option column_action">
                            <div class="btn-group btn-group-xs action-buttons">

                                <?php if ($row->_can_setting): ?>
                                    <a href="<?php echo $row->_url_setting; ?>" title="<?php echo lang('setting'); ?>"
                                       class="btn btn-primary btn-xs">
                                        <?php echo lang('button_setting'); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ($row->_can_edit): ?>
                                    <a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('setting'); ?>"
                                       class="btn btn-warning btn-xs">
                                        <?php echo lang('button_edit'); ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($row->_can_uninstall): ?>
                                    <a href="" _url="<?php echo $row->_url_uninstall; ?>"
                                       title="<?php echo lang('uninstall'); ?>"
                                       class="btn btn-danger btn-xs verify_action"
                                       notice="<?php echo lang('notice_confirm_uninstall'); ?>:<br><b><?php echo $row->name; ?></b>"
                                        >
                                        <?php echo lang('button_uninstall'); ?>
                                    </a>
                                <?php endif; ?>
                                <a title="<?php echo lang('sort'); ?>" class="  js-sortable-handle"
                                   style="cursor:move;">
                                    <i class="fa fa-arrows-alt icon-only"></i>
                                </a>


                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>


    <!-- Danh sach chua cai dat -->
    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4><i class="fa fa-list-ul"></i> <?php echo lang('title_list_uninstall'); ?></h4>
            </div>
        </div>
        <div class="portlet-body no-padding">

            <table class="table table-bordered table-striped table-hover tc-table ">
                <thead>
                <tr>
                    <th class="sortCol"><?php echo lang('name'); ?></th>
                    <th style="width:10%;"><?php echo lang('action'); ?></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($list_uninstall as $row): ?>
                    <tr>
                        <td><?php echo $row->name; ?></td>

                        <td class="option">
                            <div class="btn-group btn-group-xs action-buttons">
                                <?php if ($row->_can_install): ?>
                                    <a href="" _url="<?php echo $row->_url_install; ?>"
                                       title="<?php echo lang('install'); ?>"
                                       class="btn btn-primary btn-xs  verify_action"
                                       notice="<?php echo lang('notice_confirm_install'); ?>:<br><b><?php echo $row->name; ?></b>"
                                        >
                                        <?php echo lang('button_install'); ?>
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

        