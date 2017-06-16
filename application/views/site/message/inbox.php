<style>
    .not_readed .status {
        color: red;
    }

    .readed .status {
        color: #006400;
    }
</style>
<?php view('site/message/js') ?>
<?php echo macro()->page_heading(lang('message_inbox')) ?>
<?php echo macro()->page_body_start() ?>
<?php view('tpl::message/_menu',['current'=>'inbox']); ?>
<form class="form_action form-filter" action="<?php echo $action; ?>" method="get">
    <div class="form-group pull-left mr10" style="width:100px">
        <span><?php echo lang('order_id'); ?>:</span><br>
        <input name="id" value="<?php echo $filter['id']; ?>" style="width:100px;" type="text" class="form-control fontB "/>
    </div>
    <div class="form-group pull-left mr10" style="width:100px">
        <span><?php echo lang('title'); ?>:</span><br>
        <input name="title" value="<?php echo $filter['title']; ?>" style="width:100px;" type="text"
               class="form-control fontB "/>
    </div>

    <div class="form-group pull-left mr10">
        <span><?php echo lang('date_start'); ?>:</span><br>
        <input name="created" value="<?php echo $filter['created']; ?>" style="width:130px;" type="text"
               class="form-control datepicker fontB  textC"/>
    </div>

    <div class="form-group pull-left mr10">
        <span><?php echo lang('date_end'); ?>:</span><br>
        <input name="created_to" value="<?php echo $filter['created_to']; ?>" style="width:130px;" type="text"
               class="form-control datepicker fontB  textC"/>
    </div>

    <div class="form-group pull-left mr10">
        <br>
        <input type="submit" value="<?php echo lang('submit'); ?>" class="btn btn-info"/>
        <input type="reset" value="Làm mới<?php //echo lang('button_reset'); ?>"
               class="btn" onclick="window.location.href = '<?php echo $action; ?>'; "/>

    </div>
</form>
<table style="margin-top:10px" class="table table-striped table-responsive">
    <thead>
    <tr style="color:#006699">
        <th><?php echo lang('id'); ?></th>
        <th><?php echo lang('sender'); ?></th>
        <th><?php echo lang('title'); ?></th>

        <th><?php echo lang('status'); ?></th>
        <th><?php echo lang('created'); ?></th>
        <th><?php echo lang('action'); ?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($list as $row): ?>
        <?php $status = ($row->readed > 0) ? 'readed' : 'not_readed' ?>

        <tr class="<?php echo $status ?>">
            <td>
                <div class="fontB f12 blue"><?php echo $row->id; ?></div>
            </td>
            <td><i class="fa fa-user" aria-hidden="true"></i> <?php echo $row->sender_username ?></td>

            <td><?php echo $row->title ?></td>

            <td class="status">
                <b><?php echo lang($status) ?></b>
                <?php if ($row->is_spam): ?>
                    <p style="color:red" data-toggle="tooltip"
                       title="<?php echo lang('spamed') ?>"><?php echo lang('spamed') ?></p>
                <?php endif; ?>
            </td>

            <td>
                <?php echo $row->_created_time; ?>
            </td>
            <td>
                <a href="<?php echo site_url('message/view/' . $row->id) ?>" data-toggle="tooltip"
                   title="<?php echo lang('view') ?>" class="lightbox_">

                    <i class="fa fa-eye" aria-hidden="true"></i> <?php echo lang('view') ?>
                </a>

                <?php if (!$row->is_spam): ?>
                    -
                    <a href="javascript:void(0)" data-id="<?php echo $row->id ?>" data-toggle="tooltip"
                       title="<?php echo lang('spam') ?>" class="action_spam">
                        <i class="fa fa-times" aria-hidden="true"></i> <?php echo lang('spam') ?>
                    </a>
                <?php endif; ?>

            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>
<div class="clear"></div>
<div class="auto_check_pages">
    <?php $this->widget->site->pages($pages_config); ?>
</div>
<div class="clear"></div>
<?php echo macro()->page_body_end() ?>
