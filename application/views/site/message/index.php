<style>
    .readed {
        color: #006400;
    }
</style>
<?php echo macro()->page_heading(lang('message_sended')) ?>
<?php echo macro()->page_body_start() ?>
<?php view('tpl::message/_menu', ['current' => 'sended']); ?>

<form class="form_action" action="<?php echo $action; ?>" method="get">
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
        <th><?php echo lang('title'); ?></th>
        <th><?php echo lang('content'); ?></th>
        <th><?php echo lang('receive'); ?></th>
        <th><?php echo lang('created'); ?></th>
        <th><?php echo lang('view'); ?></th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($list as $row): ?>


        <tr>
            <td>
                <div class="fontB f12 blue"><?php echo $row->id; ?></div>
            </td>

            <td>
                <?php echo $row->title ?>
                <?php /* if (isset($row->user_execute->username)): ?>
                    <p><?php echo lang('user_execute') ?>: <b
                            style="color:red"><?php echo $row->user_execute->username ?></b></p>
                <?php endif; */ ?>
            </td>
            <td><?php echo character_limiter_len($row->content, 20) ?></td>
            <td>
                <?php foreach ($row->receives as $receive): ?>
                    <?php $status = ($receive->readed > 0) ? 'readed' : 'not_readed' ?>
                    <?php $title = ($receive->readed > 0) ? lang('readed') . ' lúc ' . get_date($receive->readed, 'full') : lang('not_readed') ?>

                    <p data-toggle="tooltip" style="margin-bottom:2px" title="<?php echo $title ?>">
                        <b><?php echo $receive->receive_username ?></b>: <span
                            class="<?php echo $status ?>"><?php echo lang($status) ?></span></p>
                <?php endforeach; ?>
            </td>

            <td class="status">
                <?php echo $row->_created_time; ?>
            </td>
            <td>
                <a href="<?php echo site_url('message/view_send/' . $row->id) ?>" class="lightbox_">
                    <i class="fa fa-eye" aria-hidden="true"></i> <?php echo lang('view') ?>
                </a>

                <?php if ($row->is_spam): ?>
                    <p>
                        <a href="javascript:void(0)" style="color:red" data-toggle="tooltip"
                           title="<?php echo lang('reported') ?>  (<?php echo $row->total_spam ?> lần)">
                            <i class="fa fa-times" aria-hidden="true"></i> <?php echo lang('reported') ?>
                            (<?php echo $row->total_spam ?>)
                        </a>
                    </p>
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